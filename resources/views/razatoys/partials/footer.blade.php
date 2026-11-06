@php
  $footer = $footer ?? \App\Models\FooterSetting::getSettings();
  $layoutCategories = $layoutCategories ?? \App\Models\Category::where('is_storefront_category', true)->get();
@endphp

<footer class="raza-footer" id="raza-main-footer" role="contentinfo">
  <div class="razatoys-container">
    
    <!-- 4-Column Grid -->
    <div class="raza-footer__grid">
      
      <!-- Brand Column -->
      <div>
        <a href="{{ route('home') }}" class="raza-logo" aria-label="RazaToys Home">
          <div class="raza-logo__mark" style="width: 44px; height: 44px;" aria-hidden="true">
            <span style="font-size: 20px;">🧸</span>
          </div>
          <div class="raza-logo__text-group">
            <span class="raza-logo__title" style="font-size: 1.45rem;">Raza<span class="raza-logo__title-highlight">Toys</span></span>
            <span class="raza-logo__tagline" style="font-size: 0.7rem;">Joy in Every Box</span>
          </div>
        </a>

        <p class="raza-footer__brand-p">
          {{ $footer->brand_description ?? 'RazaToys brings smiles, wonder, and quality play into every home. Explore certified, child-safe toys crafted to inspire young imaginations.' }}
        </p>

        <!-- Social Icons -->
        <div class="raza-footer__social">
          <a href="{{ $footer->facebook_url ?? '#' }}" class="raza-footer__social-btn" target="_blank" rel="noopener noreferrer" aria-label="RazaToys on Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="{{ $footer->instagram_url ?? '#' }}" class="raza-footer__social-btn" target="_blank" rel="noopener noreferrer" aria-label="RazaToys on Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="{{ $footer->youtube_url ?? '#' }}" class="raza-footer__social-btn" target="_blank" rel="noopener noreferrer" aria-label="RazaToys on YouTube">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"/></svg>
          </a>
        </div>
      </div>

      <!-- Categories Column -->
      <div>
        <h4 class="raza-footer__heading">Popular Categories</h4>
        <ul class="raza-footer__links">
          <li>
            <a href="{{ route('polani.collection') }}?category=toys" class="raza-footer__link">Toys & Games</a>
          </li>
          <li>
            <a href="{{ route('polani.collection') }}?category=kitchenware" class="raza-footer__link">Kitchenware Essentials</a>
          </li>
          <li>
            <a href="{{ route('polani.collection') }}" class="raza-footer__link">All Products</a>
          </li>
          <li>
            <a href="{{ route('polani.new-arrivals') }}" class="raza-footer__link">New Arrivals</a>
          </li>
          <li>
            <a href="{{ route('polani.deals') }}" class="raza-footer__link">Deals & Promotions</a>
          </li>
        </ul>
      </div>

      <!-- Customer Service Column -->
      <div>
        <h4 class="raza-footer__heading">Customer Care</h4>
        <ul class="raza-footer__links">
          <li>
            <a href="{{ route('polani.track-order') }}" class="raza-footer__link">Track Your Order</a>
          </li>
          <li>
            <a href="{{ route('polani.shipping') }}" class="raza-footer__link">Shipping & Delivery</a>
          </li>
          <li>
            <a href="{{ route('polani.returns') }}" class="raza-footer__link">Returns & Refunds</a>
          </li>
          <li>
            <a href="{{ route('polani.faq') }}" class="raza-footer__link">Frequently Asked Questions</a>
          </li>
          <li>
            <a href="{{ route('polani.contact') }}" class="raza-footer__link">Contact Support</a>
          </li>
        </ul>
      </div>

      <!-- Newsletter Column -->
      <div>
        <h4 class="raza-footer__heading">Join the Fun! 🎉</h4>
        <p class="raza-footer__newsletter-p">
          Subscribe for exclusive toy discounts, secret sales, and new drop announcements.
        </p>
        <form action="{{ route('polani.contact') }}" method="GET" class="raza-newsletter-form">
          <input
            type="email"
            name="email"
            class="raza-newsletter-input"
            placeholder="Enter your email"
            aria-label="Email Address for newsletter"
            required
          >
          <button type="submit" class="raza-newsletter-btn" aria-label="Subscribe to newsletter">
            Join
          </button>
        </form>
        <span style="font-size: 0.74rem; color: var(--raza-muted); display: block; margin-top: 8px;">
          🔒 No spam. Only joyful updates & deals!
        </span>
      </div>

    </div>

    <!-- Trust Badges Strip -->
    <div style="background: var(--raza-white); border: 1.5px solid var(--raza-border); border-radius: var(--raza-radius-lg); padding: 18px 24px; display: flex; align-items: center; justify-content: space-around; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 24px;">🛡️</span>
        <div>
          <strong style="font-size: 0.88rem; display: block; color: var(--raza-black);">100% Child-Safe</strong>
          <span style="font-size: 0.78rem; color: var(--raza-muted);">Certified non-toxic toys</span>
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 24px;">🚚</span>
        <div>
          <strong style="font-size: 0.88rem; display: block; color: var(--raza-black);">Fast Nationwide Shipping</strong>
          <span style="font-size: 0.78rem; color: var(--raza-muted);">Free over Rs. 3,000</span>
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 24px;">🔄</span>
        <div>
          <strong style="font-size: 0.88rem; display: block; color: var(--raza-black);">7-Day Easy Returns</strong>
          <span style="font-size: 0.78rem; color: var(--raza-muted);">Hassle-free replacement</span>
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 24px;">💵</span>
        <div>
          <strong style="font-size: 0.88rem; display: block; color: var(--raza-black);">Cash on Delivery</strong>
          <span style="font-size: 0.78rem; color: var(--raza-muted);">Pay at your doorstep</span>
        </div>
      </div>
    </div>

    <!-- Bottom Copyright & Policies -->
    <div class="raza-footer__bottom">
      <p style="margin: 0;">
        &copy; {{ date('Y') }} <strong>RazaToys</strong>. Joy in Every Box. All rights reserved.
        &bull; <a href="{{ route('polani.terms') }}" style="text-decoration: underline;">Terms &amp; Conditions</a>
        &bull; <a href="{{ route('polani.privacy') }}" style="text-decoration: underline;">Privacy Policy</a>
      </p>

      <div class="raza-footer__payments" aria-label="Accepted payment options">
        <span class="raza-badge raza-badge--yellow">Cash on Delivery</span>
        <span class="raza-badge raza-badge--blue">Bank Transfer</span>
        <span class="raza-badge raza-badge--pink">Visa / Card</span>
      </div>
    </div>

  </div>
</footer>
