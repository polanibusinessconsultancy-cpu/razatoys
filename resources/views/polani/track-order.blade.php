@extends('polani.layout')

@section('title', 'Track Order — RazaToys')
@section('meta_description', 'Track your RazaToys order status using your order number and contact details.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('one-stop-mart/css/customer-account.css') }}">
@endpush

@section('content')
  @php
    $timeline = [
      ['label' => 'Order Received', 'desc' => 'Your order has been received and is awaiting payment verification.', 'icon' => '1'],
      ['label' => 'Payment Confirmed', 'desc' => 'Payment has been successfully verified.', 'icon' => '2'],
      ['label' => 'Preparing Access', 'desc' => 'Your course or lecture access is being configured.', 'icon' => '3'],
      ['label' => 'Access Available', 'desc' => 'Purchased courses are now unlocked in your dashboard.', 'icon' => '4'],
    ];
    $isAuthenticated = $isAuthenticated ?? auth()->check();
    $orders = $orders ?? collect();
  @endphp

  <div class="osm-order-tracking-page">
    <div class="osm-account-container">
      
      <!-- Breadcrumbs -->
      <nav class="osm-account-breadcrumb" aria-label="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Track Order</span>
      </nav>

      <div class="osm-tracking-box">
        <h1 class="osm-account-title" style="text-align: center; margin-bottom: 8px;">Track Your Order</h1>
        <p style="text-align: center; color: #6b7280; font-size: 14px; margin-bottom: 24px;">
          {{ $isAuthenticated ? 'Select an order below to view its real-time tracking status.' : 'Enter your Order number and contact info to track your purchase.' }}
        </p>

        @if($isAuthenticated)
          @if($orders->isNotEmpty())
            <div class="osm-account-nav" style="gap: 12px;">
              @foreach($orders as $trackedOrder)
                @php
                  $isCompleted = in_array(strtolower($trackedOrder['status']), ['paid', 'completed']);
                  $isFailed = in_array(strtolower($trackedOrder['status']), ['failed', 'rejected']);
                @endphp
                <a href="{{ route('polani.track-order', ['order_number' => $trackedOrder['orderNumber']]) }}" class="osm-lecture-link" style="display: flex; justify-content: space-between; align-items: center;">
                  <div>
                    <strong style="color: #111827; display: block;">{{ $trackedOrder['orderNumber'] }}</strong>
                    <span style="font-size: 12px; color: #6b7280;">Placed on: {{ $trackedOrder['orderDate'] }}</span>
                  </div>
                  <div>
                    @if($isCompleted)
                      <span class="osm-badge osm-badge--green">Completed</span>
                    @elseif($isFailed)
                      <span class="osm-badge osm-badge--red">Rejected</span>
                    @else
                      <span class="osm-badge osm-badge--amber">Pending</span>
                    @endif
                  </div>
                </a>
              @endforeach
            </div>
          @else
            <div class="osm-empty-state">
              <span class="osm-empty-icon" aria-hidden="true">📦</span>
              <p>You have not placed any orders yet.</p>
              <a href="{{ route('courses') }}" class="osm-btn osm-btn--primary" style="margin-top: 15px;">
                Shop Now
              </a>
            </div>
          @endif
        @else
          <!-- Guest Lookup Form -->
          <form method="GET" action="{{ route('polani.track-order') }}">
            <div class="osm-form-group">
              <label for="order_number" class="osm-form-label">Order ID / Tracking Number <span style="color: #ef4444;">*</span></label>
              <input type="text" name="order_number" id="order_number" class="osm-form-input" placeholder="e.g. PF-2026-0001" value="{{ $query['order_number'] ?? '' }}" required>
            </div>
            <div class="osm-form-group">
              <label for="email" class="osm-form-label">Email Used at Checkout</label>
              <input type="email" name="email" id="email" class="osm-form-input" placeholder="email@example.com" value="{{ $query['email'] ?? '' }}">
            </div>
            <div class="osm-form-group">
              <label for="phone" class="osm-form-label">Phone Number Used at Checkout</label>
              <input type="text" name="phone" id="phone" class="osm-form-input" placeholder="e.g. 03001234567" value="{{ $query['phone'] ?? '' }}">
            </div>
            
            <button type="submit" class="osm-btn osm-btn--primary" style="width: 100%; margin-top: 10px;">
              Track Order
            </button>
          </form>

          @if(!empty($error))
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-top: 20px; font-weight: 600; font-size: 14px;">
              {{ $error }}
            </div>
          @endif
        @endif
      </div>

      <!-- Tracking Results -->
      @if(!empty($order))
        <div class="osm-account-card" style="margin-top: 40px;">
          <h2 class="osm-account-section-title" style="text-align: center; margin-bottom: 30px;">Tracking Progress for Order {{ $order['orderNumber'] }}</h2>
          
          <!-- Timeline -->
          <div class="osm-tracking-timeline" style="max-width: 600px; margin: 0 auto 40px;">
            @foreach($timeline as $index => $step)
              @php
                $isCompletedStep = $order['statusStep'] >= $index + 1;
                $isActiveStep = $order['statusStep'] == $index + 1;
                $isFailedStep = in_array(strtolower($order['status']), ['failed', 'rejected']) && $index == 1; // Show reject at Confirmed stage
              @endphp
              <div class="osm-tracking-step @if($isFailedStep) osm-tracking-step--failed @elseif($isActiveStep) osm-tracking-step--active @elseif($isCompletedStep) osm-tracking-step--complete @endif">
                <div class="osm-tracking-step-title">
                  {{ $step['label'] }}
                  @if($isActiveStep)
                    <span style="font-size: 11px; font-weight: 600; background-color: #fef3c7; color: #b45309; padding: 2px 6px; border-radius: 4px; margin-left: 8px;">Active Stage</span>
                  @endif
                </div>
                <div class="osm-tracking-step-desc">
                  @if($isFailedStep)
                    Order was cancelled or rejected by admin.
                  @else
                    {{ $step['desc'] }}
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          <!-- Order & Billing Details Grid -->
          <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 30px;">
            <div class="osm-account-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 0;">
              
              <!-- Left panel: Details -->
              <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 16px;">Order Details</h3>
                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e5e7eb;">
                  <span style="color: #6b7280; font-size: 14px;">Order Number</span>
                  <span style="font-weight: 600; font-size: 14px;">{{ $order['orderNumber'] }}</span>
                </div>
                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e5e7eb;">
                  <span style="color: #6b7280; font-size: 14px;">Order Date</span>
                  <span style="font-weight: 600; font-size: 14px;">{{ $order['orderDate'] }}</span>
                </div>
                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e5e7eb;">
                  <span style="color: #6b7280; font-size: 14px;">Payment Method</span>
                  <span style="font-weight: 600; font-size: 14px;">{{ $order['paymentMethod'] }}</span>
                </div>
                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e5e7eb;">
                  <span style="color: #6b7280; font-size: 14px;">Status</span>
                  <span style="font-weight: 600; font-size: 14px;">{{ $order['statusLabel'] }}</span>
                </div>
                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: none; margin-top: 10px;">
                  <span style="font-weight: 700; color: #111827;">
                    @if(in_array(strtolower($order['status']), ['paid', 'completed']))
                      Total Paid
                    @else
                      Total Payable
                    @endif
                  </span>
                  <span style="font-weight: 700; color: #111827; font-size: 16px;">
                    Rs. {{ number_format((float)$order['total'], 2) }}
                  </span>
                </div>
              </div>

              <!-- Right panel: Contact/Billing -->
              <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 16px;">Customer Details</h3>
                <p style="margin: 0 0 10px; font-size: 14px; color: #4b5563;">
                  <strong>Email:</strong> {{ $order['email'] ?? 'N/A' }}
                </p>
                <p style="margin: 0 0 10px; font-size: 14px; color: #4b5563;">
                  <strong>Phone:</strong> {{ $order['phone'] ?? 'N/A' }}
                </p>
                @if(!empty($order['address']))
                  <p style="margin: 0; font-size: 14px; color: #4b5563;">
                    <strong>Billing Address:</strong> {{ $order['address'] }}
                  </p>
                @endif
              </div>

            </div>

            <!-- Items Ordered -->
            @if(!empty($order['items']))
              <div style="margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Items In This Order</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                  @foreach($order['items'] as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff;">
                      <div>
                        <strong style="color: #111827; display: block;">{{ $item['name'] }}</strong>
                        <span style="font-size: 12px; color: #6b7280;">Quantity: {{ $item['quantity'] }} &bull; Price: Rs. {{ number_format((float)$item['price'], 2) }}</span>
                      </div>
                      <div style="font-weight: 700; color: #111827;">
                        Rs. {{ number_format((float)$item['total'], 2) }}
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

          </div>

        </div>
      @endif

    </div>
  </div>
@endsection
