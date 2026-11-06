@props([
  'trendingToys' => collect(),
  'bestSellers' => collect(),
  'newArrivals' => collect(),
])

<section class="raza-trending-section" aria-label="Trending Toys Collection">
  <div class="razatoys-container">
    
    <div class="raza-trending-header">
      <div class="raza-section-header__title-group">
        <span class="raza-section-header__sparkle" aria-hidden="true">🔥</span>
        <h2 class="raza-section-header__title">Trending Toys Right Now</h2>
      </div>

      <!-- Collection Tabs -->
      <div class="raza-trending-tabs" role="tablist" aria-label="Product Collections">
        <button type="button" class="raza-tab-btn is-active" role="tab" aria-selected="true" data-tab="all-toys">All Toys</button>
        <button type="button" class="raza-tab-btn" role="tab" aria-selected="false" data-tab="best-sellers">Best Sellers</button>
        <button type="button" class="raza-tab-btn" role="tab" aria-selected="false" data-tab="new-arrivals">New Arrivals</button>
        <button type="button" class="raza-tab-btn" role="tab" aria-selected="false" data-tab="on-sale">On Sale</button>
        <a href="{{ route('polani.collection', ['category' => 'toys']) }}" class="raza-tab-link">View All →</a>
      </div>
    </div>

    <!-- Product Slider Wrapper -->
    <div class="raza-slider-container">
      <button type="button" class="raza-slider-arrow raza-slider-arrow--prev" aria-label="Previous products" onclick="scrollProductSlider('raza-trending-slider', -300)">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
      </button>

      <!-- All Toys Tab Pane -->
      <div class="raza-product-slider" id="raza-trending-slider" role="region" aria-label="Products">
        @forelse($trendingToys as $product)
          <div class="raza-slider-item">
            @include('razatoys.components.product-card', ['product' => $product])
          </div>
        @empty
          <div class="raza-empty-state" style="padding: 40px; text-align: center; width: 100%;">
            <p style="font-weight: 700; color: var(--raza-muted);">No toy products found currently.</p>
          </div>
        @endforelse
      </div>

      <button type="button" class="raza-slider-arrow raza-slider-arrow--next" aria-label="Next products" onclick="scrollProductSlider('raza-trending-slider', 300)">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>

  </div>
</section>
