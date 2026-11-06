@props([
  'educationalToys' => collect(),
  'kitchenware' => collect(),
])

<!-- Educational & Learning Toys Spotlight -->
@if($educationalToys->isNotEmpty())
  <section class="raza-collection-section" aria-label="Educational & STEM Toys">
    <div class="razatoys-container">
      
      <div class="raza-section-header">
        <div class="raza-section-header__title-group">
          <span class="raza-section-header__sparkle" aria-hidden="true">🧠</span>
          <h2 class="raza-section-header__title">Learn, Build &amp; Grow — Educational Toys</h2>
        </div>
        <a href="{{ route('polani.collection', ['category' => 'toys', 'q' => 'educational']) }}" class="raza-section-header__link">
          <span>Explore All Learning</span>
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      </div>

      <div class="raza-products-grid">
        @foreach($educationalToys->take(4) as $product)
          @include('razatoys.components.product-card', ['product' => $product])
        @endforeach
      </div>

    </div>
  </section>
@endif

<!-- Kitchenware Collection (Visually Secondary) -->
@if($kitchenware->isNotEmpty())
  <section class="raza-collection-section raza-collection-section--secondary" aria-label="Kitchenware Collection">
    <div class="razatoys-container">
      
      <div class="raza-section-header">
        <div class="raza-section-header__title-group">
          <span class="raza-section-header__sparkle" aria-hidden="true">🍳</span>
          <h2 class="raza-section-header__title" style="font-size: 1.25rem; color: var(--raza-slate);">Kitchenware &amp; Home Essentials</h2>
        </div>
        <a href="{{ route('polani.collection', ['category' => 'kitchenware']) }}" class="raza-section-header__link">
          <span>View Kitchenware</span>
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      </div>

      <div class="raza-products-grid">
        @foreach($kitchenware->take(4) as $product)
          @include('razatoys.components.product-card', ['product' => $product])
        @endforeach
      </div>

    </div>
  </section>
@endif
