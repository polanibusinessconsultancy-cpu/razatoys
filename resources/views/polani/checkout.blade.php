@extends('polani.layout')

@section('title', 'Checkout — RazaToys')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/checkout.css') }}">
@endpush

@section('content')
    <main class="osm-checkout-page">
        <div class="osm-checkout-header">
            <nav class="osm-checkout-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">›</span>
                <a href="{{ route('shopping-cart') }}">Shopping Cart</a>
                <span aria-hidden="true">›</span>
                <span aria-current="page">Checkout</span>
            </nav>

            <div class="osm-checkout-heading-row">
                <div>
                    <h1>Checkout</h1>
                    <p>Review your details and choose an available payment method.</p>
                </div>

                <a
                    class="osm-checkout-back"
                    href="{{ route('shopping-cart') }}"
                >
                    Back to Cart
                </a>
            </div>
        </div>

        <livewire:checkout />
    </main>
@endsection
