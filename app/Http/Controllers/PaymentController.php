<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Product;
use App\Models\Order;
use App\Models\UserCourse;
use App\Models\PaymentMethod;
use App\Models\Lecture;

use App\Services\StripeOrderFinalizer;

class PaymentController extends Controller
{
    public function success($order)
    {
        if (!auth()->check()) {
            abort(401);
        }

        $order = Order::findOrFail($order);

        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        // If it's a card order and still pending, fetch session from Stripe and finalize
        if ($order->payment_method === 'card' && $order->status === 'pending') {
            $sessionId = request()->query('session_id');
            if (empty($sessionId) || $sessionId !== $order->stripe_session_id) {
                abort(400, 'Invalid or missing session_id.');
            }

            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    app(StripeOrderFinalizer::class)->finalize($order, $session);
                    // Reload to get updated state
                    $order = $order->fresh();
                } else {
                    return redirect()->route('checkout')->with('error', 'Payment has not been completed yet.');
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Stripe success retrieval failed: ' . $e->getMessage());
                return redirect()->route('checkout')->with('error', 'Unable to verify payment status with Stripe.');
            }
        }

        // Non-card orders that are still pending shouldn't access the success page directly
        if ($order->payment_method !== 'card' && $order->status === 'pending') {
            return redirect()->route('payment.pending', ['order' => $order->id]);
        }

        $context = $this->buildOrderStatusContext($order);

