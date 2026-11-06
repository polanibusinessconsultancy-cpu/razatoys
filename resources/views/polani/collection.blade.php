@extends('polani.layout')

@section('title', 'Shop — RazaToys')
@section('meta_description', 'Explore quality toys, games, and play essentials available at RazaToys.')
@section('body_class', 'page-collection')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/shop.css') }}">
@endpush

@section('content')
  <div class="container osm-shop-page">
    
    <!-- ============ BREADCRUMB & HERO ============ -->
    <div class="osm-shop-hero">
      <div class="osm-shop-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="osm-shop-breadcrumb__sep">›</span>
        <span class="osm-shop-breadcrumb__current">Shop</span>
      </div>
      <h1 class="osm-shop-hero__title">
        Shop
        @if(request('q') !== null && request('q') !== '')
          for "{{ request('q') }}"
        @endif
      </h1>
    </div>

    <!-- ============ PROMOTIONAL BANNER ============ -->
    <div class="osm-shop-promo">
      <div class="osm-shop-promo__banner">
        <div class="osm-shop-promo__left">
          <div class="osm-shop-promo__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
          </div>
          <div class="osm-shop-promo__text">
            <strong>Everything You Need, All in One Place</strong>
            <span>Explore our handpicked collection of quality products.</span>
          </div>
        </div>
        <a href="#products-section" class="osm-shop-promo__cta">Browse Catalog</a>
      </div>
    </div>

    @php
      $activeCategory = request('category', '');
      $activeMinPrice = request('min_price', '');
      $activeMaxPrice = request('max_price', '');
      $activeSort = request('sort', 'default');
      $activeSearch = request('q', '');
      
      $hasActiveFilters = $activeCategory !== '' || $activeMinPrice !== '' || $activeMaxPrice !== '' || $activeSearch !== '';
    @endphp

    <!-- ============ MAIN SHOP LAYOUT ============ -->
    <!-- Wrapping entire shop container in a GET form to combine all filters -->
    <form method="GET" action="{{ route('polani.collection') }}" id="shop-filter-form">
      <!-- Background Overlay for Mobile Drawer -->
      <div class="osm-filter-overlay" id="shop-filter-overlay"></div>

      <!-- Noscript Fallback Styles -->
      <noscript>
        <style>
          .one-stop-mart-storefront .osm-shop-sidebar.osm-filter-drawer {
            position: static !important;
            display: block !important;
            width: 100% !important;
            box-shadow: none !important;
            margin-bottom: 30px !important;
            height: auto !important;
          }
          .one-stop-mart-storefront .osm-shop-filter-toggle {
            display: none !important;
          }
          .one-stop-mart-storefront .osm-filter-drawer__header {
            display: none !important;
          }
        </style>
      </noscript>

      <div class="osm-shop-main__layout" id="products-section">
        
        <!-- ====== LEFT SIDEBAR / DRAWER ====== -->
        <aside class="osm-shop-sidebar osm-filter-drawer" id="shop-sidebar">
          <!-- Drawer Header -->
          <div class="osm-filter-drawer__header">
            <h3>Filters</h3>
            <button type="button" class="osm-shop-sidebar__close-mobile" id="sidebar-close-mobile" aria-label="Close filters">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <!-- Drawer Body -->
          <div class="osm-filter-drawer__body">
            <!-- Search filter inside sidebar -->
            <div class="osm-filter-group">
              <h3 class="osm-filter-group__title">Search Products</h3>
              <div style="display: flex; gap: 8px;">
                <input 
                  type="text" 
                  name="q" 
                  value="{{ $activeSearch }}" 
                  class="osm-price-input" 
                  placeholder="Search keywords..."
                  style="margin: 0;"
                >
                <button type="submit" class="osm-price-btn" style="width: auto; padding: 8px 12px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
              </div>
            </div>

            <!-- Categories Filter Group -->
            <div class="osm-filter-group">
              <h3 class="osm-filter-group__title">Categories</h3>
              <div class="osm-filter-group__body">
                <label class="osm-filter-radio">
                  <input 
                    type="radio" 
                    name="category" 
                    value="" 
                    {{ $activeCategory === '' ? 'checked' : '' }}
                    onchange="this.form.submit()"
                  >
                  <span>All Categories</span>
                </label>

                @foreach($categories as $category)
                  <label class="osm-filter-radio">
                    <input 
                      type="radio" 
                      name="category" 
                      value="{{ $category->slug }}" 
                      {{ $activeCategory === $category->slug ? 'checked' : '' }}
                      onchange="this.form.submit()"
                    >
                    <span>{{ $category->name }}</span>
                    <span style="font-size: 0.8rem; color: var(--color-text-muted);">({{ $categoryCounts[$category->slug] ?? 0 }})</span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Price Filter Group -->
            <div class="osm-filter-group">
              <h3 class="osm-filter-group__title">Price Range</h3>
              <div class="osm-price-inputs">
                <input 
                  type="number" 
                  name="min_price" 
                  value="{{ $activeMinPrice }}" 
                  class="osm-price-input" 
                  placeholder="Min Rs." 
                  min="0"
                >
                <span style="color: var(--color-text-muted);">–</span>
                <input 
                  type="number" 
                  name="max_price" 
                  value="{{ $activeMaxPrice }}" 
                  class="osm-price-input" 
                  placeholder="Max Rs." 
                  min="0"
                >
              </div>
              <div style="display: flex; gap: 8px;">
                <button type="submit" class="osm-price-btn">Apply Price</button>
              </div>
            </div>
          </div>

          <!-- Drawer Footer (Sticky Actions on Mobile) -->
          <div class="osm-filter-drawer__footer">
            <button type="submit" class="osm-price-btn osm-filter-apply-btn">Apply Filters</button>
            <a href="{{ route('polani.collection') }}" class="osm-clear-btn osm-filter-clear-btn">Clear All</a>
          </div>
        </aside>


        <!-- ====== RIGHT PRODUCT CONTENT ====== -->
        <div class="osm-shop-content" id="shop-content" style="width: 100%;">
          
          <!-- Toolbar -->
          <div class="osm-shop-toolbar">
            @if(isset($products) && $products->isNotEmpty())
              <span class="osm-shop-result-count">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
              </span>
            @else
              <span class="osm-shop-result-count">No products available</span>
            @endif

            <!-- Toolbar Actions wrapper -->
            <div class="osm-shop-toolbar__actions" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
              <!-- Mobile inline filter trigger button -->
              <button type="button" class="osm-filter-trigger-inline" id="shop-filter-toggle-inline" aria-label="Open filter sidebar" style="padding: 8px 16px; border: 1px solid var(--color-border); border-radius: var(--border-radius); font-size: 0.9rem; background: var(--color-primary); color: var(--color-secondary); font-weight: 600; cursor: pointer; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                  <line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/>
                  <line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/>
                  <line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/>
                  <line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/>
                  <line x1="17" y1="16" x2="23" y2="16"/>
                </svg>
                Filters
              </button>

              <!-- Active sorting selection -->
              <div style="display: flex; align-items: center; gap: 8px;">
                <label for="sort-select" style="font-size: 0.9rem; font-weight: 600; color: var(--color-secondary); white-space: nowrap;">Sort:</label>
                <select 
                  id="sort-select" 
                  name="sort" 
                  onchange="this.form.submit()" 
                  style="padding: 8px 12px; border: 1px solid var(--color-border); border-radius: var(--border-radius); font-size: 0.9rem; background: var(--color-white); color: var(--color-text); outline: none; cursor: pointer;"
                >
                  <option value="default" {{ $activeSort === 'default' ? 'selected' : '' }}>Default</option>
                  <option value="newest" {{ $activeSort === 'newest' ? 'selected' : '' }}>Newest</option>
                  <option value="price-low" {{ $activeSort === 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                  <option value="price-high" {{ $activeSort === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                  <option value="name-az" {{ $activeSort === 'name-az' ? 'selected' : '' }}>Name: A to Z</option>
                  <option value="name-za" {{ $activeSort === 'name-za' ? 'selected' : '' }}>Name: Z to A</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Active Filters Summary Badges -->
          @if($hasActiveFilters)
            <div class="osm-active-filters-summary" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 24px; padding: 0 4px;">
              <span style="font-size: 0.85rem; font-weight: bold; color: var(--color-secondary);">Active Filters:</span>
              
              @if($activeSearch !== '')
                <span style="background: rgba(0,0,0,0.05); border: 1px solid var(--color-border); border-radius: 4px; padding: 3px 8px; font-size: 0.8rem; color: var(--color-text-light);">
                  Search: "{{ $activeSearch }}"
                </span>
              @endif

              @if($activeCategory !== '')
                <span style="background: rgba(0,0,0,0.05); border: 1px solid var(--color-border); border-radius: 4px; padding: 3px 8px; font-size: 0.8rem; color: var(--color-text-light); text-transform: capitalize;">
                  Category: {{ $activeCategory }}
                </span>
              @endif

              @if($activeMinPrice !== '' || $activeMaxPrice !== '')
                <span style="background: rgba(0,0,0,0.05); border: 1px solid var(--color-border); border-radius: 4px; padding: 3px 8px; font-size: 0.8rem; color: var(--color-text-light);">
                  Price: Rs. {{ $activeMinPrice ?: '0' }} – Rs. {{ $activeMaxPrice ?: '∞' }}
                </span>
              @endif

              <a href="{{ route('polani.collection') }}" style="font-size: 0.8rem; color: var(--color-red); font-weight: bold; text-decoration: underline; margin-left: 5px;">Reset All</a>
            </div>
          @endif

          <!-- Product Grid / Empty State -->
          @forelse($products as $product)
            @if($loop->first)
              <div class="osm-shop-grid">
            @endif
                @include('polani.partials.osm-product-card', ['product' => $product])
            @if($loop->last)
              </div>
            @endif
          @empty
            <!-- Empty State -->
            <div class="osm-shop-empty">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="48" height="48">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
              </svg>
              @if($activeCategory !== '')
                <h3>No products available in this category yet</h3>
                <p>There are currently no products assigned to the "{{ $categories->firstWhere('slug', $activeCategory)->name ?? $activeCategory }}" category. Please explore our other categories or check back later!</p>
                <div class="osm-shop-empty__actions">
                  <a href="{{ route('polani.collection') }}" class="osm-price-btn" style="text-decoration: none; display: inline-block;">View All Products</a>
                </div>
              @elseif($hasActiveFilters)
                <h3>No products found</h3>
                <p>We couldn't find any products matching your current filters. Try resetting the options or adjusting your search term.</p>
                <div class="osm-shop-empty__actions">
                  <a href="{{ route('polani.collection') }}" class="osm-price-btn" style="text-decoration: none; display: inline-block;">Clear All Filters</a>
                </div>
              @else
                <h3>No products available</h3>
                <p>No products are currently available in the catalog. Please check back later.</p>
              @endif
            </div>
          @endforelse

          <!-- Laravel Pagination Links -->
          @if(isset($products) && $products->hasPages())
            <div class="osm-shop-pagination">
              {{ $products->appends(request()->query())->links() }}
            </div>
          @endif

        </div>
      </div>
    </form>
  </div>

  <!-- Mobile Filter Toggle Sticky Button (visible on mobile drawer viewports < 1024px) -->
  <button type="button" class="osm-shop-filter-toggle" id="shop-filter-toggle" aria-label="Open filter sidebar">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
      <line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/>
      <line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/>
      <line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/>
      <line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/>
      <line x1="17" y1="16" x2="23" y2="16"/>
    </svg>
    Filters
  </button>

  <!-- ============ TRUST BADGES STRIP ============ -->
  <section class="trust" id="trust-section" style="padding: 40px 0; background: var(--color-white); border-top: 1px solid var(--color-border); margin-top: 40px;">
    <div class="container trust__grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
      <div class="trust__item" style="display: flex; gap: 15px; align-items: flex-start;">
        <div class="trust__icon" style="color: var(--color-primary-dark); flex-shrink: 0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
        </div>
        <div class="trust__text">
          <strong style="display: block; font-size: 1.05rem; color: var(--color-secondary); margin-bottom: 4px;">Quality You Can Trust</strong>
          <span style="font-size: 0.9rem; color: var(--color-text-muted);">We offer only premium quality products.</span>
        </div>
      </div>
      <div class="trust__item" style="display: flex; gap: 15px; align-items: flex-start;">
        <div class="trust__icon" style="color: var(--color-primary-dark); flex-shrink: 0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div class="trust__text">
          <strong style="display: block; font-size: 1.05rem; color: var(--color-secondary); margin-bottom: 4px;">Fast &amp; Secure Delivery</strong>
          <span style="font-size: 0.9rem; color: var(--color-text-muted);">Timely and safe delivery to your doorstep.</span>
        </div>
      </div>
      <div class="trust__item" style="display: flex; gap: 15px; align-items: flex-start;">
        <div class="trust__icon" style="color: var(--color-primary-dark); flex-shrink: 0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <div class="trust__text">
          <strong style="display: block; font-size: 1.05rem; color: var(--color-secondary); margin-bottom: 4px;">100% Safe Payments</strong>
          <span style="font-size: 0.9rem; color: var(--color-text-muted);">Secure Cash on Delivery and direct transfer options.</span>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('shop-sidebar');
      const toggleBtn = document.getElementById('shop-filter-toggle');
      const toggleBtnInline = document.getElementById('shop-filter-toggle-inline');
      const closeBtn = document.getElementById('sidebar-close-mobile');
      const overlay = document.getElementById('shop-filter-overlay');

      function openDrawer() {
        if (sidebar) sidebar.classList.add('active');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Lock background scrolling
      }

      function closeDrawer() {
        if (sidebar) sidebar.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = ''; // Unlock background scrolling
      }

      // Toggle sidebar drawer on mobile/tablet viewports
      if (toggleBtn) {
        toggleBtn.addEventListener('click', openDrawer);
      }

      if (toggleBtnInline) {
        toggleBtnInline.addEventListener('click', openDrawer);
      }

      // Close sidebar drawer
      if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
      }

      if (overlay) {
        overlay.addEventListener('click', closeDrawer);
      }

      // Support closing drawer on Escape keyboard shortcut
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
          closeDrawer();
        }
      });
    });
  </script>
@endpush
