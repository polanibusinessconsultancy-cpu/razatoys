@php
  $layoutCategories = $layoutCategories ?? \App\Models\Category::where('is_storefront_category', true)->get();
@endphp

<nav class="raza-nav" id="raza-main-nav" aria-label="Main Navigation">
  <div class="razatoys-container raza-nav__container">
    
    <!-- Mega Category Button & Dropdown -->
    <div class="raza-cat-trigger">
      <button
        type="button"
        class="raza-cat-btn"
        id="raza-cat-btn"
        aria-expanded="false"
        aria-haspopup="true"
        aria-controls="raza-cat-dropdown"
      >
        <svg class="raza-cat-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18" aria-hidden="true">
          <line x1="3" y1="6" x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
        <span>All Categories</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="14" height="14" aria-hidden="true">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </button>

      <ul class="raza-cat-dropdown" id="raza-cat-dropdown" role="menu">
        <li>
          <a href="{{ route('polani.collection') }}" class="raza-cat-dropdown__item" role="menuitem">
            <span>🧸 All Products</span>
          </a>
        </li>
        @foreach($layoutCategories as $cat)
          <li>
            <a href="{{ route('polani.collection') }}?category={{ $cat->slug }}" class="raza-cat-dropdown__item" role="menuitem">
              <span>{{ $cat->name }}</span>
              <span class="raza-cat-dropdown__item-badge">Explore</span>
            </a>
          </li>
        @endforeach
      </ul>
    </div>

    <!-- Horizontal Links (Strict Single Line on Desktop) -->
    <ul class="raza-nav__links" id="raza-desktop-links">
      <li>
        <a href="{{ route('home') }}" class="raza-nav__link @if(request()->routeIs('home')) raza-nav__link--active @endif">
          Home
        </a>
      </li>
      <li>
        <a href="{{ route('polani.collection') }}?category=toys" class="raza-nav__link @if(request('category') === 'toys') raza-nav__link--active @endif">
          Toys &amp; Games
        </a>
      </li>
      <li>
        <a href="{{ route('polani.collection') }}" class="raza-nav__link @if(request()->routeIs('polani.collection') && !request('category')) raza-nav__link--active @endif">
          Shop
        </a>
      </li>
      <li>
        <a href="{{ route('polani.new-arrivals') }}" class="raza-nav__link @if(request()->routeIs('polani.new-arrivals')) raza-nav__link--active @endif">
          New Arrivals
        </a>
      </li>
      <li>
        <a href="{{ route('polani.deals') }}" class="raza-nav__link raza-nav__link--deal @if(request()->routeIs('polani.deals')) raza-nav__link--active @endif">
          Deals
          <span class="raza-nav__pill">Sale</span>
        </a>
      </li>
      <li>
        <a href="{{ route('polani.collection') }}?category=kitchenware" class="raza-nav__link @if(request('category') === 'kitchenware') raza-nav__link--active @endif">
          Kitchenware
        </a>
      </li>

      <!-- Accessible More Dropdown -->
      <li class="raza-nav__item-dropdown">
        <button
          type="button"
          class="raza-nav__link raza-nav__dropdown-btn"
          id="raza-more-btn"
          aria-expanded="false"
          aria-haspopup="true"
          aria-controls="raza-more-dropdown"
        >
          <span>More</span>
          <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </button>

        <ul class="raza-nav__sub-menu" id="raza-more-dropdown" role="menu">
          <li role="none">
            <a href="{{ route('polani.track-order') }}" class="raza-nav__sub-link" role="menuitem">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>Track Order</span>
            </a>
          </li>
          <li role="none">
            <a href="{{ route('polani.about') }}" class="raza-nav__sub-link" role="menuitem">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <span>About RazaToys</span>
            </a>
          </li>
          <li role="none">
            <a href="{{ route('polani.contact') }}" class="raza-nav__sub-link" role="menuitem">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <span>Contact Support</span>
            </a>
          </li>
          <li role="none">
            <a href="{{ route('polani.shipping') }}" class="raza-nav__sub-link" role="menuitem">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
              <span>Shipping &amp; Delivery</span>
            </a>
          </li>
          <li role="none">
            <a href="{{ route('polani.returns') }}" class="raza-nav__sub-link" role="menuitem">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
              <span>Returns &amp; Refunds</span>
            </a>
          </li>
          <li role="none">
            <a href="{{ route('polani.faq') }}" class="raza-nav__sub-link" role="menuitem">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <span>Help &amp; FAQs</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>

  </div>
</nav>
