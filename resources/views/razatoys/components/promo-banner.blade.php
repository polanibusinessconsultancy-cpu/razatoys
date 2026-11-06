@props([
  'title' => '',
  'tag' => null,
  'tagBg' => 'var(--raza-pink)',
  'tagColor' => '#ffffff',
  'ctaText' => 'Shop Now →',
  'ctaClass' => 'raza-btn--primary',
  'url' => '#',
  'image' => null,
  'imageAlt' => null,
  'width' => 412,
  'height' => 116,
  'bgGradient' => 'linear-gradient(135deg, #8B5CF6 0%, #A855F7 100%)',
  'textColor' => '#ffffff',
])

@php
  $hasImg = !empty($image) && file_exists(public_path($image));
@endphp

<a href="{{ $url }}" class="raza-promo-banner" aria-label="{{ $title }} — {{ $ctaText }}">
  @if($hasImg)
    <img
      src="{{ asset($image) }}"
      alt="{{ $imageAlt ?: $title }}"
      class="raza-promo-banner__img"
      loading="lazy"
      decoding="async"
      width="{{ $width }}"
      height="{{ $height }}"
    >
  @else
    <div class="raza-promo-banner__card" style="background: {{ $bgGradient }}; color: {{ $textColor }};">
      <div class="raza-promo-banner__body">
        @if($tag)
          <span class="raza-promo-banner__tag" style="background: {{ $tagBg }}; color: {{ $tagColor }};">
            {{ $tag }}
          </span>
        @endif
        <h3 class="raza-promo-banner__title">{{ $title }}</h3>
        <span class="raza-btn {{ $ctaClass }} raza-btn--sm" style="margin-top: 4px; width: fit-content;">
          {{ $ctaText }}
        </span>
      </div>
    </div>
  @endif
</a>
