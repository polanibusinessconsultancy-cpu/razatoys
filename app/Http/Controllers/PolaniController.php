<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Order;
use App\Models\Shoppingcart;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PolaniController extends Controller
{
    private array $notesMap = [
        'exclusive-reserve' => [
            'top' => ['Grapefruit', 'Aromatic Notes'],
            'heart' => ['Refined Spices', 'Soft Florals'],
            'base' => ['Warm Woods', 'Amber'],
        ],
        'oud-candle' => [
            'top' => ['Oud', 'Warm Spice'],
            'heart' => ['Smoke', 'Resin'],
            'base' => ['Amber', 'Woods'],
        ],
        'noir-candle' => [
            'top' => ['Rose'],
            'heart' => ['Jasmine'],
            'base' => ['Amber'],
        ],
        'qasr-al-oud' => [
            'top' => ['Saffron', 'Bergamot'],
            'heart' => ['Oud', 'Rose'],
            'base' => ['Amber', 'Musk', 'Patchouli'],
        ],
        'amber-woods-attar' => [
            'top' => ['Amber'],
            'heart' => ['Woods'],
            'base' => ['Musk', 'Resin'],
        ],
        'musk-silk-attar' => [
            'top' => ['Musk'],
            'heart' => ['Soft Powder'],
            'base' => ['Creamy Woods'],
        ],
        'citrus-grove' => [
            'top' => ['Bergamot', 'Orange'],
            'heart' => ['Green Notes'],
            'base' => ['Clean Woods'],
        ],
        'velvet-rose' => [
            'top' => ['Rose'],
            'heart' => ['Floral Accord'],
            'base' => ['Musk', 'Vanilla'],
        ],
    ];

    private array $ratingMap = [
        'exclusive-reserve' => ['rating' => 4.9, 'reviews' => 124],
        'oud-candle' => ['rating' => 4.6, 'reviews' => 54],
        'noir-candle' => ['rating' => 4.7, 'reviews' => 41],
        'qasr-al-oud' => ['rating' => 4.9, 'reviews' => 124],
        'amber-woods-attar' => ['rating' => 4.8, 'reviews' => 66],
        'musk-silk-attar' => ['rating' => 4.8, 'reviews' => 58],
        'citrus-grove' => ['rating' => 4.7, 'reviews' => 63],
        'velvet-rose' => ['rating' => 4.8, 'reviews' => 76],
        'noir-elixir' => ['rating' => 4.7, 'reviews' => 98],
        'oud-royale' => ['rating' => 4.9, 'reviews' => 112],
        'bleu-mist' => ['rating' => 4.6, 'reviews' => 47],
        'amber-muse' => ['rating' => 4.7, 'reviews' => 62],
    ];

    private function productType(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => 'Scented Candle',
            'Attars' => 'Attar',
            default => 'Extrait de Parfum',
        };
    }

    private function productViewModel(Course $course): array
    {
        $slug = $course->slug;
        $category = $course->category?->name;
        $imagePath = $course->image_path ?: 'polani/assets/product-noir.svg';
        $categoryFilter = match ($category) {
            'Scented Candles' => 'candles',
            'Attars' => 'attars',
            'Oud' => 'oud',
            'Women' => 'women',
            default => 'men',
        };
        $rating = $course->average_rating;
        $reviews = $course->rating_count;
        $notes = $this->notesMap[$slug] ?? [
            'top' => ['Citrus'],
            'heart' => ['Amber'],
            'base' => ['Musk', 'Woods'],
        ];

        return [
            'db_id' => $course->id,
            'id' => $slug,
            'slug' => $slug,
            'name' => $course->name,
            'type' => $this->productType($category),
            'price' => (float) ($course->weekly_price ?? 0),
            'old_price' => (float) ($course->monthly_price ?? 0),

            'rating' => $rating,
            'reviews' => $reviews,
            'category_slug' => $categoryFilter,
            'category_name' => $category,
            'family' => $this->familyForCategory($category),
            'occasion' => $this->occasionForCategory($category),
            'image' => $course->image_url ?: asset($imagePath),
            'notes' => $notes,
            'longevity' => $this->longevityForCategory($category),
            'projection' => $this->projectionForCategory($category),
            'season' => $this->seasonForCategory($category),
            'description' => $course->description,
            'long_description' => $course->long_description,
            'intro_video_url' => $course->intro_video_url,
        ];
    }

    private function familyForCategory(?string $category): array
    {
        return match ($category) {
            'Attars' => ['amber', 'musky'],
            'Scented Candles' => ['amber', 'floral'],
            'Oud' => ['woody', 'amber'],
            'Women' => ['floral', 'amber'],
            default => ['woody', 'spicy'],
        };
    }

    private function occasionForCategory(?string $category): array
    {
        return match ($category) {
            'Scented Candles' => ['daily', 'luxury'],
            'Attars' => ['daily', 'evening'],
            'Women' => ['daily', 'evening'],
            'Oud' => ['evening', 'luxury'],
            default => ['office', 'evening'],
        };
    }

    private function longevityForCategory(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => '40 hours',
            'Attars' => '10–12 hours',
            'Oud' => '10–12 hours',
            default => '8–10 hours',
        };
    }

    private function projectionForCategory(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => 'Room-filling',
            'Oud' => 'Strong',
            'Attars' => 'Moderate',
            default => 'Moderate',
        };
    }

    private function seasonForCategory(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => 'All seasons',
            'Attars' => 'All seasons',
            'Women' => 'All seasons',
            default => 'Fall / Winter',
        };
    }

    private function sessionCart(): array
    {
        return session()->get('polani_cart', []);
    }

    private function saveSessionCart(array $items): void
    {
        session()->put('polani_cart', array_values($items));
    }

    private function cartCount(): int
    {
        if (auth()->check()) {
            return Shoppingcart::where('user_id', auth()->id())->count();
        }

        return count($this->sessionCart());
    }

    private function categoryPage(string $categoryName, string $title, string $description, string $heroImage, string $heroPosition = 'center', string $pageKey = 'collection')
    {
        $products = Course::query()
            ->where('is_storefront_visible', true)
            ->with('category')
            ->whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->latest('id')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course))
            ->values();

        return view('polani.category', [
            'pageTitle' => $title,
            'pageDescription' => $description,
            'heroImage' => $heroImage,
            'heroPosition' => $heroPosition,
            'collectionLabel' => $categoryName,
            'pageKey' => $pageKey,
            'products' => $products,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function home()
    {
        $featuredKeys = config('one_stop_mart.featured_source_keys', []);
        
        $products = Course::query()
            ->where('is_storefront_visible', true)
            ->whereIn('source_key', $featuredKeys)
            ->with('category')
            ->get();

        if ($products->isEmpty()) {
            $products = Course::query()
                ->where('is_storefront_visible', true)
                ->with('category')
                ->latest('id')
                ->take(8)
                ->get();
        }

        $preferredSlugs = config('one_stop_mart.category_display_order', []);
        $categories = \App\Models\Category::where('is_storefront_category', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_storefront_visible', true);
            }])
            ->get()
            ->sortBy(function ($category) use ($preferredSlugs) {
                $index = array_search($category->slug, $preferredSlugs);
                return $index === false ? 999999 : $index;
            })
            ->take(7)
            ->values();

        foreach ($categories as $category) {
            $category->courses_count = $category->products_count;
        }

        $blogs = Blog::where('is_storefront_visible', true)->latest('id')->take(3)->get();

        $signatureProductData = Course::with('category')
            ->where('is_storefront_visible', true)
            ->where('slug', 'qasr-al-oud')
            ->first()
            ?? Course::with('category')
                ->where('is_storefront_visible', true)
                ->latest('id')
                ->first();

        $banners = \App\Models\CarouselSlide::where('is_active', true)
            ->where('page_key', 'home')
            ->orderBy('order')
            ->get();

        return view('polani.home', [
            'products' => $products->map(fn (Course $course) => $this->productViewModel($course)),
            'categories' => $categories,
            'signatureProduct' => $signatureProductData ? $this->productViewModel($signatureProductData) : null,
            'blogs' => $blogs,
            'banners' => $banners,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function newArrivals()
    {
        $newArrivalKeys = config('one_stop_mart.new_arrival_source_keys', []);
        
        $products = Course::query()
            ->where('is_storefront_visible', true)
            ->whereIn('source_key', $newArrivalKeys)
            ->with('category')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course))
            ->values();

        $banners = \App\Models\CarouselSlide::where('is_active', true)
            ->whereIn('page_key', ['new-arrivals', 'new_arrivals', 'new-arrival'])
            ->orderBy('order')
            ->get();

        return view('polani.new-arrivals', [
            'products' => $products,
            'banners' => $banners,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function deals()
    {
        $dealKeys = config('one_stop_mart.deal_source_keys', []);

        $products = Course::query()
            ->where('is_storefront_visible', true)
            ->whereIn('source_key', $dealKeys)
            ->with('category')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course))
            ->values();

        $banners = \App\Models\CarouselSlide::where('is_active', true)
            ->whereIn('page_key', ['deals', 'deal'])
            ->orderBy('order')
            ->get();

        return view('polani.deals', [
            'products' => $products,
            'banners' => $banners,
            'cartCount' => $this->cartCount(),
        ]);
    }



    public function collection()
    {
        $query = trim((string) request('q', ''));
        $categorySlug = trim((string) request('category', ''));
        $minPrice = request('min_price');
        $maxPrice = request('max_price');
        $sort = request('sort', 'default');

        $products = Course::query()
            ->where('is_storefront_visible', true)
            ->where('product_kind', 'retail') // Filter only visible retail products
            ->with('category')
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', '%' . $query . '%')
                        ->orWhere('slug', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                });
            })
            ->when($categorySlug !== '', function ($builder) use ($categorySlug) {
                $builder->whereHas('category', function ($inner) use ($categorySlug) {
                    $inner->where('slug', $categorySlug);
                });
            })
            ->when(is_numeric($minPrice) && (float)$minPrice >= 0, function ($builder) use ($minPrice) {
                $builder->where('weekly_price', '>=', (float)$minPrice);
            })
            ->when(is_numeric($maxPrice) && (float)$maxPrice >= 0, function ($builder) use ($maxPrice) {
                $builder->where('weekly_price', '<=', (float)$maxPrice);
            })
            ->when($sort === 'price-low', function ($builder) {
                $builder->orderBy('weekly_price', 'asc');
            })
            ->when($sort === 'price-high', function ($builder) {
                $builder->orderBy('weekly_price', 'desc');
            })
            ->when($sort === 'name-az', function ($builder) {
                $builder->orderBy('name', 'asc');
            })
            ->when($sort === 'name-za', function ($builder) {
                $builder->orderBy('name', 'desc');
            })
            ->when($sort === 'newest' || $sort === 'default', function ($builder) {
                $builder->latest('id');
            })
            ->paginate(12)
            ->withQueryString();


        $products->getCollection()->transform(fn (Course $course) => $this->productViewModel($course));

        $categoryCounts = \App\Models\Category::where('is_storefront_category', true)
            ->withCount(['courses' => function ($q) {
                $q->where('is_storefront_visible', true)
                  ->where('product_kind', 'retail');
            }])->get()->pluck('courses_count', 'slug')->toArray();

        $preferredSlugs = config('one_stop_mart.category_display_order', []);
        $allCategories = \App\Models\Category::where('is_storefront_category', true)->get();
        $categories = $allCategories->sortBy(function ($category) use ($preferredSlugs) {
            $index = array_search($category->slug, $preferredSlugs);
            return $index === false ? 999999 : $index;
        })->values();

        return view('polani.collection', [
            'products' => $products,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'cartCount' => $this->cartCount(),
        ]);
    }




    public function women()
    {
        return $this->categoryPage(
            'Fashion',
            'Fashion — One Stop Mart',
            'Discover our premium selection of fashion items.',
            'one-stop-mart/images/cat-fashion.png',
            'center',
            'fashion'
        );
    }

    public function attars()
    {
        return $this->categoryPage(
            'Perfumes',
            'Perfumes — One Stop Mart',
            'Explore our premium collection of fragrances.',
            'one-stop-mart/images/cat-perfumes.png',
            'center',
            'perfumes'
        );
    }

    public function oud()
    {
        return $this->categoryPage(
            'Perfumes',
            'Perfumes — One Stop Mart',
            'Explore our premium collection of fragrances.',
            'one-stop-mart/images/cat-perfumes.png',
            'center',
            'perfumes'
        );
    }

    public function scentedCandles()
    {
        return $this->categoryPage(
            'Home & Living',
            'Home & Living — One Stop Mart',
            'Beautiful decor and home items.',
            'one-stop-mart/images/cat-home.png',
            'center',
            'home-living'
        );
    }


    public function trackOrder(Request $request)
    {
        $order = null;
        $error = null;
        $orderNumberInput = trim((string) $request->query('order_number', ''));
        $emailInput = trim((string) $request->query('email', ''));
        $phoneInput = trim((string) $request->query('phone', ''));
        $userOrders = collect();
        $selectedOrderId = null;

        if (auth()->check()) {
            $userOrders = Order::where('user_id', auth()->id())
                ->latest()
                ->get()
                ->map(function (Order $userOrder) {
                    return $this->buildTrackOrderContext($userOrder);
                })
                ->values();

            if ($orderNumberInput !== '') {
                if (str_contains($orderNumberInput, '-')) {
                    $parts = explode('-', $orderNumberInput);
                    $selectedOrderId = (int) end($parts);
                } else {
                    $selectedOrderId = (int) preg_replace('/\D+/', '', $orderNumberInput);
                }
                $order = $selectedOrderId > 0
                    ? Order::where('user_id', auth()->id())->find($selectedOrderId)
                    : null;

                if (!$order && $userOrders->isNotEmpty()) {
                    $order = Order::where('user_id', auth()->id())->latest()->first();
                }
            } elseif ($userOrders->isNotEmpty()) {
                $order = Order::where('user_id', auth()->id())->latest()->first();
            }
        } elseif ($orderNumberInput !== '') {
            if (str_contains($orderNumberInput, '-')) {
                $parts = explode('-', $orderNumberInput);
                $orderId = (int) end($parts);
            } else {
                $orderId = (int) preg_replace('/\D+/', '', $orderNumberInput);
            }

            if ($orderId > 0) {
                $order = Order::find($orderId);

                if ($order) {
                    $billingAddress = json_decode($order->billing_address ?? '{}', true);
                    if (!is_array($billingAddress)) {
                        $billingAddress = [];
                    }

                    $emailMatches = $emailInput !== '' && strtolower((string) ($billingAddress['email'] ?? '')) === strtolower($emailInput);
                    $phoneMatches = $phoneInput !== '' && preg_replace('/\D+/', '', (string) ($billingAddress['phone'] ?? '')) === preg_replace('/\D+/', '', $phoneInput);
                    $ownsOrder = auth()->check() && (int) $order->user_id === (int) auth()->id();

                    if (!($ownsOrder || $emailMatches || $phoneMatches)) {
                        $order = null;
                        $error = 'Please enter the same email or phone number used at checkout.';
                    }
                }
            }
        }

        if ($orderNumberInput !== '' && !$order) {
            $error = $error ?: 'No order was found for the details you entered.';
        }

        return view('polani.track-order', [
            'cartCount' => $this->cartCount(),
            'order' => $order ? $this->buildTrackOrderContext($order) : null,
            'orders' => $userOrders,
            'error' => $error,
            'isAuthenticated' => auth()->check(),
            'query' => [
                'order_number' => $orderNumberInput,
                'email' => $emailInput,
                'phone' => $phoneInput,
            ],
        ]);
    }

    public function product(string $slug)
    {
        $product = Course::with('category')
            ->where('is_storefront_visible', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('polani.product', [
            'product' => $this->productViewModel($product),
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function blogs()
    {
        $blogs = Blog::where('is_storefront_visible', true)->latest('id')->paginate(12);

        return view('polani.blogs', [
            'blogs' => $blogs,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function blogDetail(string $slug)
    {
        $blog = Blog::where('is_storefront_visible', true)->where('slug', $slug)->firstOrFail();

        return view('polani.blog-detail', [
            'blog' => $blog,
            'cartCount' => $this->cartCount(),
        ]);
    }


    public function about()
    {
        return view('polani.about', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function contact()
    {
        return view('polani.contact', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $storeSettings = \App\Models\FooterSetting::getSettings();
            $recipient = !empty($storeSettings->email) ? $storeSettings->email : config('mail.from.address');

            if (!empty($recipient)) {
                Mail::raw(
                    "New Contact Message Received on One Stop Mart:\n\n" .
                    "From: {$contactMessage->name} ({$contactMessage->email})\n" .
                    "Subject: {$contactMessage->subject}\n\n" .
                    "Message:\n{$contactMessage->message}\n\n" .
                    "Submitted At: {$contactMessage->created_at->format('Y-m-d H:i:s')}",
                    function ($mail) use ($recipient, $contactMessage) {
                        $mail->to($recipient)
                            ->subject("New Customer Inquiry: {$contactMessage->subject}");
                    }
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Contact form email notification failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Thank you for contacting One Stop Mart. We have received your message and will get back to you soon.');
    }

    public function shippingDelivery()
    {
        return view('polani.shipping', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function returnsRefunds()
    {
        return view('polani.returns', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function faq()
    {
        return view('polani.faq', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function termsConditions()
    {
        return view('polani.terms', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function privacyPolicy()
    {
        return view('polani.privacy', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function cart()
    {
        return view('polani.cart', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function checkout()
    {
        return view('polani.checkout', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    private function buildTrackOrderContext(Order $order): array
    {
        $status = $order->status ?: 'pending';
        $statusMap = [
            'pending' => ['Placed', 1, 'Your order has been received and is awaiting confirmation.'],
            'paid' => ['Confirmed', 2, 'Your order is confirmed and is being prepared.'],
            'shipped' => ['Shipped', 3, 'Your order has been shipped and is on the way.'],
            'completed' => ['Delivered', 4, 'Your order has been completed successfully.'],
            'failed' => ['Cancelled', 0, 'Your order was cancelled.'],
            'rejected' => ['Cancelled', 0, 'Your order was rejected.'],
        ];

        [$label, $step, $message] = $statusMap[$status] ?? ['Processing', 2, 'Your order is being processed.'];

        $items = collect(json_decode($order->cart_items, true) ?: [])
            ->map(function (array $item) {
                $productId = $item['product_id'] ?? null;
                $product = $productId ? Product::find($productId) : null;
                if (!$product) {
                    return null;
                }

                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? $product->weekly_price ?? 0);

                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image_path ? asset($product->image_path) : asset('polani/assets/product-noir-elixir.jpg'),
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $price * $quantity,
                ];
            })
            ->filter()
            ->values();

        $billing = json_decode($order->billing_address ?? '{}', true);
        if (!is_array($billing)) {
            $billing = [];
        }

        return [
            'orderNumber' => sprintf('#PF-%s-%04d', now()->format('Y'), $order->id),
            'orderId' => $order->id,
            'status' => $status,
            'statusLabel' => $label,
            'statusStep' => $step,
            'statusStage' => $step > 0 ? "Step {$step} of 4" : 'Step 0 of 4',
            'statusMessage' => $message,
            'orderDate' => optional($order->created_at)->format('F j, Y') ?? now()->format('F j, Y'),
            'deliveryWindow' => $status === 'completed' ? 'Delivered' : '2 - 4 working days',
            'estimatedDelivery' => $status === 'completed' ? 'Delivered' : '2 - 4 working days',
            'total' => (float) ($order->final_total ?? $order->total ?? 0),
            'paymentMethod' => $this->formatPaymentLabel($order->payment_method),
            'address' => trim(implode(', ', array_filter([
                $billing['address'] ?? null,
                $billing['city'] ?? null,
                $billing['country'] ?? null,
            ]))),
            'email' => $billing['email'] ?? null,
            'phone' => $billing['phone'] ?? null,
            'items' => $items,
        ];
    }

    private function formatPaymentLabel(?string $paymentMethod): string
    {
        $method = trim((string) $paymentMethod);

        if ($method === '') {
            return 'Polani Payment';
        }

        $normalized = strtolower($method);

        return match (true) {
            str_contains($normalized, 'cash') => 'Cash on Delivery',
            str_contains($normalized, 'cod') => 'Cash on Delivery',
            str_contains($normalized, 'easypaisa') => 'Easypaisa',
            str_contains($normalized, 'jazzcash') => 'JazzCash',
            str_contains($normalized, 'bank') => 'Bank Transfer',
            str_contains($normalized, 'card') => 'Card Payment',
            str_contains($normalized, 'paypal') => 'PayPal',
            default => ucwords($method),
        };
    }

    public function addToCart(Request $request, string $slug)
    {
        $product = Product::where('is_storefront_visible', true)->where('slug', $slug)->firstOrFail();

        if ($product->is_free) {
            return back()->with('error', 'This product is free and cannot be added to cart.');
        }

        $price = (float) ($product->weekly_price ?? 0);

        if (auth()->check()) {
            $existing = Shoppingcart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($existing) {
                $existing->update([
                    'price' => $price,
                    'price_type' => null,
                    'quantity' => $existing->quantity + 1,
                ]);
            } else {
                Shoppingcart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'price' => $price,
                    'price_type' => null,
                    'quantity' => 1,
                ]);
            }
        } else {
            $cart = $this->sessionCart();
            $existingIndex = collect($cart)->search(fn ($item) => (int) ($item['product_id'] ?? 0) === (int) $product->id);

            if ($existingIndex !== false) {
                $currentQty = max(1, (int) ($cart[$existingIndex]['quantity'] ?? 1));
                $cart[$existingIndex]['quantity'] = $currentQty + 1;
            } else {
                $cart[] = [
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'type' => 'Product',
                    'price' => $price,
                    'quantity' => 1,
                    'image_path' => $product->image_path,
                ];
            }

            $this->saveSessionCart($cart);
        }

        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Added to cart.',
                'cartCount' => $this->cartCount(),
            ]);
        }

        return back()->with('success', 'Added to cart.');
    }
}
