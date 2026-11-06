@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
@endphp

@section('title', 'Returns & Exchanges — RazaToys')
@section('meta_description', 'Learn about return and exchange policies at RazaToys.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--policy">
  {{-- Breadcrumb --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <span aria-current="page">Returns & Exchanges</span>
  </nav>

  {{-- Hero Section --}}
  <header class="osm-info-hero">
    <span class="osm-info-hero-eyebrow">Customer Care</span>
    <h1>Returns & Exchanges</h1>
    <p class="osm-info-hero-subtitle">
      Information regarding returns, exchanges and refund policies.
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
      <a href="{{ route('polani.shipping') }}">Shipping & Delivery</a>
      <span>|</span>
      <a href="{{ route('polani.returns') }}" class="active" style="font-weight: bold; color: var(--color-primary-dark);">Returns & Exchanges</a>
    </nav>

    <div class="osm-info-content-card" style="max-width: 800px;">
      <section class="osm-info-section">
        <h2 class="osm-info-section-title">Return and Exchange Guidelines</h2>
        <p class="osm-info-copy">
          Availability, fulfillment details, return periods, and any applicable charges are confirmed through the order process or by the store before fulfillment.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">Standard Return Conditions</h2>
        <p class="osm-info-copy">
          To be eligible for a return or exchange, items must be unused, in their original packaging, and in the same condition as received. Certain promotional items may not be eligible for standard returns.
        </p>
      </section>

      <section class="osm-info-section">
        <h2 class="osm-info-section-title">Process Inquiries</h2>
        <p class="osm-info-copy">
          Please refer to the contacts listed during checkout or order communication to resolve any specific questions related to returns, refunds, or exchanges.
        </p>
      </section>
    </div>
  </div>
</div>
@endsection
