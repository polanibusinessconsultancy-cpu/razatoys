@props(['product'])

@php
  $slug = is_array($product) ? ($product['slug'] ?? '') : ($product->slug ?? '');
  $name = is_array($product) ? ($product['name'] ?? 'Toy Product') : ($product->name ?? 'Toy Product');
  $category = is_array($product) 
      ? ($product['category_name'] ?? ($product['category']->name ?? 'Toys')) 
      : ($product->category->name ?? 'Toys');
  $price = is_array($product) 
      ? (float)($product['price'] ?? $product['monthly_price'] ?? 0) 
      : (float)($product->price ?: $product->monthly_price ?: 0);
  $oldPrice = !empty($product['old_price']) ? (float)$product['old_price'] : 0;
  $rating = (float)($product['rating'] ?? 5.0);
  $reviews = (int)($product['reviews'] ?? ($product['rating_count'] ?? 0));
  
  $img = is_array($product)
      ? (!empty($product['image_url']) ? $product['image_url'] : (!empty($product['image']) ? $product['image'] : asset('one-stop-mart/images/cat-toys.webp')))
      : ($product->image_url ?: asset('one-stop-mart/images/cat-toys.webp'));

  if (str_contains($img, '.png') && !str_starts_with($img, 'http')) {
      $webpCandidate = str_replace('.png', '.webp', $img);
      if (file_exists(public_path(ltrim(parse_url($webpCandidate, PHP_URL_PATH), '/')))) {
          $img = $webpCandidate;
      }
  }

  $hasDiscount = $oldPrice > $price;
  $discountPercent = $hasDiscount ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;
@endphp

<article class="raza-card" id="raza-product-{{ $product['db_id'] ?? $slug }}" style="background: var(--raza-white); border: 1.5px solid var(--raza-border); border-radius: var(--raza-radius-lg); overflow: hidden; display: flex; flex-direction: column; transition: var(--raza-bounce); position: relative;">
  
  <!-- Card Media Wrapper -->
  <div style="position: relative; background: var(--raza-cream); padding: 20px; display: flex; align-items: center; justify-content: center; min-height: 220px; overflow: hidden;">
    
    <!-- Discount Ribbon -->
    @if($hasDiscount)
      <span class="raza-badge raza-badge--pink" style="position: absolute; top: 12px; left: 12px; z-index: 2; box-shadow: 0 2px 8px rgba(243, 91, 155, 0.25);">
        {{ $discountPercent }}% OFF
      </span>
    @endif

    <!-- Wishlist Button -->
    <button
      type="button"
      aria-label="Add {{ $name }} to Wishlist"
      style="position: absolute; top: 12px; right: 12px; width: 34px; height: 34px; border-radius: var(--raza-radius-full); background: var(--raza-white); border: 1.5px solid var(--raza-border); display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; transition: var(--raza-bounce);"
      onmouseover="this.style.transform='scale(1.15)'; this.style.borderColor='var(--raza-pink)';"
      onmouseout="this.style.transform='scale(1)'; this.style.borderColor='var(--raza-border)';"
    >
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" color="var(--raza-pink)">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
      </svg>
    </button>

    <!-- Product Image -->
    <a href="{{ !empty($slug) ? route('polani.product', ['slug' => $slug]) : '#' }}" style="display: block; width: 100%; height: 100%;">
      <img
        src="{{ $img }}"
        alt="{{ $name }}"
        width="220"
        height="220"
        loading="lazy"
        decoding="async"
        style="width: 100%; height: 180px; object-fit: contain; transition: transform 0.3s ease;"
        onmouseover="this.style.transform='scale(1.08)';"
        onmouseout="this.style.transform='scale(1)';"
      >
    </a>
  </div>

  <!-- Card Body Content -->
  <div style="padding: 18px; display: flex; flex-direction: column; flex: 1;">
    
    <!-- Category Tag -->
    <span style="font-size: 0.76rem; font-weight: 700; color: var(--raza-blue-dark); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
      {{ $category }}
    </span>

    <!-- Product Title -->
    <h3 style="font-family: var(--raza-font-heading); font-size: 1.02rem; font-weight: 800; color: var(--raza-black); margin: 0 0 8px 0; line-height: 1.35; min-height: 2.7em;">
      <a href="{{ !empty($slug) ? route('polani.product', ['slug' => $slug]) : '#' }}" style="color: inherit;">
        {{ $name }}
      </a>
    </h3>

    <!-- Star Ratings -->
    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
      <div style="display: flex; color: var(--raza-yellow-dark);">
        @for($i = 1; $i <= 5; $i++)
          <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        @endfor
      </div>
      <span style="font-size: 0.78rem; font-weight: 700; color: var(--raza-muted);">
        ({{ $reviews > 0 ? $reviews : 12 }})
      </span>
    </div>

    <!-- Pricing Row -->
    <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 16px; margin-top: auto;">
      <span style="font-family: var(--raza-font-heading); font-size: 1.25rem; font-weight: 800; color: var(--raza-black);">
        Rs. {{ number_format($price) }}
      </span>
      @if($hasDiscount)
        <span style="font-size: 0.85rem; color: var(--raza-muted); text-decoration: line-through;">
          Rs. {{ number_format($oldPrice) }}
        </span>
      @endif
    </div>

    <!-- Add to Cart Action -->
    @if(!empty($slug))
      <button
        type="button"
        class="raza-btn raza-btn--primary"
        style="width: 100%; padding: 10px 16px; font-size: 0.88rem;"
        data-add-to-cart
        data-add-url="{{ route('polani.cart.add', ['slug' => $slug]) }}"
        aria-label="Add {{ $name }} to Cart"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="16" height="16">
          <circle cx="9" cy="21" r="1"/>
          <circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        Add to Cart
      </button>
    @endif

  </div>
</article>
