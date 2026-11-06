@props([
  'banners' => collect(),
])

@php
  $hasHero1 = file_exists(public_path('razatoys/images/banners/hero-slide-1.png'));
  $hasBlocksImg = file_exists(public_path('razatoys/images/categories/cat-building-blocks.png'));
  $hasRcImg = file_exists(public_path('razatoys/images/categories/cat-rc-cars.png'));
@endphp

<section class="raza-hero-section" aria-label="Featured Toy Promotions Carousel">
  <div class="razatoys-container">
    <div
      class="raza-carousel"
      id="raza-hero-carousel"
      role="region"
      aria-roledescription="carousel"
      aria-label="Featured Toy Promotions"
      tabindex="0"
    >
      
      <!-- Slide Track -->
      <div class="raza-carousel__track" id="raza-carousel-track" aria-live="polite">
        
        <!-- Slide 1: Approved RazaToys Visual Banner -->
        <div
          class="raza-carousel__slide is-active"
          role="group"
          aria-roledescription="slide"
          aria-label="1 of 3: RazaToys Small Toys Big Smiles"
          id="raza-slide-1"
        >
          <div class="raza-hero-banner raza-hero-banner--art">
            @if($hasHero1)
              <a href="{{ route('polani.collection') }}" class="raza-hero-banner__link" aria-label="Shop Toys Now — RazaToys: Small Toys, Big Smiles">
                <img
                  src="{{ asset('razatoys/images/banners/hero-slide-1.png') }}"
                  alt="RazaToys — Small Toys, Big Smiles. A joyful collection of toys for every age, for play, joy, learning and endless fun. Up to 50% Off On Selected Toys."
                  class="raza-hero-banner__image"
                  width="1264"
                  height="264"
                  fetchpriority="high"
                  decoding="async"
                >
              </a>
            @else
              <div class="raza-hero-banner__fallback">
                <div class="raza-hero-banner__content">
                  <span class="raza-hero-banner__badge" style="background: var(--raza-pink); color: white;">WELCOME TO RAZATOYS</span>
                  <h1 class="raza-hero-banner__title">Small Toys <span style="color: var(--raza-pink);">Big Smiles</span></h1>
                  <p class="raza-hero-banner__subtitle">A joyful collection of toys for every age, for play, learning, and endless fun!</p>
                  <a href="{{ route('polani.collection') }}" class="raza-btn raza-btn--primary raza-hero-banner__cta">
                    <span>Shop Toys Now</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                  </a>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Slide 2: Educational & STEM Play -->
        <div
          class="raza-carousel__slide"
          role="group"
          aria-roledescription="slide"
          aria-label="2 of 3: Educational & STEM Toys"
          id="raza-slide-2"
        >
          <div class="raza-hero-banner raza-hero-banner--stem">
            <div class="raza-hero-banner__content">
              <span class="raza-hero-banner__badge" style="background: var(--raza-blue); color: white;">LEARN • BUILD • GROW</span>
              <h2 class="raza-hero-banner__title">Educational &amp; <span style="color: var(--raza-pink);">STEM Toys</span></h2>
              <p class="raza-hero-banner__subtitle">Inspire curiosity with interactive activity cubes, colorful building sets, and cognitive learning tools.</p>
              <a href="{{ route('polani.collection', ['category' => 'toys']) }}" class="raza-btn raza-btn--primary raza-hero-banner__cta" aria-label="Explore Learning Toys">
                <span>Explore Learning Toys</span>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
            </div>
            <div class="raza-hero-banner__visual">
              @if($hasBlocksImg)
                <img
                  src="{{ asset('razatoys/images/categories/cat-building-blocks.png') }}"
                  alt="Building Blocks & STEM Toys"
                  class="raza-hero-banner__visual-img"
                  width="140"
                  height="140"
                  loading="lazy"
                  decoding="async"
                >
              @endif
              <div class="raza-hero-banner__badge-circle" aria-hidden="true">
                <span class="raza-hero-banner__badge-title">100%</span>
                <span class="raza-hero-banner__badge-sub">Child Safe</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 3: RC Cars & Action Adventures -->
        <div
          class="raza-carousel__slide"
          role="group"
          aria-roledescription="slide"
          aria-label="3 of 3: High-Speed RC Monster Cars & Action Adventures"
          id="raza-slide-3"
        >
          <div class="raza-hero-banner raza-hero-banner--action">
            <div class="raza-hero-banner__content">
              <span class="raza-hero-banner__badge" style="background: var(--raza-purple); color: white;">SPEED &amp; ADVENTURE</span>
              <h2 class="raza-hero-banner__title">High-Speed <span style="color: var(--raza-yellow);">RC Monster Cars</span></h2>
              <p class="raza-hero-banner__subtitle">Rechargeable stunt cars, 360° off-road vehicles, and action playsets engineered for excitement.</p>
              <a href="{{ route('polani.collection', ['category' => 'toys']) }}" class="raza-btn raza-btn--pink raza-hero-banner__cta" aria-label="Shop Action Toys">
                <span>Shop Action Toys</span>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
            </div>
            <div class="raza-hero-banner__visual">
              @if($hasRcImg)
                <img
                  src="{{ asset('razatoys/images/categories/cat-rc-cars.png') }}"
                  alt="RC Off-Road Monster Cars"
                  class="raza-hero-banner__visual-img"
                  width="140"
                  height="140"
                  loading="lazy"
                  decoding="async"
                >
              @endif
              <div class="raza-hero-banner__badge-circle" style="background: var(--raza-yellow); color: var(--raza-black);" aria-hidden="true">
                <span class="raza-hero-banner__badge-title">TOP</span>
                <span class="raza-hero-banner__badge-sub">Trending</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Navigation Arrows -->
      <button
        type="button"
        class="raza-carousel__arrow raza-carousel__arrow--prev"
        id="raza-carousel-prev"
        aria-label="Previous Slide"
        aria-controls="raza-carousel-track"
      >
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </button>

      <button
        type="button"
        class="raza-carousel__arrow raza-carousel__arrow--next"
        id="raza-carousel-next"
        aria-label="Next Slide"
        aria-controls="raza-carousel-track"
      >
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </button>

      <!-- Pagination Dots -->
      <div class="raza-carousel__dots" id="raza-carousel-dots" role="tablist" aria-label="Hero Carousel Navigation">
        <button
          type="button"
          class="raza-carousel__dot is-active"
          role="tab"
          aria-selected="true"
          aria-controls="raza-slide-1"
          aria-label="Slide 1: Small Toys Big Smiles"
          data-slide="0"
        ></button>
        <button
          type="button"
          class="raza-carousel__dot"
          role="tab"
          aria-selected="false"
          aria-controls="raza-slide-2"
          aria-label="Slide 2: Educational & STEM Toys"
          data-slide="1"
        ></button>
        <button
          type="button"
          class="raza-carousel__dot"
          role="tab"
          aria-selected="false"
          aria-controls="raza-slide-3"
          aria-label="Slide 3: High-Speed RC Monster Cars"
          data-slide="2"
        ></button>
      </div>

    </div>
  </div>
</section>
