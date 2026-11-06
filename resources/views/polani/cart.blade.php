@extends('polani.layout')

@section('title', 'Shopping Cart — RazaToys')
@section('body_class', 'page-cart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/cart.css') }}">
@endpush

@section('content')
  <div class="container osm-cart-page">
    
    <!-- ============ BREADCRUMB ============ -->
    <div class="osm-cart-breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="osm-cart-breadcrumb__sep">›</span>
      <span class="osm-cart-breadcrumb__current">Shopping Cart</span>
    </div>

    <!-- ============ LIVEWIRE COMPONENT ============ -->
    <livewire:shoppingcart />
    
  </div>
@endsection
