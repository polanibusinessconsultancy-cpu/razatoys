@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
@endphp

@section('title', 'Shipping & Delivery — RazaToys')
@section('meta_description', 'Shipping and delivery policies, timelines and fulfillment information for RazaToys.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--policy">
  {{-- Breadcrumb --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <span aria-current="page">Shipping & Delivery</span>
  </nav>

  {{-- Hero Section --}}
  <header class="osm-info-hero">
    <span class="osm-info-hero-eyebrow">Customer Care</span>
    <h1>Shipping & Delivery</h1>
    <p class="osm-info-hero-subtitle">
      Shipping and delivery guidelines and fulfillment information for your orders.
    </p>
  </header>

  {{-- Container --}}
  <div class="osm-info-container">
    {{-- Policy Navigation --}}
    <nav class="osm-info-policy-nav" aria-label="Policies Navigation" style="text-align: center; margin-bottom: 30px;">
      <a href="{{ route('polani.privacy') }}">Privacy Policy</a>
      <span>|</span>
      <a href="{{ route('polani.terms') }}">Terms & Conditions</a>
      <span>|</span>
      <a href="{{ route('polani.shipping') }}" class="active" style="font-weight: bold; color: var(--color-primary-dark);">Shipping & Delivery</a>
      <span>|</span>
      <a href="{{ route('polani.returns') }}">Returns & Exchanges</a>
    </nav>

    <div class="osm-info-content-card" style="max-width: 800px;">
      <section class="osm-info-section">
        <h2 class="osm-info-section-title">Fulfillment Policy</h2>
        <p class="osm-info-copy">
          Availability, fulfillment details and any applicable charges are confirmed through the order process or by the store before fulfillment.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">Delivery Timelines</h2>
        <p class="osm-info-copy">
          We strive to process orders as quickly as possible. Once your order is processed, standard delivery timelines depend on your location. Detailed tracking information is accessible on our online tracking portal.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">Delivery Address</h2>
        <p class="osm-info-copy">
          Please ensure that you provide a complete, correct shipping address and an active phone number during checkout to prevent delivery delays.
        </p>
      </section>
    </div>
  </div>
</div>
@endsection
