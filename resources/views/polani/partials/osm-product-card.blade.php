<div class="osm-home-product-card" id="product-{{ $product['db_id'] ?? '' }}">
    <div class="osm-home-product-card__image-wrapper">
        <button class="osm-home-product-card__wishlist" aria-label="Add to wishlist" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
        <a href="{{ !empty($product['slug']) ? route('polani.product', ['slug' => $product['slug']]) : '#' }}">
            @php
                $imgRaw = !empty($product['image']) ? $product['image'] : asset('one-stop-mart/images/product-teddy.webp');
                if (str_contains($imgRaw, '.png') && !str_starts_with($imgRaw, 'http')) {
                    $webpCandidate = str_replace('.png', '.webp', $imgRaw);
                    if (file_exists(public_path(ltrim(parse_url($webpCandidate, PHP_URL_PATH), '/')))) {
                        $imgRaw = $webpCandidate;
                    }
                }
            @endphp
            <img src="{{ $imgRaw }}" alt="{{ $product['name'] ?? 'Product Image' }}" class="osm-home-product-card__image" width="220" height="220" loading="lazy" decoding="async" style="object-fit: contain;">
        </a>
    </div>
    <div class="osm-home-product-card__info">
        @if(!empty($product['category_name']))
            <div class="osm-home-product-card__category">{{ $product['category_name'] }}</div>
        @endif
        
        <h3 class="osm-home-product-card__name">
            <a href="{{ !empty($product['slug']) ? route('polani.product', ['slug' => $product['slug']]) : '#' }}">{{ $product['name'] ?? 'Unnamed Product' }}</a>
        </h3>
        
        <div class="osm-home-product-card__price-container" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
            @if(isset($product['price']))
                <span class="osm-home-product-card__price" style="font-weight: 800; color: var(--color-secondary);">Rs. {{ number_format($product['price']) }}</span>
            @endif
            @if(!empty($product['old_price']) && (float)$product['old_price'] > (float)$product['price'])
                <span class="osm-home-product-card__old-price" style="text-decoration: line-through; color: var(--color-text-muted); font-size: 0.85rem;">Rs. {{ number_format($product['old_price']) }}</span>
                @php
                    $discountPercent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                @endphp
                <span class="osm-home-product-card__discount-badge" style="background: #dc3545; color: #fff; font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; font-weight: bold; margin-left: auto;">{{ $discountPercent }}% OFF</span>
            @endif
        </div>

        
        @if(!empty($product['rating']) && (float)$product['rating'] > 0)
        <div class="osm-home-product-card__rating">
            <div class="osm-home-product-card__stars">
                @php
                    $ratingVal = (float) $product['rating'];
                    $fullStars = (int) round($ratingVal);
                @endphp
                @for($i = 1; $i <= 5; $i++)
                    <svg viewBox="0 0 24 24" width="14" height="14">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="{{ $i <= $fullStars ? '#FFC107' : '#e0e0e0' }}" stroke="{{ $i <= $fullStars ? '#FFC107' : '#e0e0e0' }}"/>
                    </svg>
                @endfor
            </div>
            @if(!empty($product['reviews']) && (int)$product['reviews'] > 0)
                <span class="osm-home-product-card__reviews">({{ (int)$product['reviews'] }})</span>
            @endif
        </div>
        @endif

        @if(!empty($product['slug']))
            <button 
                type="button" 
                class="osm-home-product-card__add-to-cart" 
                data-add-to-cart 
                data-add-url="{{ route('polani.cart.add', ['slug' => $product['slug']]) }}"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="margin-right: 6px;">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Add to Cart
            </button>
        @endif
    </div>
</div>
