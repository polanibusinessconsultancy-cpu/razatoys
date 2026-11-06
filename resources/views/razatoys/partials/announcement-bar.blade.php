@php
  $footer = $footer ?? \App\Models\FooterSetting::getSettings();
  $phone = $footer->phone ?? '0324 9206345';
@endphp

<div class="raza-topbar" id="raza-announcement-bar" role="region" aria-label="Announcement">
  <div class="razatoys-container raza-topbar__inner">
    <div class="raza-topbar__perks">
      <span class="raza-topbar__perk">
        <svg class="raza-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="15" height="15" aria-hidden="true">
          <rect x="1" y="3" width="15" height="13" rx="2"/>
          <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
          <circle cx="5.5" cy="18.5" r="2.5"/>
          <circle cx="18.5" cy="18.5" r="2.5"/>
        </svg>
        <span><strong>Free Nationwide Delivery</strong> on orders over Rs. 3,000</span>
      </span>
      <span class="raza-topbar__perk">
        <svg class="raza-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="15" height="15" aria-hidden="true">
          <polyline points="1 4 1 10 7 10"/>
          <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
        </svg>
        <span>7-Day Easy Replacement</span>
      </span>
      <span class="raza-topbar__perk">
        <svg class="raza-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="15" height="15" aria-hidden="true">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        <span>100% Child-Safe & Certified</span>
      </span>
    </div>

    <div class="raza-topbar__links">
      <a href="{{ route('polani.track-order') }}" class="raza-topbar__link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
        Track Order
      </a>
      <a href="{{ route('polani.contact') }}" class="raza-topbar__link">Help & FAQs</a>
      <span class="raza-topbar__badge">PKR (Rs)</span>
    </div>
  </div>
</div>
