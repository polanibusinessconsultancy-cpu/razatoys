@extends('polani.layout')

@section('title', 'Privacy Policy — RazaToys')
@section('meta_description', 'Our privacy policy explains how we collect, use, protect, and manage your personal data when you shop with us.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--policy">
  {{-- Breadcrumb --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <span aria-current="page">Privacy Policy</span>
  </nav>

  {{-- Hero Section --}}
  <header class="osm-info-hero">
    <span class="osm-info-hero-eyebrow">Privacy & Trust</span>
    <h1>Privacy Policy</h1>
    <p class="osm-info-hero-subtitle">
      At RazaToys, we value your trust and are committed to protecting your personal information.
    </p>
  </header>

  {{-- Container --}}
  <div class="osm-info-container">
    {{-- Policy Navigation --}}
    <nav class="osm-info-policy-nav" aria-label="Policies Navigation" style="text-align: center; margin-bottom: 30px;">
      <a href="{{ route('polani.privacy') }}" class="active" style="font-weight: bold; color: var(--color-primary-dark);">Privacy Policy</a>
      <span>|</span>
      <a href="{{ route('polani.terms') }}">Terms & Conditions</a>
      <span>|</span>
      <a href="{{ route('polani.shipping') }}">Shipping & Delivery</a>
      <span>|</span>
      <a href="{{ route('polani.returns') }}">Returns & Exchanges</a>
    </nav>

    <div class="osm-info-content-card" style="max-width: 800px;">
      <p class="osm-info-intro">
        At RazaToys, we value your trust and are committed to protecting your personal information. This Privacy Policy details how we collect, use, and secure your data when you visit our store or make a purchase.
      </p>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">1. Information We Collect</h2>
        <p class="osm-info-copy">
          When you purchase products or register an account on our website, we collect personal information you provide to us, including:
        </p>
        <ul class="osm-info-list">
          <li><strong>Identity Data:</strong> Full name, username, or account login credentials.</li>
          <li><strong>Contact Data:</strong> Email address, phone number, and physical shipping address.</li>
          <li><strong>Transaction Data:</strong> Details about payments, order history, and product preferences.</li>
          <li><strong>Technical Data:</strong> IP address, browser type, device information, and activity logs.</li>
        </ul>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">2. How We Use Your Data</h2>
        <p class="osm-info-copy">
          We use your personal data to provide a seamless shopping experience. Specific purposes include:
        </p>
        <ul class="osm-info-list">
          <li>Processing and delivering your orders, including sending order updates.</li>
          <li>Managing your customer account and providing support.</li>
          <li>Verifying order details to prevent fraudulent transactions.</li>
          <li>Improving our website performance, layout, and product offerings.</li>
        </ul>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">3. Cookies and Site Activity</h2>
        <p class="osm-info-copy">
          We use cookies and similar tracking technologies to enhance your browsing experience. Cookies help us remember your shopping cart items, recognize you when you return to our website, and analyze web traffic. You can choose to disable cookies through your browser settings.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">4. Information Sharing</h2>
        <p class="osm-info-copy">
          We do not sell, rent, or trade your personal information with third parties. We only share necessary delivery data (name, phone, and address) with our authorized logistics partners to fulfill shipping.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">5. Data Security</h2>
        <p class="osm-info-copy">
          To protect your personal information, we take reasonable precautions and follow industry best practices to make sure it is not inappropriately lost, misused, accessed, disclosed, altered or destroyed. All order transmission details are encrypted using Secure Socket Layer (SSL) technology.
        </p>
      </section>
    </div>
  </div>
</div>
@endsection
