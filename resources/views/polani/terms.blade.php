@extends('polani.layout')

@section('title', 'Terms & Conditions — RazaToys')
@section('meta_description', 'Please read our terms and conditions carefully before using our website or placing an order.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--policy">
  {{-- Breadcrumb --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <span aria-current="page">Terms & Conditions</span>
  </nav>

  {{-- Hero Section --}}
  <header class="osm-info-hero">
    <span class="osm-info-hero-eyebrow">Legal Notice</span>
    <h1>Terms & Conditions</h1>
    <p class="osm-info-hero-subtitle">
      Please read our terms and conditions carefully before using our website or placing an order.
    </p>
  </header>

  {{-- Container --}}
  <div class="osm-info-container">
    {{-- Policy Navigation --}}
    <nav class="osm-info-policy-nav" aria-label="Policies Navigation" style="text-align: center; margin-bottom: 30px;">
      <a href="{{ route('polani.privacy') }}">Privacy Policy</a>
      <span>|</span>
      <a href="{{ route('polani.terms') }}" class="active" style="font-weight: bold; color: var(--color-primary-dark);">Terms & Conditions</a>
      <span>|</span>
      <a href="{{ route('polani.shipping') }}">Shipping & Delivery</a>
      <span>|</span>
      <a href="{{ route('polani.returns') }}">Returns & Exchanges</a>
    </nav>

    <div class="osm-info-content-card" style="max-width: 800px;">
      <p class="osm-info-intro">
        Welcome to RazaToys. These Terms and Conditions govern your use of our website and the purchase of our products. By accessing the site or placing an order, you agree to be bound by these terms.
      </p>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">1. General Conditions</h2>
        <p class="osm-info-copy">
          We reserve the right to refuse service to anyone for any reason at any time. You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the service, use of the service, or access to the service without express written permission by us.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">2. Product Accuracy & Specifications</h2>
        <p class="osm-info-copy">
          We make every effort to display the colors, descriptions, packaging, and imagery of our products as accurately as possible. However, the actual display color you see depends on your device screen settings.
        </p>
        <p class="osm-info-copy">
          All descriptions of products or product pricing are subject to change at any time without notice. We reserve the right to discontinue any product at any time.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">3. Ordering & Payment</h2>
        <p class="osm-info-copy">
          By placing an order, you represent that all details you provide to us are true and accurate, that you are authorized to use the payment account/wallet used, and that there are sufficient funds to cover the cost of the products. We reserve the right to refuse or cancel any order.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">4. Delivery & Return Policies</h2>
        <p class="osm-info-copy">
          The delivery of products is carried out by third-party logistics firms. While we strive to ensure timely dispatch, we are not liable for delayed deliveries caused by courier processing errors. Our return and exchange rules are governed by our specific policy document.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">5. Intellectual Property</h2>
        <p class="osm-info-copy">
          All content included on this website, such as text, graphics, logos, button icons, images, digital downloads, and software, is the property of RazaToys and is protected by local and international copyright laws.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">6. Limitation of Liability</h2>
        <p class="osm-info-copy">
          In no case shall RazaToys, our directors, officers, employees, or affiliates be liable for any injury, loss, claim, or any direct, indirect, incidental, punitive, special, or consequential damages of any kind, including, without limitation lost profits, lost revenue, lost savings, loss of data, or replacement costs.
        </p>
      </section>
    </div>
  </div>
</div>
@endsection
