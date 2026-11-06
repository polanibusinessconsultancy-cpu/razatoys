@props([
  'blogs' => collect(),
])

@php
  $guides = [
    [
      'title' => '10 Best Educational Toys for Kids',
      'desc' => 'Help your child learn while they play!',
      'icon' => '🧩',
      'bg' => '#E0F2FE',
      'link' => route('polani.collection', ['category' => 'toys']),
    ],
    [
      'title' => 'Pretend Play Benefits for Child Development',
      'desc' => 'Why playsets are great for growing minds.',
      'icon' => '🎭',
      'bg' => '#FCE7F3',
      'link' => route('polani.collection', ['category' => 'toys']),
    ],
    [
      'title' => 'Gift Ideas for Every Age',
      'desc' => 'Find the perfect toy for birthdays and special days.',
      'icon' => '🎁',
      'bg' => '#FFF7EA',
      'link' => route('polani.collection', ['category' => 'toys']),
    ],
  ];
@endphp

<section class="raza-guides-section" aria-label="Playtime Guides and Reviews">
  <div class="razatoys-container">
    <div class="raza-guides-layout">
      
      <!-- Left: Top Picks & Playtime Guides -->
      <div class="raza-guides-col">
        <div class="raza-section-header">
          <div class="raza-section-header__title-group">
            <span class="raza-section-header__sparkle" aria-hidden="true">📚</span>
            <h2 class="raza-section-header__title" style="font-size: 1.35rem;">Top Picks &amp; Playtime Guides</h2>
          </div>
        </div>

        <div class="raza-guides-list">
          @foreach($guides as $guide)
            <div class="raza-guide-card" style="background: {{ $guide['bg'] }};">
              <div class="raza-guide-card__icon" aria-hidden="true">
                {{ $guide['icon'] }}
              </div>
              <div class="raza-guide-card__body">
                <h3 class="raza-guide-card__title">{{ $guide['title'] }}</h3>
                <p class="raza-guide-card__desc">{{ $guide['desc'] }}</p>
                <a href="{{ $guide['link'] }}" class="raza-guide-card__link">
                  <span>Read More</span>
                  <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Right: What Our Customers Say -->
      <div class="raza-reviews-col">
        <div class="raza-section-header">
          <div class="raza-section-header__title-group">
            <span class="raza-section-header__sparkle" aria-hidden="true">💬</span>
            <h2 class="raza-section-header__title" style="font-size: 1.35rem;">What Our Customers Say</h2>
          </div>
          <a href="{{ route('polani.collection') }}" class="raza-section-header__link">
            <span>View All</span>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
        </div>

        <div class="raza-testimonial-card">
          <div class="raza-testimonial-stars" aria-label="5 out of 5 stars">
            @for($i = 0; $i < 5; $i++)
              <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--raza-yellow-dark)">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            @endfor
          </div>

          <p class="raza-testimonial-quote">
            &ldquo;Amazing toy collection and fast delivery! My son absolutely loves the RC stunt car. Quality packaging and genuine products.&rdquo;
          </p>

          <div class="raza-testimonial-author">
            <div class="raza-testimonial-avatar">AK</div>
            <div class="raza-testimonial-meta">
              <strong>Ayesha K.</strong>
              <span>Verified Buyer &bull; Lahore</span>
            </div>
          </div>

          <!-- Carousel Controls -->
          <div class="raza-testimonial-nav">
            <button type="button" class="raza-testimonial-arrow" aria-label="Previous Review">‹</button>
            <button type="button" class="raza-testimonial-arrow" aria-label="Next Review">›</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
