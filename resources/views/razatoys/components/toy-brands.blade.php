@php
  $brands = [
    ['name' => 'LEGO', 'color' => '#E52521', 'bg' => '#FFF1F2'],
    ['name' => 'Hot Wheels', 'color' => '#EA580C', 'bg' => '#FFF7ED'],
    ['name' => 'Barbie', 'color' => '#E11D48', 'bg' => '#FDF2F8'],
    ['name' => 'NERF', 'color' => '#2563EB', 'bg' => '#EFF6FF'],
    ['name' => 'Fisher-Price', 'color' => '#DC2626', 'bg' => '#FEF2F2'],
    ['name' => 'Hasbro', 'color' => '#0284C7', 'bg' => '#F0F9FF'],
  ];
@endphp

<section class="raza-brands-section" aria-label="Top Toy Brands">
  <div class="razatoys-container">
    
    <div class="raza-section-header">
      <div class="raza-section-header__title-group">
        <span class="raza-section-header__sparkle" aria-hidden="true">🏷️</span>
        <h2 class="raza-section-header__title">Top Toy Brands</h2>
      </div>
      <a href="{{ route('polani.collection', ['category' => 'toys']) }}" class="raza-section-header__link">
        <span>View All Brands</span>
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    </div>

    <div class="raza-brands-grid">
      @foreach($brands as $brand)
        <a href="{{ route('polani.collection', ['category' => 'toys', 'q' => $brand['name']]) }}" class="raza-brand-pill" style="border-color: rgba(0,0,0,0.08); background: {{ $brand['bg'] }};" aria-label="Explore {{ $brand['name'] }} toys">
          <span class="raza-brand-pill__text" style="color: {{ $brand['color'] }}; font-weight: 800; font-family: var(--raza-font-heading); font-size: 1.1rem; letter-spacing: -0.02em;">
            {{ $brand['name'] }}
          </span>
        </a>
      @endforeach
    </div>

  </div>
</section>
