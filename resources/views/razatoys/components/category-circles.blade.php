@props([
  'categories' => collect(),
])

@php
  $toyCategories = [
    [
      'name' => 'Soft Toys',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'soft-toys'],
      'image' => 'razatoys/images/categories/cat-soft-toys.png',
      'bg' => '#FFF7EA',
    ],
    [
      'name' => 'RC Cars & Vehicles',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'car'],
      'image' => 'razatoys/images/categories/cat-rc-cars.png',
      'bg' => '#F3E8FF',
    ],
    [
      'name' => 'Building Blocks',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'blocks'],
      'image' => 'razatoys/images/categories/cat-building-blocks.png',
      'bg' => '#E0F2FE',
    ],
    [
      'name' => 'Puzzles & Games',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'puzzle'],
      'image' => 'razatoys/images/categories/cat-puzzles-games.png',
      'bg' => '#FEF3C7',
    ],
    [
      'name' => 'Dolls & Playsets',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'doll'],
      'image' => 'razatoys/images/categories/cat-dolls-playsets.png',
      'bg' => '#FCE7F3',
    ],
    [
      'name' => 'Baby & Toddler',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'baby'],
      'image' => 'razatoys/images/categories/cat-baby-toddler.png',
      'bg' => '#DCFCE7',
    ],
    [
      'name' => 'Arts & Crafts',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'crafts'],
      'image' => 'razatoys/images/categories/cat-arts-crafts.png',
      'bg' => '#FFF1F2',
    ],
    [
      'name' => 'Robots & STEM',
      'slug' => 'toys',
      'param' => ['category' => 'toys', 'q' => 'stem'],
      'image' => 'razatoys/images/categories/cat-robots-stem.png',
      'bg' => '#E0E7FF',
    ],
    [
      'name' => 'Kitchenware',
      'slug' => 'kitchenware',
      'param' => ['category' => 'kitchenware'],
      'image' => 'razatoys/images/categories/cat-kitchenware.png',
      'bg' => '#FEE2E2',
    ],
    [
      'name' => 'More Categories',
      'slug' => '',
      'param' => [],
      'image' => 'razatoys/images/categories/cat-more-categories.png',
      'bg' => '#F1F5F9',
    ],
  ];
@endphp

<section class="raza-categories-section" aria-label="Shop by Category">
  <div class="razatoys-container">
    
    @include('razatoys.components.section-header', [
      'title' => 'Shop by Category',
      'sparkle' => '🎉',
      'linkText' => 'View All Categories',
      'linkUrl' => route('polani.collection'),
    ])

    <div class="raza-categories-grid">
      @foreach($toyCategories as $cat)
        @php
          $url = !empty($cat['slug']) ? route('polani.collection', $cat['param']) : route('polani.collection');
          $hasImg = file_exists(public_path($cat['image']));
        @endphp
        <a href="{{ $url }}" class="raza-category-card" aria-label="Explore {{ $cat['name'] }}">
          <div class="raza-category-card__circle" style="background-color: {{ $cat['bg'] }};">
            @if($hasImg)
              <img
                src="{{ asset($cat['image']) }}"
                alt="{{ $cat['name'] }}"
                class="raza-category-card__img"
                loading="lazy"
                decoding="async"
                width="72"
                height="72"
              >
            @else
              <span class="raza-category-card__icon" aria-hidden="true">🧸</span>
            @endif
          </div>
          <span class="raza-category-card__name">{{ $cat['name'] }}</span>
        </a>
      @endforeach
    </div>

  </div>
</section>
