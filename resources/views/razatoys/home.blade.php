@extends('razatoys.layout')

@section('title', 'RazaToys — Small Toys, Big Smiles | Joyful E-Commerce for Kids')
@section('meta_description', 'Discover a joyful collection of toys for every age, for play, learning, and endless fun. Fast nationwide delivery across Pakistan.')

@section('content')
  {{-- 1. Hero Carousel with 3 Slides --}}
  @include('razatoys.components.hero-carousel', ['banners' => $banners])

  {{-- 2. Trust Badges Strip (5 Pillars) --}}
  @include('razatoys.components.trust-strip')

  {{-- 3. Shop by Category (10 Circular Cards) --}}
  @include('razatoys.components.category-circles', ['categories' => $categories])

  {{-- 4. Feature Promo Banners (3-Column Grid) --}}
  @include('razatoys.components.feature-promos')

  {{-- 5. Trending Toys Right Now (Product Collection Tabbed Slider) --}}
  @include('razatoys.components.trending-toys', [
    'trendingToys' => $trendingToys,
    'bestSellers' => $bestSellers,
    'newArrivals' => $newArrivals,
  ])

  {{-- 6. Category Promo Banners (3-Column Play Collections) --}}
  @include('razatoys.components.category-promos')

  {{-- 7. Shop by Age Section --}}
  @include('razatoys.components.age-group-strip')

  {{-- 8. Educational Toys & Kitchenware Spotlight Collections --}}
  @include('razatoys.components.featured-collections', [
    'educationalToys' => $educationalToys,
    'kitchenware' => $kitchenware,
  ])

  {{-- 9. Top Toy Brands Strip --}}
  @include('razatoys.components.toy-brands')

  {{-- 10. Why Choose RazaToys? Value Strip --}}
  @include('razatoys.components.why-choose-us')

  {{-- 11. Top Picks & Playtime Guides + Customer Testimonials --}}
  @include('razatoys.components.playtime-guides', ['blogs' => $blogs])

  {{-- 12. App Download Banner --}}
  @include('razatoys.components.app-banner')

  {{-- 13. Newsletter Subscription Strip --}}
  @include('razatoys.components.newsletter-strip')
@endsection
