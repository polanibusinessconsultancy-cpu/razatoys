@extends('polani.layout')

@section('title', ($statusLabel ?? 'Order Status') . ' — RazaToys')

@push('styles')
  <link rel="stylesheet" href="{{ asset('one-stop-mart/css/payment-status.css') }}">
@endpush

@section('content')
  @php
    $orderedItems = $orderedItems ?? [];
    $billingAddress = $billingAddress ?? [];
    $statusTone = $statusTone ?? 'pending';
    $isSuccess = $statusTone === 'success';
    $isFree = ((float)($totalAmount ?? $order->final_total ?? $order->total ?? 0)) <= 0.0;
  @endphp

  <div class="osm-payment-status-page">
    <div class="osm-payment-status-container">
      
      <!-- Breadcrumb -->
      <nav class="osm-payment-status-breadcrumb" aria-label="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Order Status</span>
      </nav>

      <!-- Status Card -->
      <div class="osm-payment-status-card">
        <div class="osm-payment-status-icon osm-payment-status-icon--{{ $statusTone }}" aria-hidden="true">
          @if($isSuccess)
            <!-- Check Icon -->
            <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          @else
            <!-- Clock Icon -->
            <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          @endif
        </div>

        <h1 class="osm-payment-status-heading">
          @if($isSuccess)
            {{ $isFree ? 'Your Free Order Is Complete' : 'Payment Successful' }}
          @else
            Order Received
          @endif
        </h1>
        
        <p class="osm-payment-status-message">
          @if($isSuccess)
            Your order has been completed successfully! You can now access your purchased materials.
          @else
            Your order has been placed. Payment verification is pending.
          @endif
        </p>

        <!-- Instructions (if pending manual payment) -->
        @if(!$isSuccess && !empty($message))
          <div class="osm-payment-status-instructions">
            <h3>Payment Instructions</h3>
            <p>{!! nl2br(e($message)) !!}</p>
          </div>
        @endif

        <!-- Grid Layout -->
        <div class="osm-payment-status-grid">
          
          <!-- Order Information Details Panel -->
          <div class="osm-payment-status-details">
            <h2>Order Details</h2>
            <div class="osm-payment-status-row">
              <span class="osm-payment-status-label">Order Number</span>
              <span class="osm-payment-status-value">{{ $orderNumber ?? ('#PF-' . $order->id) }}</span>
            </div>
            <div class="osm-payment-status-row">
              <span class="osm-payment-status-label">Order Date</span>
              <span class="osm-payment-status-value">{{ $orderDate ?? optional($order->created_at)->format('F j, Y') }}</span>
            </div>
            <div class="osm-payment-status-row">
              <span class="osm-payment-status-label">Payment Method</span>
              <span class="osm-payment-status-value">{{ $paymentLabel ?? ($paymentMethod->name ?? 'Manual Payment') }}</span>
            </div>
            <div class="osm-payment-status-row">
              <span class="osm-payment-status-label">Payment Status</span>
              <span class="osm-payment-status-value">
                @if($isSuccess)
                  Paid
                @else
                  Unpaid
                @endif
              </span>
            </div>
            
            @if(!empty($billingAddress))
              <div style="margin-top: 15px; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                <span class="osm-payment-status-label" style="display: block; margin-bottom: 5px; font-weight: 600; color: #1f2937;">Billing Details</span>
                <span class="osm-payment-status-value" style="display: block; font-size: 14px; font-weight: normal; color: #4b5563; line-height: 1.5;">
                  {{ $billingAddress['name'] ?? '' }}<br>
                  {{ $billingAddress['email'] ?? '' }}<br>
                  {{ $billingAddress['phone'] ?? '' }}
                </span>
              </div>
            @endif
          </div>

          <!-- Items Panel -->
          <div class="osm-payment-status-items">
            <h2>Items Ordered</h2>
            @forelse($orderedItems as $item)
              <div class="osm-payment-status-item">
                <div class="osm-payment-status-item-details">
                  <span class="osm-payment-status-item-name">{{ $item['name'] }}</span>
                  <span class="osm-payment-status-item-meta">
                    Type: {{ $item['type'] === 'Product' ? 'Course' : 'Lecture' }} | Qty: {{ $item['quantity'] }}
                  </span>
                </div>
                <div class="osm-payment-status-item-price">
                  Rs. {{ number_format((float) $item['line_total'], 2) }}
                </div>
              </div>
            @empty
              <div style="color: #6b7280; font-size: 14px;">No item snapshots found for this order.</div>
            @endforelse

            <!-- Summary Rows -->
            <div class="osm-payment-status-summary">
              <div class="osm-payment-status-row">
                <span class="osm-payment-status-label">Subtotal</span>
                <span class="osm-payment-status-value">Rs. {{ number_format((float) ($subtotal ?? $order->total ?? 0), 2) }}</span>
              </div>
              @if(!empty($order->discount) && $order->discount > 0)
                <div class="osm-payment-status-row">
                  <span class="osm-payment-status-label">Coupon Discount</span>
                  <span class="osm-payment-status-value">-Rs. {{ number_format((float) $order->discount, 2) }}</span>
                </div>
              @endif
              <div class="osm-payment-status-row" style="border-bottom: none; margin-top: 10px;">
                <span class="osm-payment-status-label" style="font-weight: 700; color: #111827;">
                  @if($isSuccess)
                    Total Paid
                  @else
                    Total Payable
                  @endif
                </span>
                <span class="osm-payment-status-value" style="font-weight: 700; color: #111827; font-size: 18px;">
                  Rs. {{ number_format((float) ($totalAmount ?? $order->final_total ?? $order->total ?? 0), 2) }}
                </span>
              </div>
            </div>
          </div>

        </div>

        <!-- Action buttons -->
        <div class="osm-payment-status-actions">
          @if($isSuccess)
            @if(Route::has('courses.index'))
              <a href="{{ route('courses.index') }}" class="osm-payment-status-button osm-payment-status-button--primary">
                Continue Learning
              </a>
            @else
              <a href="{{ route('home') }}" class="osm-payment-status-button osm-payment-status-button--primary">
                Back to Home
              </a>
            @endif
          @else
            <a href="{{ route('home') }}" class="osm-payment-status-button osm-payment-status-button--primary">
              Back to Home
            </a>
          @endif
          
          @if(!empty($actionLabel) && !empty($actionUrl))
            <a href="{{ $actionUrl }}" class="osm-payment-status-button osm-payment-status-button--secondary">
              {{ $actionLabel }}
            </a>
          @else
            <a href="{{ route('polani.collection') }}" class="osm-payment-status-button osm-payment-status-button--secondary">
              Continue Shopping
            </a>
          @endif
          
          @if(!empty($billingAddress['email']) || !empty($billingAddress['phone']))
            <a href="{{ route('polani.track-order', array_filter([
                'order_number' => $orderNumber ?? null,
                'email' => $billingAddress['email'] ?? null,
                'phone' => $billingAddress['phone'] ?? null,
              ])) }}" class="osm-payment-status-button osm-payment-status-button--secondary">
              Track Order
            </a>
          @endif
        </div>

      </div>

    </div>
  </div>
@endsection
