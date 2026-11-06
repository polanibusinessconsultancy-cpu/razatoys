@extends('polani.layout')

@section('title', 'Hot Deals — RazaToys')
@section('meta_description', 'Explore our handpicked hot deals and promotions at RazaToys. Unbeatable savings, limited time offers!')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/new-arrivals.css') }}">
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/page-heroes.min.css') }}">
@endpush

@section('content')
<div class="arrivals-page">
    <!-- Breadcrumbs Section -->
    <section class="arrivals-hero-nav" style="padding: 12px 0 6px; background: var(--color-bg-light);">
        <div class="container">
            <nav class="breadcrumb" id="breadcrumb" aria-label="Breadcrumb" style="display: flex; gap: 8px; font-size: 0.85rem; color: var(--color-text-muted);">
                <a href="{{ route('home') }}" class="breadcrumb__link" style="color: var(--color-text-muted); text-decoration: none;">Home</a>
                <span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current" style="color: var(--color-secondary); font-weight: bold;">Deals</span>
            </nav>
        </div>
    </section>

    <!-- Hero Banner Section -->
    @include('polani.partials.page-hero', [
        'pageKey' => 'deals',
        'banners' => $banners ?? null,
        'defaultEyebrow' => 'UNBEATABLE SAVINGS',
        'defaultTitle' => 'HOT DEALS & OFFERS',
        'defaultSubtitle' => 'Grab the best promotional offers on premium quality toys, kitchenware, perfumes, and more. Super discounts, today only!',
        'defaultCtaText' => 'Browse Deals',
        'defaultCtaUrl' => '#deals-grid-anchor',
        'secondaryCtaText' => 'View All Products',
        'secondaryCtaUrl' => route('polani.collection'),
        'defaultImage' => asset('one-stop-mart/images/promo-deal.png'),
        'defaultWatermark' => 'DEALS'
    ])


    <!-- Main Product Grid Section -->
    <section class="arrivals-main-sec" id="deals-grid-anchor" style="padding: 60px 0; background: var(--color-bg-light);">
        <div class="container">
            <!-- Product Grid -->
            <div class="arrivals-grid">
                @forelse($products as $product)
                    @include('polani.partials.osm-product-card', ['product' => $product])
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--color-text-muted);">
                        <p>No active deals available at this time.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
