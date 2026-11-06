<section class="raza-newsletter-section" aria-label="Special Deals Newsletter">
  <div class="razatoys-container">
    <div class="raza-newsletter-box">
      
      <div class="raza-newsletter-box__info">
        <div class="raza-newsletter-box__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" color="var(--raza-pink)">
            <polyline points="20 12 20 22 4 22 4 12"/>
            <rect x="2" y="7" width="20" height="5"/>
            <line x1="12" y1="22" x2="12" y2="7"/>
            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
          </svg>
        </div>
        <div class="raza-newsletter-box__text">
          <h3 class="raza-newsletter-box__title">Subscribe &amp; Get Special Deals!</h3>
          <p class="raza-newsletter-box__sub">Be the first to know about new toys, exclusive offers and big discounts.</p>
        </div>
      </div>

      <form action="{{ Route::has('newsletter.subscribe') ? route('newsletter.subscribe') : '#' }}" method="POST" class="raza-newsletter-form">
        @csrf
        <input
          type="email"
          name="email"
          class="raza-newsletter-form__input"
          placeholder="Enter your email address"
          required
          aria-label="Email address for discounts and newsletters"
        >
        <button type="submit" class="raza-btn raza-btn--pink raza-newsletter-form__btn">
          <span>Subscribe</span>
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </button>
      </form>

    </div>
  </div>
</section>