        return view('polani.order-status', [
            'order' => $order,
            'message' => 'Your order has been placed successfully. A confirmation email has been sent to your inbox.',
            'paymentMethod' => PaymentMethod::where('key', $order->payment_method)->first(),
            'statusLabel' => 'Order Confirmed',
            'statusTone' => 'success',
            'actionLabel' => 'Continue Shopping',
            'actionUrl' => route('polani.collection'),
            'orderNumber' => $context['orderNumber'],
            'orderDate' => $context['orderDate'],
            'estimatedDelivery' => $context['estimatedDelivery'],
            'billingAddress' => $context['billingAddress'],
            'orderedItems' => $context['orderedItems'],
            'recommendations' => $context['recommendations'],
            'paymentLabel' => $context['paymentLabel'],
            'subtotal' => $context['subtotal'],
            'shippingLabel' => $context['shippingLabel'],
            'totalAmount' => $context['totalAmount'],
        ]);
    }

    public function cancel($order)
    {
        if (!auth()->check()) {
            abort(401);
        }

        $order = Order::findOrFail($order);

        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        if ($order->status === 'completed' || $order->payment_status === 'paid') {
            return redirect()->route('checkout')->with('error', 'This order is already paid.');
        }

        // Update order status to 'failed'
        $order->update([
            'status' => 'failed',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('checkout')->with('error', 'Payment was cancelled. Please try again.');
    }

    public function pending($order)
    {
        if (!auth()->check()) {
            abort(401);
        }

        $order = Order::findOrFail($order);

        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        // Get payment method details from database
        $paymentMethod = \App\Models\PaymentMethod::where('key', $order->payment_method)->first();

        // Get payment instructions
        $instructions = $paymentMethod ? $paymentMethod->instructions : $this->getDefaultInstructions($order->payment_method);
        $context = $this->buildOrderStatusContext($order);

        return view('polani.order-status', [
            'order' => $order,
            'message' => $instructions,
            'paymentMethod' => $paymentMethod,
            'statusLabel' => 'Order Received',
            'statusTone' => 'pending',
            'actionLabel' => 'Continue Shopping',
            'actionUrl' => route('polani.collection'),
            'orderNumber' => $context['orderNumber'],
            'orderDate' => $context['orderDate'],
            'estimatedDelivery' => $context['estimatedDelivery'],
            'billingAddress' => $context['billingAddress'],
            'orderedItems' => $context['orderedItems'],
            'recommendations' => $context['recommendations'],
            'paymentLabel' => $context['paymentLabel'],
            'subtotal' => $context['subtotal'],
            'shippingLabel' => $context['shippingLabel'],
            'totalAmount' => $context['totalAmount'],
        ]);
    }


    private function buildOrderStatusContext(Order $order): array
    {
        $cartItems = json_decode($order->cart_items, true);
        $orderedItems = [];
        $excludeIds = [];
        $subtotal = (float) ($order->total ?? 0);
        $totalAmount = (float) ($order->final_total ?? $order->total ?? 0);

        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                $productId = $item['product_id'] ?? null;
                if ($productId) {
                    $product = Product::find($productId);

                    if ($product) {
                        $quantity = (int) ($item['quantity'] ?? 1);
                        $price = (float) ($item['price'] ?? $product->weekly_price ?? 0);
                        $orderedItems[] = [
                            'name' => $product->name,
                            'slug' => $product->slug,
                            'image' => $product->image_path ? asset($product->image_path) : asset('polani/assets/product-noir-elixir.jpg'),
                            'quantity' => $quantity,
                            'unit_price' => $price,
                            'line_total' => $price * $quantity,
                        ];
                        $excludeIds[] = $product->id;
                    }
                }
            }
        }

        if (empty($orderedItems)) {
            $fallbackProducts = Course::query()->orderBy('id')->take(3)->get();
            foreach ($fallbackProducts as $product) {
                $orderedItems[] = [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image_path ? asset($product->image_path) : asset('polani/assets/product-noir-elixir.jpg'),
                    'quantity' => 1,
                    'unit_price' => (float) ($product->weekly_price ?? 0),
                    'line_total' => (float) ($product->weekly_price ?? 0),
                ];
                $excludeIds[] = $product->id;
            }
        }

        $recommendations = Course::query()
            ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
            ->orderBy('id')
            ->take(4)
            ->get();

        $billingAddressData = json_decode($order->billing_address ?? '{}', true);
        if (!is_array($billingAddressData)) {
            $billingAddressData = [];
        }

        $fullName = trim(($billingAddressData['first_name'] ?? '') . ' ' . ($billingAddressData['last_name'] ?? ''));
        $addressLines = array_filter([
            $billingAddressData['address'] ?? null,
            trim(($billingAddressData['city'] ?? '') . (!empty($billingAddressData['state']) ? ', ' . $billingAddressData['state'] : '')),
            trim(($billingAddressData['country'] ?? '') . (!empty($billingAddressData['postal_code']) ? ' ' . $billingAddressData['postal_code'] : '')),
        ]);

        $paymentLabel = $this->formatPaymentLabel($order->payment_method);

        return [
            'orderNumber' => sprintf('#PF-%s-%04d', now()->format('Y'), $order->id),
            'orderDate' => optional($order->created_at)->format('F j, Y') ?? now()->format('F j, Y'),
            'estimatedDelivery' => now()->addDays(4)->format('M j') . ' - ' . now()->addDays(8)->format('M j, Y'),
            'billingAddress' => [
                'name' => $fullName ?: 'Polani Customer',
                'email' => $billingAddressData['email'] ?? null,
                'phone' => $billingAddressData['phone'] ?? null,
                'lines' => $addressLines,
            ],
            'orderedItems' => $orderedItems,
            'recommendations' => $recommendations,
            'paymentLabel' => $paymentLabel,
            'subtotal' => $subtotal,
            'shippingLabel' => 'Free',
            'totalAmount' => $totalAmount,
        ];
    }

    private function formatPaymentLabel(?string $method): string
    {
        return match ($method) {
            'card' => 'Card Payment',
            'cash' => 'Cash on Delivery',
            'jazzcash' => 'JazzCash',
            'easypaisa' => 'Easypaisa',
            'banktransfer' => 'Bank Transfer',
            default => $method ? ucwords(str_replace(['_', '-'], ' ', $method)) : 'Polani Payment',
        };
    }

    private function getDefaultInstructions($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'cash':
                return 'Please visit our office to make your cash payment. Remember to bring your order number.';

            case 'jazzcash':
                return 'Please send your payment to our Jazz Cash account: +92 333 1234567 and send the screenshot to +92 312 9876543.';

            case 'easypaisa':
                return 'Please send your payment to our Easypaisa account: +92 345 1234567 and send the screenshot to +92 312 9876543.';

            case 'banktransfer':
                return 'Please transfer the amount to our bank account: 1234-5678-9012-3456 (HBL Pakistan) and send the payment confirmation to +92 312 9876543.';

            default:
                return 'Your order is being processed. You will receive further instructions shortly.';
        }
    }
}
