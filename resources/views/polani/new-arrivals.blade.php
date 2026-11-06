@extends('polani.layout')

@section('title', 'New Arrivals — RazaToys')
@section('meta_description', 'Discover our latest premium additions in Toys, Perfumes, Kitchenware, and more. Fresh arrivals, best prices!')

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
                <span class="breadcrumb__current" style="color: var(--color-secondary); font-weight: bold;">New Arrivals</span>
            </nav>
        </div>
    </section>

    <!-- Hero Banner Section -->
    @include('polani.partials.page-hero', [
        'pageKey' => 'new-arrivals',
        'banners' => $banners ?? null,
        'defaultEyebrow' => 'JUST LANDED',
        'defaultTitle' => 'NEW ARRIVALS',
        'defaultSubtitle' => 'Fresh finds have just landed. Explore the latest premium items added to RazaToys. New season, new favorites!',
        'defaultCtaText' => 'Shop New Arrivals',
        'defaultCtaUrl' => '#arrivals-grid-anchor',
        'secondaryCtaText' => 'View All Products',
        'secondaryCtaUrl' => route('polani.collection'),
        'defaultImage' => asset('one-stop-mart/images/new-arrivals-banner.png'),
        'defaultWatermark' => 'NEW ARRIVALS'
    ])


    <!-- Main Product Grid Section -->
    <section class="arrivals-main-sec" id="arrivals-grid-anchor" style="padding: 60px 0; background: var(--color-bg-light);">
        <div class="container">
            <!-- Product Grid -->
            <div class="arrivals-grid">
                @forelse($products as $product)
                    @include('polani.partials.osm-product-card', ['product' => $product])
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--color-text-muted);">
                        <p>No new arrivals available at this time.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
