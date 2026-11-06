<div class="osm-cart-livewire" data-cart-count="{{ $cartitems->count() }}">
  @if(session()->has('error'))
    <div class="osm-alert osm-alert--danger" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
      {{ session('error') }}
    </div>
  @endif
  @if(session()->has('success'))
    <div class="osm-alert osm-alert--success" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
      {{ session('success') }}
    </div>
  @endif
  
  @if($cartitems->count() > 0)
    <h1 class="osm-cart-heading">
      {{ $cartitems->count() }} {{ $cartitems->count() === 1 ? 'product' : 'products' }} in your cart
    </h1>

    <div class="osm-cart-layout">
      
      <!-- Left Column: Items Panel -->
      <div class="osm-cart-layout__left">
        <div class="osm-cart-items-panel">
          
          <!-- Desktop table header (CSS hidden on mobile) -->
          <div class="osm-cart-items-header" aria-hidden="true">
            <div>Product</div>
            <div>Price</div>
            <div>Quantity</div>
            <div>Subtotal</div>
            <div>Action</div>
          </div>

          <!-- Items list -->
          <div class="osm-cart-items">
            @foreach($cartitems as $cartitem)
              @php
                $course = $cartitem->course ?? null;
                $lecture = $cartitem->lecture ?? null;

                $name = !empty($course)
                    ? ($course->name ?? 'Item')
                    : (!empty($lecture) ? ($lecture->name ?? 'Item') : 'Item');

                $slug = !empty($course) && !empty($course->slug)
                    ? $course->slug
                    : null;

                $imagePath = !empty($course) && !empty($course->image_path)
                    ? $course->image_path
                    : (!empty($lecture) && !empty($lecture->image_path)
                        ? $lecture->image_path
                        : null);

                $imageUrl = !empty($imagePath)
                    ? asset($imagePath)
                    : asset('one-stop-mart/images/product-teddy.png');

                $unitPrice = (float) ($cartitem->price ?? 0);
                $quantity = (int) ($cartitem->quantity ?? 0);
                $lineTotal = $unitPrice * $quantity;
              @endphp

              <article 
                class="osm-cart-item" 
                wire:key="cart-item-{{ $cartitem->id }}"
              >
                
                <!-- Product Column -->
                <div class="osm-cart-product">
                  @if(!empty($slug))
                    <a href="{{ route('polani.product', ['slug' => $slug]) }}">
                      <img src="{{ $imageUrl }}" alt="{{ $name }}" class="osm-cart-image" loading="lazy">
                    </a>
                  @else
                    <img src="{{ $imageUrl }}" alt="{{ $name }}" class="osm-cart-image" loading="lazy">
                  @endif

                    <div class="osm-cart-product-details">
                      <div class="osm-cart-product-name">
                        @if(!empty($slug) && ($cartitem->is_available ?? true))
                          <a href="{{ route('polani.product', ['slug' => $slug]) }}">{{ $name }}</a>
                        @else
                          <span>{{ $name }}</span>
                        @endif

                        @if(!($cartitem->is_available ?? true))
                          <div class="osm-cart-error-text" style="color: #dc3545; font-size: 0.85rem; font-weight: bold; margin-top: 4px;">
                            This item is no longer available.
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>

                <!-- Unit Price Column -->
                <div class="osm-cart-price">
                  @if($cartitem->is_available ?? true)
                    Rs. {{ number_format($unitPrice, 2) }}
                  @else
                    —
                  @endif
                </div>

                <!-- Quantity Controls Column -->
                <div class="osm-cart-quantity">
                  <div class="osm-cart-quantity-control">
                    <button
                      type="button"
                      class="osm-cart-quantity-button"
                      aria-label="Decrease quantity of {{ $name }}"
                      wire:click="decrementQuantity({{ $cartitem->id }})"
                      wire:loading.attr="disabled"
                      wire:target="decrementQuantity({{ $cartitem->id }})"
                      @disabled($quantity <= 1 || !($cartitem->is_available ?? true))
                    >
                      −
                    </button>

                    <span class="osm-cart-quantity-value" aria-live="polite">
                      {{ $quantity }}
                    </span>

                    <button
                      type="button"
                      class="osm-cart-quantity-button"
                      aria-label="Increase quantity of {{ $name }}"
                      wire:click="incrementQuantity({{ $cartitem->id }})"
                      wire:loading.attr="disabled"
                      wire:target="incrementQuantity({{ $cartitem->id }})"
                      @disabled(!($cartitem->is_available ?? true))
                    >

                      +
                    </button>
                  </div>
                </div>

                <!-- Line Total Column -->
                <div class="osm-cart-line-total">
                  @if($cartitem->is_available ?? true)
                    Rs. {{ number_format($lineTotal, 2) }}
                  @else
                    —
                  @endif
                </div>


                <!-- Remove Button Column -->
                <div class="osm-cart-remove">
                  <button
                    type="button"
                    class="osm-cart-remove-button"
                    aria-label="Remove {{ $name }} from cart"
                    wire:click="removeFromCart({{ $cartitem->id }})"
                    wire:loading.attr="disabled"
                    wire:target="removeFromCart({{ $cartitem->id }})"
                  >
                    Remove
                  </button>
                </div>

              </article>
            @endforeach
          </div>

          <!-- Continue Shopping Action -->
          <div class="osm-cart-continue">
            <a href="{{ route('polani.collection') }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
              Continue Shopping
            </a>
          </div>

        </div>
      </div>

      <!-- Right Column: Order Summary Card -->
      <div class="osm-cart-layout__right">
        <div class="osm-cart-summary">
          <h2 class="osm-cart-summary__title">Order Summary</h2>
          
          <div class="osm-cart-summary-row osm-cart-summary-total">
            <span>Subtotal</span>
            <strong>Rs. {{ number_format((float) $totalAmount, 2) }}</strong>
          </div>

          <div class="osm-cart-summary-note">
            Shipping and any applicable final charges are confirmed during checkout.
          </div>

          <button
            type="button"
            class="osm-cart-checkout-button"
            wire:click="checkout"
            wire:loading.attr="disabled"
            wire:target="checkout"
          >
            <span wire:loading.remove wire:target="checkout">Proceed to Checkout</span>
            <span wire:loading wire:target="checkout">Processing…</span>
          </button>
        </div>
      </div>

    </div>
  @else
    <!-- Empty Cart Screen -->
    <div class="osm-cart-empty">
      <div class="osm-cart-empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
      </div>
      <h2 class="osm-cart-empty-title">Your Cart is Empty</h2>
      <p class="osm-cart-empty-text">Review your collection and add products to start shopping.</p>
      <a href="{{ route('polani.collection') }}" class="btn btn--primary">Continue Shopping</a>
    </div>
  @endif

</div>
