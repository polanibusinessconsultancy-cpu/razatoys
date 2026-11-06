@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
@endphp

@section('title', 'Frequently Asked Questions (FAQ) — RazaToys')
@section('meta_description', 'Find answers to commonly asked questions about browsing, ordering, payment methods, and tracking on RazaToys.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--faq">
  {{-- Breadcrumb --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <span aria-current="page">FAQ</span>
  </nav>

  {{-- Hero Section --}}
  <header class="osm-info-hero">
    <span class="osm-info-hero-eyebrow">Assistance</span>
    <h1>Frequently Asked Questions</h1>
    <p class="osm-info-hero-subtitle">
      Find answers to commonly asked questions about browsing, ordering, and payment methods.
    </p>
  </header>

  {{-- Container --}}
  <div class="osm-info-container">
    <div class="osm-info-content-card" style="max-width: 800px;">
      {{-- Category: Browsing & Products --}}
      <section class="osm-info-faq-category" aria-labelledby="cat-products">
        <h2 id="cat-products" class="osm-info-faq-category-title">Browsing & Products</h2>
        
        <details class="osm-info-faq-item">
          <summary>How can I browse products on RazaToys?</summary>
          <div class="osm-info-faq-body">
            <p>You can browse our catalog by clicking on "All Products" in the navigation menu or filtering by specific categories such as Toys, Kitchenware, Perfumes, Electronics, Fashion, Home & Living, and Sports.</p>
          </div>
        </details>

        <details class="osm-info-faq-item">
          <summary>How do I search for a specific product?</summary>
          <div class="osm-info-faq-body">
            <p>Use the search icon in the header navigation. Type keywords like "teddy", "bottle", or "cookware" to search our active storefront catalog immediately.</p>
          </div>
        </details>
      </section>

      {{-- Category: Shopping Cart & Checkout --}}
      <section class="osm-info-faq-category" aria-labelledby="cat-ordering">
        <h2 id="cat-ordering" class="osm-info-faq-category-title">Shopping Cart & Checkout</h2>
        
        <details class="osm-info-faq-item">
          <summary>How do I add items to my shopping cart?</summary>
          <div class="osm-info-faq-body">
            <p>Click on any product card or open the product detail page, then click the "Add to Cart" button. You can view, modify quantities, or remove items from your cart at any time.</p>
          </div>
        </details>

        <details class="osm-info-faq-item">
          <summary>What payment methods do you accept?</summary>
          <div class="osm-info-faq-body">
            <p>We support manual payment methods including <strong>Cash on Delivery (COD)</strong>, Online Bank Transfer, JazzCash, and Easypaisa. Payment details and confirmation are provided during the checkout process.</p>
          </div>
        </details>

        <details class="osm-info-faq-item">
          <summary>How does order tracking work?</summary>
          <div class="osm-info-faq-body">
            <p>Once you place an order, you can monitor its progress using our live "Track Order" page. Simply provide your Order Number and email or phone number to load its current status.</p>
          </div>
        </details>
      </section>

      {{-- Category: Store Demonstrations --}}
      <section class="osm-info-faq-category" aria-labelledby="cat-demo">
        <h2 id="cat-demo" class="osm-info-faq-category-title">General Store Inquiries</h2>
        
        <details class="osm-info-faq-item">
          <summary>How can I reach customer support?</summary>
          <div class="osm-info-faq-body">
            <p>You can send us messages directly through our Contact Us page or reach us via phone, email, or WhatsApp. Our support team responds to all inquiries promptly during business hours.</p>
          </div>
        </details>
      </section>
    </div>
  </div>

</div>
@endsection
