@php
  $layoutCategories = $layoutCategories ?? \App\Models\Category::where('is_storefront_category', true)->get();
  $footer = $footer ?? \App\Models\FooterSetting::getSettings();
  $whatsappPhone = preg_replace('/\D+/', '', $footer->phone ?? '923249206345');
@endphp

<!-- Backdrop -->
<div class="raza-mobile-backdrop" id="raza-mobile-backdrop" aria-hidden="true"></div>

<!-- Slide-out Drawer -->
<aside
  class="raza-mobile-drawer"
  id="raza-mobile-drawer"
  role="dialog"
  aria-modal="true"
  aria-label="Mobile Navigation Drawer"
>
  <!-- Drawer Header -->
  <div class="raza-mobile-drawer__header">
    <div style="display: flex; align-items: center; gap: 10px;">
      <div style="width: 32px; height: 32px; background: var(--raza-yellow); border: 2px solid var(--raza-black); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
        🧸
      </div>
      <span class="raza-mobile-drawer__title">Raza<span style="color: var(--raza-pink);">Toys</span></span>
    </div>
    <button
      type="button"
      class="raza-mobile-drawer__close"
      id="raza-mobile-close"
      aria-label="Close mobile menu"
    >
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>

  <!-- Mobile Search inside Drawer -->
  <div style="padding: 16px 20px 0 20px;">
    <form action="{{ route('polani.collection') }}" method="GET" class="raza-search__form" role="search">
      <input
        type="search"
        name="q"
        class="raza-search__input"
        placeholder="Search toys & games..."
        value="{{ request('q') }}"
        aria-label="Search"
      >
      <button type="submit" class="raza-search__btn" aria-label="Search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </button>
    </form>
  </div>

  <!-- Drawer Body Navigation Links -->
  <div class="raza-mobile-drawer__body">
    <div style="margin-bottom: 20px;">
      <span style="font-size: 0.75rem; font-weight: 800; color: var(--raza-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px; padding-left: 14px;">
        Explore Store
      </span>
      <a href="{{ route('home') }}" class="raza-mobile-drawer__link">
        <span>🏠 Home</span>
      </a>
      <a href="{{ route('polani.collection') }}?category=toys" class="raza-mobile-drawer__link">
        <span>🧸 Toys & Games</span>
      </a>
      <a href="{{ route('polani.collection') }}" class="raza-mobile-drawer__link">
        <span>🛍️ Shop All Products</span>
      </a>
      <a href="{{ route('polani.deals') }}" class="raza-mobile-drawer__link">
        <span style="color: var(--raza-pink-dark);">🔥 Deals & Offers</span>
        <span class="raza-badge raza-badge--pink">Sale</span>
      </a>
      <a href="{{ route('polani.new-arrivals') }}" class="raza-mobile-drawer__link">
        <span>✨ New Arrivals</span>
      </a>
      <a href="{{ route('polani.collection') }}?category=kitchenware" class="raza-mobile-drawer__link">
        <span>🍳 Kitchenware</span>
      </a>
      <a href="{{ route('polani.track-order') }}" class="raza-mobile-drawer__link">
        <span>📦 Track Order</span>
      </a>
    </div>

    <!-- Categories List -->
    <div style="margin-bottom: 20px; border-top: 1px solid var(--raza-border-soft); padding-top: 16px;">
      <span style="font-size: 0.75rem; font-weight: 800; color: var(--raza-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px; padding-left: 14px;">
        Toy Categories
      </span>
      @foreach($layoutCategories as $cat)
        <a href="{{ route('polani.collection') }}?category={{ $cat->slug }}" class="raza-mobile-drawer__link" style="font-size: 0.9rem; font-weight: 600;">
          <span>{{ $cat->name }}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      @endforeach
    </div>

    <!-- Account & Customer Service -->
    <div style="border-top: 1px solid var(--raza-border-soft); padding-top: 16px;">
      <span style="font-size: 0.75rem; font-weight: 800; color: var(--raza-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px; padding-left: 14px;">
        Account & Support
      </span>
      @auth
        <a href="{{ route('users.profile.view') }}" class="raza-mobile-drawer__link">
          <span>👤 My Account</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
          @csrf
          <button type="submit" class="raza-mobile-drawer__link" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">
            <span>🚪 Logout</span>
          </button>
        </form>
      @else
        <a href="{{ route('sign-in') }}" class="raza-mobile-drawer__link">
          <span>🔑 Sign In / Register</span>
        </a>
      @endauth
      <a href="{{ route('shopping-cart') }}" class="raza-mobile-drawer__link">
        <span>🛒 View Cart</span>
      </a>
      <a href="{{ route('polani.contact') }}" class="raza-mobile-drawer__link">
        <span>💬 Contact & Help</span>
      </a>
    </div>

    <!-- WhatsApp Support inside Mobile Drawer -->
    @if(!empty($whatsappPhone))
      <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--raza-border-soft);">
        <a
          href="https://wa.me/{{ $whatsappPhone }}?text={{ rawurlencode('Assalam o Alaikum, I need assistance at RazaToys.') }}"
          class="raza-btn raza-btn--green"
          style="width: 100%; border-radius: var(--raza-radius-full); font-size: 0.88rem;"
          target="_blank"
          rel="noopener noreferrer"
        >
          <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12.01 2.002c-5.513 0-9.99 4.478-9.99 9.991 0 1.763.459 3.484 1.332 5.002l-1.417 5.176 5.309-1.392a9.96 9.96 0 0 0 4.766 1.214h.004c5.513 0 9.99-4.478 9.99-9.992 0-5.513-4.477-9.99-9.994-9.99z"/></svg>
          WhatsApp Support
        </a>
      </div>
    @endif
  </div>
</aside>
