<section class="raza-app-section" aria-label="RazaToys Mobile App">
  <div class="razatoys-container">
    @if(file_exists(public_path('razatoys/images/banners/app-banner.png')))
      <a href="{{ route('polani.collection') }}" class="raza-app-banner-link" aria-label="Download the RazaToys App">
        <img
          src="{{ asset('razatoys/images/banners/app-banner.png') }}"
          alt="Get the RazaToys App — Shop your favorite toys anytime, anywhere! Download on Google Play and the App Store."
          class="raza-app-banner-img"
          fetchpriority="high"
          decoding="async"
          width="1264"
          height="96"
        >
      </a>
    @else
      <div class="raza-app-banner" style="background: linear-gradient(135deg, #7C3AED 0%, #6366F1 100%);">
        <div class="raza-app-banner__content">
          <div class="raza-app-banner__icon" aria-hidden="true">📱</div>
          <div class="raza-app-banner__text">
            <h3 class="raza-app-banner__title">Get the RazaToys App</h3>
            <p class="raza-app-banner__sub">Shop your favorite toys anytime, anywhere!</p>
          </div>
        </div>
        <div class="raza-app-banner__actions">
          <span class="raza-btn raza-btn--yellow">Download Now →</span>
        </div>
      </div>
    @endif
  </div>
</section>
