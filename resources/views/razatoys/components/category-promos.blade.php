<section class="raza-promos-section raza-promos-section--secondary" aria-label="Play Collections">
  <div class="razatoys-container">
    <div class="raza-promos-grid">
      
      <!-- Promo 1: STEM Toys -->
      <a href="{{ route('polani.collection', ['category' => 'toys', 'q' => 'stem']) }}" class="raza-promo-banner" aria-label="Play and Learn STEM Toys">
        @if(file_exists(public_path('razatoys/images/banners/promo-stem-toys.png')))
          <img
            src="{{ asset('razatoys/images/banners/promo-stem-toys.png') }}"
            alt="Play & Learn STEM Toys — Build skills while having fun!"
            class="raza-promo-banner__img"
            decoding="async"
            width="412"
            height="116"
          >
        @else
          <div class="raza-promo-banner__card" style="background: linear-gradient(135deg, #BAE6FD 0%, #7DD3FC 100%);">
            <div class="raza-promo-banner__body">
              <h3 class="raza-promo-banner__title">Play &amp; Learn STEM Toys</h3>
              <p class="raza-promo-banner__desc">Build skills while having fun!</p>
              <span class="raza-btn raza-btn--yellow raza-btn--sm">Shop STEM Toys →</span>
            </div>
          </div>
        @endif
      </a>

      <!-- Promo 2: Dolls World -->
      <a href="{{ route('polani.collection', ['category' => 'toys', 'q' => 'doll']) }}" class="raza-promo-banner" aria-label="Dolls World">
        @if(file_exists(public_path('razatoys/images/banners/promo-dolls-world.png')))
          <img
            src="{{ asset('razatoys/images/banners/promo-dolls-world.png') }}"
            alt="Dolls World — Imagination has no limits!"
            class="raza-promo-banner__img"
            decoding="async"
            width="412"
            height="116"
          >
        @else
          <div class="raza-promo-banner__card" style="background: linear-gradient(135deg, #FBCFE8 0%, #F472B6 100%); color: white;">
            <div class="raza-promo-banner__body">
              <h3 class="raza-promo-banner__title">Dolls World</h3>
              <p class="raza-promo-banner__desc">Imagination has no limits!</p>
              <span class="raza-btn raza-btn--yellow raza-btn--sm">Explore Dolls →</span>
            </div>
          </div>
        @endif
      </a>

      <!-- Promo 3: Outdoor Play -->
      <a href="{{ route('polani.collection', ['category' => 'toys', 'q' => 'outdoor']) }}" class="raza-promo-banner" aria-label="Outdoor Play">
        @if(file_exists(public_path('razatoys/images/banners/promo-outdoor-play.png')))
          <img
            src="{{ asset('razatoys/images/banners/promo-outdoor-play.png') }}"
            alt="Outdoor Play — Scooters, Balls & More!"
            class="raza-promo-banner__img"
            decoding="async"
            width="412"
            height="116"
          >
        @else
          <div class="raza-promo-banner__card" style="background: linear-gradient(135deg, #BBF7D0 0%, #86EFAC 100%);">
            <div class="raza-promo-banner__body">
              <h3 class="raza-promo-banner__title">Outdoor Play</h3>
              <p class="raza-promo-banner__desc">Scooters, Balls &amp; More!</p>
              <span class="raza-btn raza-btn--yellow raza-btn--sm">Shop Outdoor →</span>
            </div>
          </div>
        @endif
      </a>

    </div>
  </div>
</section>
