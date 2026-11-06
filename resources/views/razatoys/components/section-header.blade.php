@props([
  'title' => '',
  'subtitle' => null,
  'sparkle' => null,
  'linkText' => null,
  'linkUrl' => null,
  'badge' => null,
  'badgeColor' => 'var(--raza-pink)',
])

<div class="raza-section-header">
  <div class="raza-section-header__title-group">
    @if($badge)
      <span class="raza-badge" style="background: {{ $badgeColor }}; color: white; margin-bottom: 6px;">
        {{ $badge }}
      </span>
    @endif
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
      @if($sparkle)
        <span class="raza-section-header__sparkle" aria-hidden="true">{{ $sparkle }}</span>
      @endif
      <h2 class="raza-section-header__title">{{ $title }}</h2>
    </div>
    @if($subtitle)
      <p class="raza-section-header__subtitle">{{ $subtitle }}</p>
    @endif
  </div>

  @if($linkText && $linkUrl)
    <a href="{{ $linkUrl }}" class="raza-section-header__link" aria-label="{{ $linkText }} — {{ $title }}">
      <span>{{ $linkText }}</span>
      <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
    </a>
  @endif
</div>
