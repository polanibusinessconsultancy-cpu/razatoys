@props([
  'variant' => 'primary', // primary, pink, blue, green, ghost
  'href' => null,
  'type' => 'button',
])

@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => 'raza-btn raza-btn--' . $variant]) }}>
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => 'raza-btn raza-btn--' . $variant]) }}>
    {{ $slot }}
  </button>
@endif
