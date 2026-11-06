@extends('razatoys.layout')

@section('title', 'RazaToys — Design System & Layout Preview')
@section('body_class', 'page-razatoys-preview')

@section('content')
<div class="razatoys-container" style="padding-top: 40px; padding-bottom: 60px;">
  
  <!-- Preview Header Banner -->
  <div style="background: linear-gradient(135deg, #FFF7EA 0%, #FFF3CD 50%, #FCE7F1 100%); border: 2px solid var(--raza-black); border-radius: var(--raza-radius-xl); padding: 36px 32px; margin-bottom: 40px; box-shadow: 3px 5px 0 var(--raza-black);">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">
      <span class="raza-badge raza-badge--pink">Phase 2 Milestone</span>
      <span class="raza-badge raza-badge--yellow">Design System &amp; Shared Layout</span>
      <span class="raza-badge raza-badge--green">Verified Baseline</span>
    </div>
    <h1 style="font-family: var(--raza-font-heading); font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; color: var(--raza-black); margin: 0 0 10px 0; line-height: 1.15;">
      Welcome to the RazaToys Design System
    </h1>
    <p style="font-size: 1.05rem; color: var(--raza-slate); max-width: 680px; margin: 0 0 20px 0; line-height: 1.6;">
      This isolated preview verifies the shared layout, typography, responsive navigation, brand palette, and interactive components before Phase 3 homepage implementation.
    </p>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
      <a href="{{ route('home') }}" class="raza-btn raza-btn--primary">
        ← Return to Storefront
      </a>
      <a href="{{ route('polani.collection') }}?category=toys" class="raza-btn raza-btn--blue">
        Explore Toy Catalog
      </a>
    </div>
  </div>

  <!-- 1. Brand Palette Tokens Grid -->
  <section style="margin-bottom: 48px;">
    <h2 style="font-family: var(--raza-font-heading); font-size: 1.6rem; font-weight: 800; color: var(--raza-black); margin-bottom: 16px;">
      1. Approved RazaToys Brand Palette
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
      
      <div style="background: #FFBD27; border: 2px solid var(--raza-black); border-radius: var(--raza-radius-md); padding: 18px; color: #17191F; box-shadow: 2px 3px 0 var(--raza-black);">
        <strong style="display: block; font-size: 0.95rem;">Sunshine Yellow</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#FFBD27</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Primary Brand &amp; CTA</span>
      </div>

      <div style="background: #F35B9B; border: 2px solid var(--raza-black); border-radius: var(--raza-radius-md); padding: 18px; color: #ffffff; box-shadow: 2px 3px 0 var(--raza-black);">
        <strong style="display: block; font-size: 0.95rem;">Candy Pink</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#F35B9B</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Badges &amp; Sale</span>
      </div>

      <div style="background: #279FE8; border: 2px solid var(--raza-black); border-radius: var(--raza-radius-md); padding: 18px; color: #ffffff; box-shadow: 2px 3px 0 var(--raza-black);">
        <strong style="display: block; font-size: 0.95rem;">Play Blue</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#279FE8</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Focus &amp; Accents</span>
      </div>

      <div style="background: #79C942; border: 2px solid var(--raza-black); border-radius: var(--raza-radius-md); padding: 18px; color: #ffffff; box-shadow: 2px 3px 0 var(--raza-black);">
        <strong style="display: block; font-size: 0.95rem;">Happy Green</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#79C942</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Success &amp; WhatsApp</span>
      </div>

      <div style="background: #8C4EDB; border: 2px solid var(--raza-black); border-radius: var(--raza-radius-md); padding: 18px; color: #ffffff; box-shadow: 2px 3px 0 var(--raza-black);">
        <strong style="display: block; font-size: 0.95rem;">Play Purple</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#8C4EDB</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Special Collections</span>
      </div>

      <div style="background: #FFF7EA; border: 2px solid var(--raza-black); border-radius: var(--raza-radius-md); padding: 18px; color: #17191F; box-shadow: 2px 3px 0 var(--raza-black);">
        <strong style="display: block; font-size: 0.95rem;">Warm Cream</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#FFF7EA</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Background Surfaces</span>
      </div>

      <div style="background: #17191F; border: 2px solid var(--raza-border); border-radius: var(--raza-radius-md); padding: 18px; color: #ffffff; box-shadow: 2px 3px 0 rgba(0,0,0,0.2);">
        <strong style="display: block; font-size: 0.95rem;">Soft Black</strong>
        <span style="font-family: monospace; font-size: 0.82rem; font-weight: 700;">#17191F</span>
        <span style="display: block; font-size: 0.75rem; margin-top: 4px; opacity: 0.9;">Headings &amp; Borders</span>
      </div>

    </div>
  </section>

  <!-- 2. Interactive Buttons & Badges -->
  <section style="margin-bottom: 48px; background: var(--raza-white); border: 1.5px solid var(--raza-border); border-radius: var(--raza-radius-lg); padding: 28px;">
    <h2 style="font-family: var(--raza-font-heading); font-size: 1.6rem; font-weight: 800; color: var(--raza-black); margin-bottom: 16px;">
      2. Reusable Buttons &amp; Badges
    </h2>
    <div style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center; margin-bottom: 24px;">
      <button type="button" class="raza-btn raza-btn--primary">
        Primary Sunshine Yellow
      </button>

      <button type="button" class="raza-btn raza-btn--pink">
        Candy Pink Action
      </button>

      <button type="button" class="raza-btn raza-btn--blue">
        Play Blue Action
      </button>

      <button type="button" class="raza-btn raza-btn--green">
        Happy Green Action
      </button>

      <button type="button" class="raza-btn raza-btn--ghost">
        Ghost / Outline Button
      </button>
    </div>

    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
      <span class="raza-badge raza-badge--yellow">⭐ Top Seller</span>
      <span class="raza-badge raza-badge--pink">🔥 25% OFF Deal</span>
      <span class="raza-badge raza-badge--blue">👶 Ages 3+ Years</span>
      <span class="raza-badge raza-badge--green">✓ Non-Toxic Certified</span>
    </div>
  </section>

  <!-- 3. Sample Product Cards in Grid -->
  <section style="margin-bottom: 48px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
      <div>
        <h2 style="font-family: var(--raza-font-heading); font-size: 1.6rem; font-weight: 800; color: var(--raza-black); margin: 0;">
          3. Shared Product Card Component
        </h2>
        <p style="font-size: 0.9rem; color: var(--raza-muted); margin: 4px 0 0 0;">
          Toy card aesthetics with age labels, discount tags, price in PKR, and instant Add-to-Cart.
        </p>
      </div>
      <a href="{{ route('polani.collection') }}" class="raza-btn raza-btn--primary" style="font-size: 0.85rem; padding: 8px 16px;">
        View All in Shop →
      </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px;">
      @php
        $previewProducts = [
            [
                'db_id' => 13,
                'name' => 'RC Off-Road Monster Car',
                'slug' => 'rc-off-road-monster-car',
                'category_name' => 'Action Toys',
                'price' => 12600,
                'old_price' => 14500,
                'image' => asset('one-stop-mart/images/product-car.png'),
                'rating' => 4.9,
                'reviews' => 28,
            ],
            [
                'db_id' => 14,
                'name' => 'Musical Learning Activity Cube',
                'slug' => 'musical-learning-activity-cube',
                'category_name' => 'Educational',
                'price' => 4250,
                'old_price' => 5000,
                'image' => asset('one-stop-mart/images/cat-toys.webp'),
                'rating' => 4.8,
                'reviews' => 19,
            ],
            [
                'db_id' => 15,
                'name' => 'Building Blocks Creative Set',
                'slug' => 'building-blocks-creative-set',
                'category_name' => 'Creative Play',
                'price' => 3850,
                'old_price' => 0,
                'image' => asset('one-stop-mart/images/cat-toys.webp'),
                'rating' => 5.0,
                'reviews' => 34,
            ],
            [
                'db_id' => 18,
                'name' => '7 Pcs Non-Stick Cookware Set with Glass Lids',
                'slug' => '7-pcs-non-stick-cookware-set-with-glass-lids',
                'category_name' => 'Kitchenware',
                'price' => 16800,
                'old_price' => 19500,
                'image' => asset('one-stop-mart/images/product-cookware.png'),
                'rating' => 4.7,
                'reviews' => 15,
            ],
        ];
      @endphp

      @foreach($previewProducts as $sampleProduct)
        @include('razatoys.components.product-card', ['product' => $sampleProduct])
      @endforeach
    </div>
  </section>

</div>
@endsection
