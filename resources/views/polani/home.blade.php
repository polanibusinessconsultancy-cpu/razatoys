@extends('polani.layout')

@section('title', 'One Stop Mart - Everything You Need, All in One Place')
@section('meta_description', 'Explore quality products across toys, kitchenware, perfumes, household essentials, and more.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/home.min.css') }}">
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/page-heroes.min.css') }}">
@endpush

@section('content')

  <!-- ============ HERO SECTION ============ -->
  @include('polani.partials.page-hero', [
    'pageKey' => 'home',
    'banners' => $banners ?? null,
    'defaultEyebrow' => 'WELCOME TO ONE STOP MART',
    'defaultTitle' => 'Everything You Need, All in One Place',
    'defaultSubtitle' => 'Explore quality products across toys, kitchenware, perfumes, household essentials, and more.',
    'defaultCtaText' => 'Shop Now',
    'defaultCtaUrl' => route('polani.collection'),
    'defaultImage' => asset('one-stop-mart/images/hero-banner.webp'),
    'defaultWatermark' => 'ONE STOP MART'
  ])
    
    <!-- Hero Features Strip -->
    <div class="osm-home-hero__features">
      <div class="container osm-home-hero__features-inner">
        <div class="osm-home-hero__feature">
          <div class="osm-home-hero__feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <div class="osm-home-hero__feature-text">
            <strong>Free Delivery</strong>
            <span>On orders above Rs. 10,000</span>
          </div>
        </div>
        <div class="osm-home-hero__feature">
          <div class="osm-home-hero__feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
          </div>
          <div class="osm-home-hero__feature-text">
            <strong>Easy Returns</strong>
            <span>Within 7 days</span>
          </div>
        </div>
        <div class="osm-home-hero__feature">
          <div class="osm-home-hero__feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          </div>
          <div class="osm-home-hero__feature-text">
            <strong>100% Secure</strong>
            <span>Checkout Payments</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SHOP BY CATEGORY ============ -->
  <section class="osm-home-categories" id="categories-section">
    <div class="container">
      <div class="osm-home-section-header">
        <h2 class="osm-home-section-title">Shop by Category</h2>
        <a href="{{ route('polani.collection') }}" class="osm-home-section-link">
          View All Categories
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="osm-home-categories__grid">
        @php
          $categoryMetadata = [
              'toys' => ['image' => 'one-stop-mart/images/cat-toys.webp'],
              'kitchenware' => ['image' => 'one-stop-mart/images/cat-kitchenware.webp'],
              'perfumes' => ['image' => 'one-stop-mart/images/cat-perfumes.webp'],
              'electronics' => ['image' => 'one-stop-mart/images/cat-electronics.webp'],
              'fashion' => ['image' => 'one-stop-mart/images/cat-fashion.webp'],
              'home-living' => ['image' => 'one-stop-mart/images/cat-home.webp'],
              'sports' => ['image' => 'one-stop-mart/images/cat-sports.webp'],
              'men' => ['image' => 'one-stop-mart/images/cat-fashion.webp'],
              'women' => ['image' => 'one-stop-mart/images/cat-fashion.webp'],
              'attars' => ['image' => 'one-stop-mart/images/cat-perfumes.webp'],
              'oud' => ['image' => 'one-stop-mart/images/cat-perfumes.webp'],
              'signature' => ['image' => 'one-stop-mart/images/cat-perfumes.webp'],
              'scented-candles' => ['image' => 'one-stop-mart/images/cat-home.webp'],
          ];
        @endphp
        @foreach($categories as $category)
          @php
            $relImg = $categoryMetadata[$category->slug]['image'] ?? 'one-stop-mart/images/category-placeholder.webp';
            if (!file_exists(public_path($relImg))) {
                $relImg = 'one-stop-mart/images/category-placeholder.webp';
            }
            $itemCount = $category->products_count ?? $category->courses_count ?? 0;
          @endphp
          <a href="{{ route('polani.collection') }}?category={{ $category->slug }}" class="osm-home-category-card">
            <div class="osm-home-category-card__image">
              <img src="{{ asset($relImg) }}" alt="{{ $category->name }}" width="120" height="120" loading="lazy" decoding="async">
            </div>
            <h3 class="osm-home-category-card__name">{{ $category->name }}</h3>
            <span class="osm-home-category-card__count">{{ $itemCount }} {{ $itemCount == 1 ? 'Item' : 'Items' }}</span>
          </a>
        @endforeach
        
        <a href="{{ route('polani.collection') }}" class="osm-home-category-card osm-home-category-card--more">
          <div class="osm-home-category-card__image osm-home-category-card__image--more">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="30" height="30"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          </div>
          <h3 class="osm-home-category-card__name">More Categories</h3>
          <span class="osm-home-category-card__count">View all</span>
        </a>
      </div>
    </div>
  </section>


  <!-- ============ PROMOTIONAL BANNERS ============ -->
  <section class="osm-home-promos" id="promos-section">
    <div class="container osm-home-promos__grid">
      <div class="osm-home-promo-card osm-home-promo-card--deal" id="promo-deal">
        <div class="osm-home-promo-card__content">
          <h3 class="osm-home-promo-card__title">Deal of the Day</h3>
          <span class="osm-home-promo-card__subtitle">Special Offer</span>
          <p class="osm-home-promo-card__text">Big savings on top quality products – Today Only!</p>
          <a href="{{ route('polani.deals') }}" class="osm-home-promo-card__link">
            Shop Now
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
        <div class="osm-home-promo-card__image">
          <span class="osm-home-promo-card__badge">Hot</span>
          <img src="{{ asset('one-stop-mart/images/promo-deal.webp') }}" alt="Deal of the Day" width="340" height="220" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="osm-home-promo-card osm-home-promo-card--arrivals" id="promo-arrivals">
        <div class="osm-home-promo-card__content">
          <h3 class="osm-home-promo-card__title osm-home-promo-card__title--red">New Arrivals</h3>
          <p class="osm-home-promo-card__text">Fresh finds crafted just for you</p>
          <a href="{{ route('polani.new-arrivals') }}" class="osm-home-promo-card__link osm-home-promo-card__link--red">
            Explore Now
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
        <div class="osm-home-promo-card__image">
          <img src="{{ asset('one-stop-mart/images/promo-arrivals.webp') }}" alt="New Arrivals" width="340" height="220" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="osm-home-promo-card osm-home-promo-card--bestsellers" id="promo-bestsellers">
        <div class="osm-home-promo-card__content">
          <h3 class="osm-home-promo-card__title">Best Sellers</h3>
          <p class="osm-home-promo-card__text">Most loved by our valued customers</p>
          <a href="{{ route('polani.collection') }}" class="osm-home-promo-card__link">
            Shop Now
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
        <div class="osm-home-promo-card__image">
          <img src="{{ asset('one-stop-mart/images/promo-bestsellers.webp') }}" alt="Best Sellers" width="340" height="220" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </section>


  <!-- ============ BEST SELLING PRODUCTS ============ -->
  @if(isset($products) && $products->isNotEmpty())
    <section class="osm-home-products" id="products-section">
      <div class="container">
        <div class="osm-home-section-header">
          <h2 class="osm-home-section-title">Best Selling Products</h2>
          <a href="{{ route('polani.collection') }}" class="osm-home-section-link">
            View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
        <div class="osm-home-products__slider-wrapper">
          <button class="osm-home-products__nav osm-home-products__nav--prev" id="osm-home-products-prev" aria-label="Previous products" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <div class="osm-home-products__slider" id="osm-home-products-slider">
            @foreach($products as $product)
              @include('polani.partials.osm-product-card', ['product' => $product])
            @endforeach
          </div>
          <button class="osm-home-products__nav osm-home-products__nav--next" id="osm-home-products-next" aria-label="Next products" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 12 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
    </section>
  @endif

  <!-- ============ SPOTLIGHT FEATURED PRODUCT (CATEGORY NEUTRAL) ============ -->
  @if(isset($signatureProduct))
    <section class="section" id="signature" style="padding: 60px 0; background-color: var(--color-white); border-top: 1px solid var(--color-border);">
      <div class="container" style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px; display: flex; justify-content: center; background-color: var(--color-bg-light); border-radius: var(--border-radius-xl); padding: 30px;">
          <img
            src="{{ !empty($signatureProduct['image']) ? (str_contains($signatureProduct['image'], '.png') ? str_replace('.png', '.webp', $signatureProduct['image']) : $signatureProduct['image']) : asset('one-stop-mart/images/product-teddy.webp') }}"
            alt="{{ $signatureProduct['name'] ?? 'Featured Product' }}"
            width="400"
            height="400"
            style="max-width: 100%; max-height: 400px; object-fit: contain;"
            loading="lazy"
            decoding="async"
          />
        </div>
        <div style="flex: 1.2; min-width: 300px;">
          <div style="color: var(--color-primary-dark); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">FEATURED PRODUCT</div>
          <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--color-secondary); margin: 0 0 16px; line-height: 1.2;">{{ $signatureProduct['name'] ?? 'Our Featured Choice' }}</h2>
          @if(!empty($signatureProduct['category_name']))
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; margin-bottom: 12px;">Category: {{ $signatureProduct['category_name'] }}</div>
          @endif
          <p style="font-size: 1rem; line-height: 1.7; color: var(--color-text-light); margin-bottom: 24px;">
            {{ $signatureProduct['description'] ?? 'A premium selection chosen just for you.' }}
          </p>
          
          @if(isset($signatureProduct['price']))
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--color-secondary); margin-bottom: 24px;">Rs. {{ number_format($signatureProduct['price']) }}</div>
          @endif

          @if(!empty($signatureProduct['slug']))
            <div style="margin-bottom: 30px;">
              <a class="osm-home-hero__cta" href="{{ route('polani.product', ['slug' => $signatureProduct['slug']]) }}">
                Explore Product Details
              </a>
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif

  <!-- ============ STORY BANNER SECTION ============ -->
  <section class="osm-home-story-banner" id="story">
    <div 
      class="osm-home-story-banner__bg"
      style="background: linear-gradient(135deg, #1a1c23 0%, #0d0f12 100%);"
    ></div>
    <div class="osm-home-story-banner__overlay" aria-hidden="true"></div>
    <div class="container">
      <div class="osm-home-story-banner__content">
        <h2 class="osm-home-story-banner__title">
          How One Stop Mart<br />Was Started
        </h2>
        <p class="osm-home-story-banner__text">
          A journey born from a commitment to bring convenience, quality, and values to every home. Handcrafted with passion, delivered with integrity... from a small workshop on M.A Jinnah Road, Karachi.
        </p>
        <div>
          <a class="osm-home-story-banner__btn" href="{{ route('polani.about') }}">
            Read Our Story
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ LATEST INSIGHTS BLOGS SECTION ============ -->
  @if(isset($blogs) && $blogs->isNotEmpty())
    <section class="osm-home-blogs" id="blogs-section">
      <div class="container">
        <div class="osm-home-section-header" style="margin-bottom: 40px;">
          <div>
            <div style="color: var(--color-primary-dark); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">LATEST INSIGHTS</div>
            <h2 class="osm-home-section-title" style="margin: 0;">Our Blogs</h2>
          </div>
          <a href="{{ route('polani.blogs') }}" class="osm-home-section-link">
            View All Blogs
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>

        <div class="osm-home-blogs__grid">
          @foreach($blogs as $blog)
            @php
              $blogImg = $blog->image_path ? asset($blog->image_path) : asset('polani/assets/story-packaging.svg');
            @endphp
            <article class="osm-home-blog-card">
              <div class="osm-home-blog-card__image-wrapper">
                <img src="{{ $blogImg }}" alt="{{ $blog->title }}" class="osm-home-blog-card__image" width="380" height="220" loading="lazy" decoding="async">
                <div class="osm-home-blog-card__date">
                  {{ $blog->created_at->format('M d, Y') }}
                </div>
              </div>
              <div class="osm-home-blog-card__content">
                <h3 class="osm-home-blog-card__title">
                  {{ $blog->title }}
                </h3>
                <p class="osm-home-blog-card__excerpt">
                  {{ Str::limit(strip_tags(htmlspecialchars_decode($blog->content)), 110) }}
                </p>
                <a href="{{ route('polani.blog.detail', $blog->slug) }}" class="osm-home-blog-card__link">
                  Read Article
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif

@endsection

@push('scripts')
    <script defer src="{{ asset('one-stop-mart/js/home.min.js') }}"></script>
@endpush
