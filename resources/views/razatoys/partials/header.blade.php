@php
  $cartCount = $cartCount ?? count(session('razatoys_cart', session('polani_cart', [])));
  $logoPath = null;
  if (file_exists(public_path('razatoys/images/branding/razatoys-logo.svg'))) {
      $logoPath = 'razatoys/images/branding/razatoys-logo.svg';
  } elseif (file_exists(public_path('razatoys/images/branding/razatoys-logo.png'))) {
      $logoPath = 'razatoys/images/branding/razatoys-logo.png';
  }
  $hasCustomLogo = !empty($logoPath);
@endphp

<header class="raza-header" id="raza-main-header">
  <div class="razatoys-container raza-header__main">
    
    <!-- Logo -->
    <a href="{{ route('home') }}" class="raza-logo" aria-label="RazaToys — Home">
      @if($hasCustomLogo)
        <img
          src="{{ asset($logoPath) }}"
          alt="RazaToys — Small Toys, Big Smiles"
          class="raza-logo__img"
          width="195"
          height="48"
          fetchpriority="high"
          decoding="async"
        >
      @else
        <!-- Verified SVG Branding Mark based on RazaToys Design System -->
        <div class="raza-logo__mark" aria-hidden="true">
          <svg viewBox="0 0 36 36" width="30" height="30" fill="none">
            <!-- Teddy Bear Silhouette / Face -->
            <circle cx="9" cy="9" r="6" fill="#17191F"/>
            <circle cx="9" cy="9" r="3.5" fill="#FFBD27"/>
            <circle cx="27" cy="9" r="6" fill="#17191F"/>
            <circle cx="27" cy="9" r="3.5" fill="#FFBD27"/>
            <circle cx="18" cy="20" r="13" fill="#17191F"/>
            <circle cx="18" cy="20" r="11" fill="#FFBD27"/>
            <ellipse cx="14" cy="17" rx="1.8" ry="2.2" fill="#17191F"/>
            <ellipse cx="22" cy="17" rx="1.8" ry="2.2" fill="#17191F"/>
            <ellipse cx="18" cy="22" rx="4.5" ry="3.2" fill="#FFF7EA"/>
            <path d="M16.5 21.5 Q18 23 19.5 21.5" stroke="#17191F" stroke-width="1.8" stroke-linecap="round" fill="none"/>
          </svg>
        </div>
        <div class="raza-logo__text-group">
          <span class="raza-logo__title">Raza<span class="raza-logo__title-highlight">Toys</span></span>
          <span class="raza-logo__tagline">Joy in Every Box</span>
        </div>
      @endif
    </a>

    <!-- Live Search Field -->
    <div class="raza-search">
      <form action="{{ route('polani.collection') }}" method="GET" class="raza-search__form" role="search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="18" height="18" color="var(--raza-muted)" style="flex-shrink:0;">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input
          type="search"
          name="q"
          class="raza-search__input"
          placeholder="Search toys, action figures, dolls, educational games..."
          value="{{ request('q') }}"
          aria-label="Search RazaToys catalog"
          autocomplete="off"
        >
        <button type="submit" class="raza-search__btn" aria-label="Submit Search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </button>
      </form>
    </div>

    <!-- Quick Action Hub -->
    <div class="raza-actions">
      <!-- Wishlist Action -->
      <a href="{{ route('polani.collection') }}" class="raza-action-pill" aria-label="View Wishlist" title="Your Wishlist">
        <svg class="raza-action-pill__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
        <span>Wishlist</span>
      </a>

      <!-- Cart Action -->
      <a href="{{ route('shopping-cart') }}" class="raza-action-pill raza-action-pill--cart" aria-label="Shopping Cart with {{ (int)$cartCount }} items">
        <svg class="raza-action-pill__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <span>Cart</span>
        <span class="raza-action-pill__badge" id="raza-cart-count">{{ (int)$cartCount }}</span>
      </a>

      <!-- Account Action -->
      @auth
        <a href="{{ route('users.profile.view') }}" class="raza-action-pill" aria-label="My Account">
          <svg class="raza-action-pill__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          <span>Account</span>
        </a>
      @else
        <a href="{{ route('sign-in') }}" class="raza-action-pill" aria-label="Sign In">
          <svg class="raza-action-pill__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          <span>Sign In</span>
        </a>
      @endauth

      <!-- Mobile Hamburger Button -->
      <button
        type="button"
        class="raza-mobile-toggle"
        id="raza-mobile-toggle"
        aria-label="Open mobile navigation menu"
        aria-expanded="false"
        aria-controls="raza-mobile-drawer"
      >
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

  </div>
</header>
