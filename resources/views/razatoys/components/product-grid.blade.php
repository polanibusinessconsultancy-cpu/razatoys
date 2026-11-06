@props([
  'products' => collect(),
  'columns' => 4,
  'emptyMessage' => 'No products currently available in this section.',
])

<div class="raza-product-grid raza-product-grid--cols-{{ $columns }}">
  @forelse($products as $product)
    @include('razatoys.components.product-card', ['product' => $product])
  @empty
    <div class="raza-empty-state" style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; background: var(--raza-white); border-radius: var(--raza-radius-lg); border: 1.5px dashed var(--raza-border);">
      <p style="font-size: 1.05rem; font-weight: 700; color: var(--raza-muted); margin: 0 0 12px 0;">{{ $emptyMessage }}</p>
      <a href="{{ route('polani.collection') }}" class="raza-btn raza-btn--primary">
        Browse Full Catalog
      </a>
    </div>
  @endforelse
</div>
