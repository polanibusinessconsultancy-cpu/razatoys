<div class="osm-checkout-livewire">
    @if(!auth()->check())
        <!-- Guest Sign-in Card -->
        <div class="osm-checkout-guest" role="alert">
            <div class="osm-checkout-guest-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="osm-checkout-guest-content">
                <h3 class="osm-checkout-guest-title">Authentication Required</h3>
                <p class="osm-checkout-guest-text">To place an order, please sign in first — this applies to both COD and online payments.</p>
                <div class="osm-checkout-auth-actions">
                    <a href="{{ route('sign-in') }}" class="btn btn--primary">Sign In</a>
                    <a href="{{ route('sign-up') }}" class="btn btn--ghost btn--dark">Sign Up</a>
                </div>
            </div>
        </div>
    @else
        <!-- Authenticated checkout form -->
        <form class="osm-checkout-form" wire:submit.prevent="createOrder">
            <div class="osm-checkout-layout">
                <!-- Left Column: Form Sections -->
                <div class="osm-checkout-main">
                    <!-- Customer Information -->
                    <div class="osm-checkout-section">
                        <h2 class="osm-checkout-section-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Customer Information
                        </h2>
                        
                        <div class="osm-checkout-field-grid">
                            <div class="osm-checkout-field">
                                <label for="first-name" class="osm-checkout-label">First Name <span class="req">*</span></label>
                                <input type="text" id="first-name" name="first_name" class="osm-checkout-input @error('firstName') input--error @enderror" placeholder="e.g. John" autocomplete="given-name" wire:model.defer="firstName" required>
                                @error('firstName')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="osm-checkout-field">
                                <label for="last-name" class="osm-checkout-label">Last Name <span class="req">*</span></label>
                                <input type="text" id="last-name" name="last_name" class="osm-checkout-input @error('lastName') input--error @enderror" placeholder="e.g. Smith" autocomplete="family-name" wire:model.defer="lastName" required>
                                @error('lastName')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="osm-checkout-field-grid">
                            <div class="osm-checkout-field">
                                <label for="email" class="osm-checkout-label">Email Address <span class="req">*</span></label>
                                <input type="email" id="email" name="email" class="osm-checkout-input @error('email') input--error @enderror" placeholder="you@example.com" autocomplete="email" wire:model.defer="email" required>
                                @error('email')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="osm-checkout-field">
                                <label for="phone" class="osm-checkout-label">Phone Number <span class="req">*</span></label>
                                <input type="tel" id="phone" name="phone" class="osm-checkout-input @error('phone') input--error @enderror" placeholder="+92 (324) 920-6345" autocomplete="tel" wire:model.defer="phone" required>
                                @error('phone')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="osm-checkout-section">
                        <h2 class="osm-checkout-section-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Delivery Address
                        </h2>

                        <div class="osm-checkout-field-grid one-col">
                            <div class="osm-checkout-field">
                                <label for="address" class="osm-checkout-label">Street Address <span class="req">*</span></label>
                                <input type="text" id="address" name="address" class="osm-checkout-input @error('address') input--error @enderror" placeholder="e.g. 123 Main Street, Apt 4B" autocomplete="address-line1" wire:model.defer="address" required>
                                @error('address')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="osm-checkout-field-grid three-col">
                            <div class="osm-checkout-field">
                                <label for="city" class="osm-checkout-label">City <span class="req">*</span></label>
                                <input type="text" id="city" name="city" class="osm-checkout-input @error('city') input--error @enderror" placeholder="e.g. Karachi" autocomplete="address-level2" wire:model.defer="city" required>
                                @error('city')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="osm-checkout-field">
                                <label for="state" class="osm-checkout-label">State / Province <span class="req">*</span></label>
                                <input type="text" id="state" name="state" class="osm-checkout-input @error('state') input--error @enderror" placeholder="e.g. Sindh" autocomplete="address-level1" wire:model.defer="state" required>
                                @error('state')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="osm-checkout-field">
                                <label for="postal-code" class="osm-checkout-label">ZIP / Postal Code <span class="req">*</span></label>
                                <input type="text" id="postal-code" name="postal_code" class="osm-checkout-input @error('postalCode') input--error @enderror" placeholder="e.g. 74200" autocomplete="postal-code" wire:model.defer="postalCode" required>
                                @error('postalCode')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="osm-checkout-field-grid one-col">
                            <div class="osm-checkout-field">
                                <label for="country" class="osm-checkout-label">Country <span class="req">*</span></label>
                                <input type="text" id="country" name="country" class="osm-checkout-input @error('country') input--error @enderror" placeholder="e.g. Pakistan" autocomplete="country" wire:model.defer="country" required>
                                @error('country')
                                    <p class="osm-checkout-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="osm-checkout-section">
                        <h2 class="osm-checkout-section-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Payment Method
                        </h2>

                        @if ($paymentMethod === 'free')
                            <div class="osm-checkout-free-order" role="alert">
                                <strong>Free Order</strong>
                                <span>No payment is required after the applied coupon.</span>
                            </div>
                        @else
                            <div class="osm-checkout-payment-list">
                                @foreach($paymentMethods as $method)
                                    <label class="osm-checkout-payment-option {{ $paymentMethod === $method->key ? 'selected' : '' }}">
                                        <input type="radio" value="{{ $method->key }}" wire:model="paymentMethod">
                                        <div class="osm-checkout-payment-content">
                                            <strong>{{ $method->name }}</strong>
                                            @if(!empty($method->description))
                                                <span>{{ $method->description }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('paymentMethod')
                                <p class="osm-checkout-error" style="margin-top:10px;">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <!-- Right Column: Sidebar -->
                <aside class="osm-checkout-sidebar">
                    <div class="order-summary-card">
                        <div class="order-summary__header">
                            <span class="order-summary__title">Order Summary</span>
                            <span class="order-summary__count">{{ count($cartItems) }}</span>
                        </div>

                        <!-- Items List -->
                        <div class="osm-checkout-items">
                            @foreach ($cartItems as $item)
                                <div class="osm-checkout-item">
                                    <div class="osm-checkout-item-info">
                                        <span class="osm-checkout-item-name">{{ $item['name'] }}</span>
                                        <span class="osm-checkout-item-type">{{ $item['type'] }}</span>
                                    </div>
                                    <div class="osm-checkout-item-quantity">Qty: {{ $item['quantity'] }}</div>
                                    <div class="osm-checkout-item-total">Rs {{ number_format((float)$item['line_total'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coupon Code -->
                        <div class="osm-checkout-coupon">
                            <p class="osm-checkout-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Promo / Coupon Code
                            </p>
                            <div class="osm-checkout-coupon-row">
                                <input type="text" placeholder="Enter coupon code" wire:model.defer="couponCode">
                                <button type="button" wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon">
                                    <span wire:loading.remove wire:target="applyCoupon">Apply</span>
                                    <span wire:loading wire:target="applyCoupon">Applying...</span>
                                </button>
                            </div>
                            @if($couponError)
                                <p class="osm-checkout-error" style="margin-top: 8px;">{{ $couponError }}</p>
                            @endif
                            @if($couponSuccess)
                                <p class="osm-checkout-success" style="margin-top: 8px;">{{ $couponSuccess }}</p>
                                <div class="osm-checkout-coupon-applied-badge">
                                    <button type="button" wire:click="removeCoupon" wire:loading.attr="disabled" wire:target="removeCoupon">
                                        <span wire:loading.remove wire:target="removeCoupon">&times; Remove Coupon</span>
                                        <span wire:loading wire:target="removeCoupon">Removing...</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Monetary Totals -->
                        <div class="osm-checkout-totals">
                            <div class="osm-checkout-summary-row">
                                <span class="total-label">Subtotal</span>
                                <span class="total-value">Rs {{ number_format((float)$total, 2) }}</span>
                            </div>
                            @if ($discount > 0)
                                <div class="osm-checkout-summary-row discount">
                                    <span class="total-label">Coupon Discount</span>
                                    <span class="total-value">-Rs {{ number_format((float)$discount, 2) }}</span>
                                </div>
                            @endif
                            <hr class="total-divider">
                            <div class="osm-checkout-summary-row grand-total">
                                <span class="total-label">Total Payable</span>
                                <span class="total-value">Rs {{ number_format(max(0.0, (float)$total - (float)$discount), 2) }}</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div style="padding: 16px 20px 20px;">
                            <button type="submit" class="osm-checkout-submit" wire:loading.attr="disabled" wire:target="createOrder" @disabled($isProcessing)>
                                <span wire:loading.remove wire:target="createOrder">
                                    {{ $paymentMethod === 'free' ? 'Complete Free Order' : 'Place Order' }}
                                </span>
                                <span wire:loading wire:target="createOrder">
                                    Processing…
                                </span>
                            </button>
                            <p class="osm-checkout-secure-note">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Your information is safe and encrypted
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    @endif
</div>
