@php
  $ageGroups = [
    [
      'range' => '0 - 2',
      'label' => 'Years',
      'param' => '0-2',
      'img' => 'razatoys/images/ages/age-0-2.png',
      'bg' => '#FFF7EA',
      'accent' => '#FFBD27',
    ],
    [
      'range' => '3 - 5',
      'label' => 'Years',
      'param' => '3-5',
      'img' => 'razatoys/images/ages/age-3-5.png',
      'bg' => '#FCE7F3',
      'accent' => '#F35B9B',
    ],
    [
      'range' => '6 - 8',
      'label' => 'Years',
      'param' => '6-8',
      'img' => 'razatoys/images/ages/age-6-8.png',
      'bg' => '#E0F2FE',
      'accent' => '#279FE8',
    ],
    [
      'range' => '9 - 12',
      'label' => 'Years',
      'param' => '9-12',
      'img' => 'razatoys/images/ages/age-9-12.png',
      'bg' => '#EDE9FE',
      'accent' => '#8C4EDB',
    ],
    [
      'range' => '13+',
      'label' => 'Years',
      'param' => '13-plus',
      'img' => 'razatoys/images/ages/age-13-plus.png',
      'bg' => '#FEF3C7',
      'accent' => '#D97706',
    ],
  ];
@endphp

<section class="raza-age-section" aria-label="Shop by Age">
  <div class="razatoys-container">
    
    <div class="raza-section-header">
      <div class="raza-section-header__title-group">
        <span class="raza-section-header__sparkle" aria-hidden="true">✨</span>
        <h2 class="raza-section-header__title">Shop by Age</h2>
      </div>
      <a href="{{ route('polani.collection', ['category' => 'toys']) }}" class="raza-section-header__link">
        <span>View All</span>
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    </div>

    <div class="raza-age-grid">
      @foreach($ageGroups as $age)
        @php
          $hasImg = file_exists(public_path($age['img']));
          $url = route('polani.collection', ['category' => 'toys', 'age' => $age['param']]);
        @endphp
        <a href="{{ $url }}" class="raza-age-card" style="background-color: {{ $age['bg'] }};" aria-label="Shop toys for ages {{ $age['range'] }} {{ $age['label'] }}">
          <div class="raza-age-card__avatar">
            @if($hasImg)
              <img
                src="{{ asset($age['img']) }}"
                alt="Ages {{ $age['range'] }}"
                class="raza-age-card__img"
                width="54"
                height="54"
                decoding="async"
              >
            @else
              <span class="raza-age-card__fallback">👶</span>
            @endif
          </div>
          
          <div class="raza-age-card__info">
            <span class="raza-age-card__range">{{ $age['range'] }}</span>
            <span class="raza-age-card__label">{{ $age['label'] }}</span>
          </div>

          <div class="raza-age-card__arrow" style="background-color: {{ $age['accent'] }};">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" color="#ffffff">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </a>
      @endforeach
    </div>

  </div>
</section>
