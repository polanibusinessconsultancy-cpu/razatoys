<section class="raza-promos-section" aria-label="Featured Promotions">
  <div class="razatoys-container">
    <div class="raza-promos-grid">
      
      <!-- Promo 1: Mega Toy Sale -->
      @include('razatoys.components.promo-banner', [
        'title' => 'Mega Toy Sale',
        'tag' => 'UP TO 50% OFF',
        'tagBg' => 'var(--raza-pink)',
        'ctaText' => 'Shop Toy Deals →',
        'ctaClass' => 'raza-btn--primary',
        'url' => route('polani.collection', ['category' => 'toys']),
        'image' => 'razatoys/images/banners/promo-mega-toy-sale.png',
        'imageAlt' => 'Mega Toy Sale — Up to 50% Off On Selected Toys',
        'bgGradient' => 'linear-gradient(135deg, #8B5CF6 0%, #A855F7 100%)',
        'textColor' => '#ffffff',
      ])

      <!-- Promo 2: Educational Toys -->
      @include('razatoys.components.promo-banner', [
        'title' => 'Educational Toys',
        'tag' => 'LEARN • BUILD • GROW',
        'tagBg' => 'var(--raza-blue)',
        'ctaText' => 'Explore Learning →',
        'ctaClass' => 'raza-btn--pink',
        'url' => route('polani.collection', ['category' => 'toys']),
        'image' => 'razatoys/images/banners/promo-educational-toys.png',
        'imageAlt' => 'Educational Toys — Learn, Build, and Grow',
        'bgGradient' => 'linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%)',
        'textColor' => 'var(--raza-black)',
      ])

      <!-- Promo 3: Kitchenware (Visually Secondary) -->
      @include('razatoys.components.promo-banner', [
        'title' => 'Kitchenware Collection',
        'tag' => 'HOME ESSENTIALS',
        'tagBg' => 'var(--raza-slate)',
        'ctaText' => 'Shop Kitchenware →',
        'ctaClass' => 'raza-btn--ghost',
        'url' => route('polani.collection', ['category' => 'kitchenware']),
        'image' => 'razatoys/images/banners/promo-kitchenware.png',
        'imageAlt' => 'Kitchenware — High Quality Essentials for Every Home',
        'bgGradient' => 'linear-gradient(135deg, #FFE4E6 0%, #FECDD3 100%)',
        'textColor' => 'var(--raza-black)',
      ])

    </div>
  </div>
</section>
