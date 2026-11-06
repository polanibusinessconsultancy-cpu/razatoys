<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use App\Models\Coupon;
use App\Models\UserCourse;
use App\Models\PaymentMethod;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Shoppingcart;
use App\Services\CheckoutCartResolver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $paymentMethod = null; // Start with no default payment method
    public $confirmPayment = false; // Confirmation property
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $address;
    public $postalCode;
    public $cardNumber;
    public $expiryDate;
    public $cvv;
    public $isProcessing = false;
    public $discount = 0;
    public $couponCode = '';
    public $couponError = '';
    public $couponSuccess = '';
    public $appliedCoupon = null;
    public $country = '';
    public $state = '';
    public $city = '';
    public $paymentMethods = []; // Store all available payment methods

    protected $listeners = [
        'paymentMethodUpdated' => 'updatePaymentMethod',
        'address-selected' => 'handleAddressSelected'
    ];

    public function isStripeReady(): bool
    {
        return config('services.stripe.enabled') === true
            && !empty(config('services.stripe.key'))
            && !empty(config('services.stripe.secret'))
            && !empty(config('services.stripe.webhook_secret'))
            && \Illuminate\Support\Facades\Schema::hasColumn('orders', 'stripe_session_id')
            && PaymentMethod::where('key', 'card')->where('is_active', true)->exists();
    }

    public function mount()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->firstName = $user->first_name ?? '';
            $this->lastName = $user->last_name ?? '';
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
        }

        // Generate stable checkout attempt token if none exists in session
        if (!session()->has('checkout.attempt_token')) {
            session()->put('checkout.attempt_token', (string) Str::uuid());
        }

        // Resolve trusted cart items and totals from the canonical resolver
        $resolver = app(CheckoutCartResolver::class);
        $resolved = $resolver->resolve(auth()->id(), session('polani_cart', []));
        $this->cartItems = $resolved['items']->toArray();
        $this->total = $resolved['total'];

        // If no items or total is 0, redirect back to cart
        if (empty($this->cartItems) || $this->total <= 0) {
            session()->flash('error', 'Your cart is empty or invalid.');
            return redirect()->route('shopping-cart');
        }

        // Load active payment methods dynamically based on Stripe readiness
        $methodsQuery = PaymentMethod::where('is_active', true);
        if (!$this->isStripeReady()) {
            $methodsQuery->where('key', '!=', 'card');
        }
        $this->paymentMethods = $methodsQuery->orderBy('sort_order')->get();

        // Set default payment method (first active method)
        if ($this->paymentMethods->isNotEmpty()) {
            $this->paymentMethod = $this->paymentMethods->first()->key;
        }
    }

    public function createOrder()
    {
        if ($this->isProcessing) {
            return;
        }

        if (!auth()->check()) {
            return redirect()->route('sign-in')
                ->with('error', 'Please sign in to place your order, whether you pay by COD or online.');
        }

        // Reload trusted cart & recalculate subtotal server-side
        $resolver = app(CheckoutCartResolver::class);
        $resolved = $resolver->resolve(auth()->id(), session('polani_cart', []));
        $resolvedItems = $resolved['items'];
        $resolvedTotal = $resolved['total'];

        if ($resolvedItems->isEmpty() || $resolvedTotal <= 0) {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->route('shopping-cart');
        }

        // Validate that all products in the cart are storefront visible (available)
        foreach ($resolvedItems as $item) {
            if (isset($item['is_available']) && !$item['is_available']) {
                session()->flash('error', 'Some items in your cart are no longer available. Please remove them before proceeding.');
                return redirect()->route('shopping-cart');
            }
        }

        // Validate that no free items are in the cart
        foreach ($resolvedItems as $item) {

            if (isset($item['course_id'])) {
                $course = Course::find($item['course_id']);
                if ($course && $course->is_free) {
                    session()->flash('error', 'Free courses cannot be purchased. Please remove "' . $course->name . '" from your cart and use the free enrollment option instead.');
                    return redirect()->route('shopping-cart');
                }
            } elseif (isset($item['lecture_id'])) {
                $lecture = Lecture::find($item['lecture_id']);
                if ($lecture && $lecture->is_free) {
                    session()->flash('error', 'Free lectures cannot be purchased. Please remove "' . $lecture->name . '" from your cart and use the free enrollment option instead.');
                    return redirect()->route('shopping-cart');
                }
            }
        }

        // Validate that restricted course lectures aren't being purchased individually
        foreach ($resolvedItems as $item) {
            if (isset($item['lecture_id'])) {
                $lecture = Lecture::find($item['lecture_id']);
                if ($lecture && $lecture->belongsToRestrictedCourse()) {
                    session()->flash('error', 'Lecture "' . $lecture->name . '" can only be purchased as part of the complete course. Please remove it from your cart.');
                    return redirect()->route('shopping-cart');
                }
            }
        }

        // Revalidate coupon
        $coupon = null;
        $discount = 0.0;
        if ($this->appliedCoupon) {
            $coupon = Coupon::find($this->appliedCoupon->id);
            if (!$coupon || !$coupon->is_active) {
                session()->flash('error', 'The coupon is no longer active.');
                return;
            }
            $now = now();
            if ($now < $coupon->valid_from || $now > $coupon->valid_until) {
                session()->flash('error', 'The coupon is expired or not yet valid.');
                return;
            }
            $discount = (float) $coupon->calculateDiscount($resolvedTotal);
        }

        // Calculate final total after discount
        $finalTotal = max(0.0, $resolvedTotal - $discount);

        // If total is 0 after discount, set payment method to free
        if ($finalTotal <= 0.0) {
            $this->paymentMethod = 'free';
        }

        // Validate shipping details and payment method selection
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postalCode' => 'required|string|max:20',
            'paymentMethod' => 'required',
        ]);

        $this->isProcessing = true;

        try {
            // Server-side payment method check
            if ($finalTotal > 0.0) {
                if ($this->paymentMethod === 'free') {
                    throw new \Exception('Invalid payment method.');
                }
                if ($this->paymentMethod === 'card' && !$this->isStripeReady()) {
                    throw new \Exception('Card payment is temporarily unavailable.');
                }
                // Verify against active database payment methods
                $methodExists = PaymentMethod::where('key', $this->paymentMethod)
                    ->where('is_active', true)
                    ->exists();
                if (!$methodExists) {
                    throw new \Exception('Selected payment method is invalid or inactive.');
                }
            } else {
                $this->paymentMethod = 'free';
            }

            $checkoutToken = session()->get('checkout.attempt_token');
            if (!$checkoutToken) {
                throw new \Exception('Invalid checkout session token.');
            }

            // Database transaction block
            $order = DB::transaction(function () use ($resolvedItems, $resolvedTotal, $discount, $finalTotal, $checkoutToken, $coupon) {
                // Idempotency: check if order already exists for this attempt token
                $existingOrder = Order::where('checkout_token', $checkoutToken)->first();
                if ($existingOrder) {
                    if ((int)$existingOrder->user_id !== (int)auth()->id()) {
                        throw new \Exception('Unauthorized order access.');
                    }
                    return $existingOrder;
                }

                // Under database lock, check coupon usage limit
                if ($coupon && $coupon->max_uses !== null) {
                    $couponUses = Order::where('coupon_code', $coupon->code)
                        ->where('status', '!=', 'failed')
                        ->count();
                    if ($couponUses >= $coupon->max_uses) {
                        throw new \Exception('Coupon usage limit exceeded.');
                    }
                }

                // Determine payment status
                $paymentStatus = 'unpaid';
                if ($finalTotal <= 0.0 || $this->paymentMethod === 'free') {
                    $paymentStatus = 'paid';
                }

                // Create the Order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'cart_items' => $resolvedItems->toJson(),
                    'total' => $resolvedTotal,
                    'discount' => $discount,
                    'final_total' => $finalTotal,
                    'status' => ($finalTotal <= 0.0 || $this->paymentMethod === 'free') ? 'completed' : 'pending',
                    'payment_status' => $paymentStatus,
                    'payment_method' => $this->paymentMethod,
                    'billing_address' => json_encode([
                        'first_name' => $this->firstName,
                        'last_name' => $this->lastName,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'address' => $this->address,
                        'country' => $this->country,
                        'state' => $this->state,
                        'city' => $this->city,
                        'postal_code' => $this->postalCode,
                    ]),
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'checkout_token' => $checkoutToken,
                ]);

                // Do not increment coupon usages for card payment at order creation
                if ($coupon && $this->paymentMethod !== 'card') {
                    $coupon->incrementUses();
                }

                // For completed orders (free orders), grant user access to courses/lectures
                if ($order->status === 'completed') {
                    $userId = auth()->id();
                    foreach ($resolvedItems as $item) {
                        if (isset($item['course_id'])) {
                            $course = Course::find($item['course_id']);
                            if ($course && $course->product_kind === 'course') {
                                UserCourse::firstOrCreate([
                                    'user_id' => $userId,
                                    'course_id' => $item['course_id'],
                                    'lecture_id' => null,
                                    'status' => 'active'
                                ], [
                                    'order_id' => $order->id
                                ]);
                            }
                        }
 elseif (isset($item['lecture_id'])) {
                            $lecture = Lecture::find($item['lecture_id']);
                            if ($lecture) {
                                UserCourse::firstOrCreate([
                                    'user_id' => $userId,
                                    'course_id' => $lecture->course_id,
                                    'lecture_id' => $item['lecture_id'],
                                    'status' => 'active'
                                ], [
                                    'order_id' => $order->id
                                ]);
                            }
                        }
                    }
                }

                // Clear represented cart rows only (except card payments which clear after payment success)
                if ($this->paymentMethod !== 'card') {
                    $cartRowIds = $resolvedItems->pluck('cart_row_id')->filter()->toArray();
                    if (!empty($cartRowIds)) {
                        Shoppingcart::where('user_id', auth()->id())
                            ->whereIn('id', $cartRowIds)
                            ->delete();
                    }
                }

                return $order;
            });

            // Commit successful: clear the checkout attempt token
            session()->forget('checkout.attempt_token');
            $this->isProcessing = false;

            // Handle Stripe card redirection
            if ($order->payment_method === 'card') {
                try {
                    $amountMinor = (int) round(((float) $order->final_total) * 100);
                    if ($amountMinor <= 0) {
                        throw new \Exception("Invalid card payment amount.");
                    }
                    $currency = strtolower(config('services.stripe.currency', 'pkr'));

                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

                    // Retrieve customer email safely from authenticated user or billing address details
                    $customerEmail = auth()->user()->email ?? $this->email;

                    $session = \Stripe\Checkout\Session::create([
                        'mode' => 'payment',
                        'payment_method_types' => ['card'],
                        'line_items' => [[
                            'price_data' => [
                                'currency' => $currency,
                                'unit_amount' => $amountMinor,
                                'product_data' => [
                                    'name' => 'Order ' . $order->id,
                                ],
                            ],
                            'quantity' => 1,
                        ]],
                        'client_reference_id' => (string) $order->id,
                        'metadata' => [
                            'order_id' => (string) $order->id,
                            'checkout_token' => (string) $order->checkout_token,
                        ],
                        'customer_email' => $customerEmail,
                        'success_url' => route('payment.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                    ], [
                        'idempotency_key' => 'stripe_checkout_order_' . $order->id
                    ]);

                    // Persist session details
                    $order->update([
                        'stripe_session_id' => $session->id,
                        'gateway_amount' => $amountMinor,
                        'gateway_currency' => $currency,
                    ]);

                    return redirect()->away($session->url);

                } catch (\Throwable $e) {
                    Log::error('Stripe Checkout session creation failed: ' . $e->getMessage(), ['exception' => $e]);
                    session()->flash('error', 'Unable to initialize card payment. Please try again.');
                    $this->isProcessing = false;
                    return;
                }
            }

            if ($order->status === 'completed') {
                return redirect()->route('payment.success', ['order' => $order->id])
                    ->with('success', 'Your order has been completed successfully! You can now access your courses.');
            } else {
                $selectedMethod = PaymentMethod::where('key', $this->paymentMethod)->first();
                return redirect()->route('payment.pending', ['order' => $order->id])
                    ->with('success', 'Your order has been placed. ' . ($selectedMethod->instructions ?? ''));
            }

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            session()->flash('error', 'Unable to process your order. Please try again.');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.checkout')->layout('layouts.app');
    }

    public function updatePaymentMethod($method)
    {
        $this->paymentMethod = $method;
        $this->confirmPayment = false; // Reset confirmation when payment method changes
    }

    public function updatedConfirmPayment($value)
    {
        // This method will be called whenever confirmPayment changes
    }

    public function updatedPaymentMethod($value)
    {
        // Reset confirmation when payment method changes
        $this->confirmPayment = false;
    }

    public function applyCoupon()
    {
        $this->validate([
            'couponCode' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code';
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        if (!$coupon->is_active) {
            $this->couponError = 'This coupon is inactive';
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        if ($coupon->max_uses !== null && $coupon->uses_count >= $coupon->max_uses) {
            $this->couponError = 'This coupon has reached its maximum usage limit';
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        $now = now();
        if ($now < $coupon->valid_from) {
            $this->couponError = 'This coupon is not yet valid. Valid from: ' . $coupon->valid_from->format('M d, Y');
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        if ($now > $coupon->valid_until) {
            $this->couponError = 'This coupon has expired. Expired on: ' . $coupon->valid_until->format('M d, Y');
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        // Recalculate using trusted subtotal
        $resolver = app(CheckoutCartResolver::class);
        $resolved = $resolver->resolve(auth()->id(), session('polani_cart', []));
        $this->total = $resolved['total'];

        $this->discount = $coupon->calculateDiscount($this->total);
        $this->couponSuccess = 'Coupon applied successfully!';
        $this->couponError = '';
        $this->appliedCoupon = $coupon;

        // Check if the total after discount is zero
        if (($this->total - $this->discount) <= 0) {
            $user = auth()->user();

            // Auto-fill required fields from user data if they're empty
            if (empty($this->firstName)) {
                $this->firstName = $user->first_name ?? explode(' ', $user->name)[0] ?? 'User';
            }
            if (empty($this->lastName)) {
                $this->lastName = $user->last_name ?? (count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name)[1] : 'Customer');
            }
            if (empty($this->email)) {
                $this->email = $user->email;
            }
            if (empty($this->phone)) {
                $this->phone = $user->phone ?? '';
            }
            if (empty($this->address)) {
                $this->address = $user->address ?? 'Not Provided';
            }
            if (empty($this->country)) {
                $this->country = $user->country ?? 'Not Provided';
            }
            if (empty($this->state)) {
                $this->state = $user->state ?? 'Not Provided';
            }
            if (empty($this->city)) {
                $this->city = $user->city ?? 'Not Provided';
            }
            if (empty($this->postalCode)) {
                $this->postalCode = $user->postal_code ?? '00000';
            }

            // Set payment method to 'free' for zero amount orders
            $this->paymentMethod = 'free';
            $this->confirmPayment = true;
        }
    }

    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->discount = 0;
        $this->couponError = '';
        $this->couponSuccess = '';
        $this->appliedCoupon = null;
        $this->confirmPayment = false;
        if ($this->paymentMethod === 'free') {
            $this->paymentMethod = null;
        }
    }

    public function handleAddressSelected($addressData)
    {
        $this->address = $addressData['address'];
        $this->city = $addressData['city'];
        $this->state = $addressData['state'];
        $this->country = $addressData['country'];
        $this->postalCode = $addressData['postalCode'];
    }
}
