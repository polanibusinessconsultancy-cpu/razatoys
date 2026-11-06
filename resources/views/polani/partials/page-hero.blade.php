@php
  $activeBanner = null;

  if (isset($banners) && count($banners) > 0) {
      foreach ($banners as $banner) {
          $hasBannerText = filled($banner->title)
              || filled($banner->subtitle)
              || (filled($banner->cta_text) && filled($banner->cta_url));

          if ($banner->image_url || $hasBannerText) {
              $activeBanner = $banner;
              break;
          }
      }
  }

  $title = $activeBanner ? $activeBanner->title : ($defaultTitle ?? '');
  $subtitle = $activeBanner ? $activeBanner->subtitle : ($defaultSubtitle ?? '');
  $ctaText = $activeBanner ? $activeBanner->cta_text : ($defaultCtaText ?? '');
  $ctaUrl = $activeBanner ? $activeBanner->cta_url : ($defaultCtaUrl ?? '');
  $imageUrl = $activeBanner ? $activeBanner->image_url : ($defaultImage ?? '');
  $eyebrow = $defaultEyebrow ?? 'ONE STOP MART';
  $watermark = $defaultWatermark ?? strtoupper(str_replace('-', ' ', $pageKey));

  $hasText = filled($title) || filled($subtitle) || (filled($ctaText) && filled($ctaUrl));
  $hasImage = filled($imageUrl);

  $variantClass = match($pageKey) {
      'deals', 'deal' => 'osm-page-hero--deals',
      'new-arrivals', 'new_arrivals', 'new-arrival' => 'osm-page-hero--new-arrivals',
      default => 'osm-page-hero--home',
  };

  $containerWrapperClass = match($pageKey) {
      'deals', 'deal' => 'container osm-page-hero-container--deals',
      'new-arrivals', 'new_arrivals', 'new-arrival' => 'container osm-page-hero-container--new-arrivals',
      default => 'osm-page-hero-container--home',
  };

  $modeClass = '';
  if ($hasText && !$hasImage) {
      $modeClass = 'osm-page-hero--text-only';
  } elseif (!$hasText && $hasImage) {
      $modeClass = 'osm-page-hero--image-only';
  }
@endphp

@if($hasText || $hasImage)
  <div class="{{ $containerWrapperClass }}">
    <section class="osm-page-hero {{ $variantClass }} {{ $modeClass }}" aria-label="{{ $watermark }} banner">
      
      @if($hasImage)
        <img
          src="{{ $imageUrl }}"
          alt="{{ $title ?: 'One Stop Mart banner' }}"
          class="osm-page-hero__background"
          loading="eager"
          fetchpriority="high"
          decoding="async"
          aria-hidden="true"
        />
      @endif

      <div class="osm-page-hero__overlay" aria-hidden="true"></div>

      @if($hasText)
        <div class="osm-page-hero__content">
          <div class="osm-page-hero__content-inner">
            @if(filled($eyebrow))
              <span class="osm-page-hero__eyebrow">{{ $eyebrow }}</span>
            @endif

            @if(filled($title))
              <h1 class="osm-page-hero__title">{!! nl2br(e($title)) !!}</h1>
            @endif

            @if(filled($subtitle))
              <p class="osm-page-hero__subtitle">{{ $subtitle }}</p>
            @endif

            @if((filled($ctaText) && filled($ctaUrl)) || (isset($secondaryCtaText) && filled($secondaryCtaText)))
              <div class="osm-page-hero__actions">
                @if(filled($ctaText) && filled($ctaUrl))
                  <a href="{{ $ctaUrl }}" class="osm-page-hero__btn osm-page-hero__btn--primary">
                    {{ $ctaText }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                  </a>
                @endif

                @if(isset($secondaryCtaText) && filled($secondaryCtaText) && isset($secondaryCtaUrl) && filled($secondaryCtaUrl))
                  <a href="{{ $secondaryCtaUrl }}" class="osm-page-hero__btn osm-page-hero__btn--secondary">
                    {{ $secondaryCtaText }}
                  </a>
                @endif
              </div>
            @endif
          </div>
        </div>
      @endif

    </section>
  </div>
@endif
