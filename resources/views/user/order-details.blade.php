@extends('polani.layout')

@section('title', 'Order Details — One Stop Mart')

@push('styles')
  <link rel="stylesheet" href="{{ asset('one-stop-mart/css/customer-account.css') }}">
@endpush

@section('content')
  @php
    $isSuccessStatus = in_array(strtolower($order->status), ['paid', 'completed']);
    $isPendingStatus = strtolower($order->status) === 'pending';
    $isFailedStatus = in_array(strtolower($order->status), ['rejected', 'failed']);
  @endphp

  <div class="osm-customer-account-page">
    <div class="osm-account-container">
      
      <!-- Breadcrumbs -->
      <nav class="osm-account-breadcrumb" aria-label="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('user.payment-details') }}">Order History</a>
        <span>/</span>
        <span>Order Details</span>
      </nav>

      <!-- Layout Grid -->
      <div class="osm-account-shell">
        
        <!-- Sidebar Navigation -->
        @include('polani.partials.account-sidebar')

        <!-- Main Account Content -->
        <main class="osm-account-main">
          
          <!-- Summary Header -->
          <div class="osm-account-card">
            <div class="osm-account-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
              <div>
                <h1 class="osm-account-title">Order #PF-{{ $order->id }}</h1>
                <p class="osm-account-subtitle">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
              </div>
              <a href="{{ route('user.payment-details') }}" class="osm-btn osm-btn--secondary osm-btn--sm">
                &larr; Back to Order History
              </a>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 30px;">
              
              <!-- Left: Items and Billing -->
              <div style="display: flex; flex-direction: column; gap: 30px;">
                
                <!-- Order Items -->
                <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
                  <h2 class="osm-account-section-title">Items Ordered</h2>
                  @forelse($items as $item)
                    <div class="osm-payment-status-item" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                      <div class="osm-payment-status-item-details">
                        <span class="osm-payment-status-item-name" style="font-weight: 700;">{{ $item['name'] }}</span>
                        <span class="osm-payment-status-item-meta" style="font-size: 13px; color: #6b7280; display: block; margin-top: 4px;">
                          Type: {{ $item['type'] === 'Product' ? 'Course' : 'Lecture' }}
                        </span>
                      </div>
                      <div class="osm-payment-status-item-price" style="font-weight: 700;">
                        Rs. {{ number_format((float)$item['price'], 2) }}
                      </div>
                    </div>
                  @empty
                    <div style="color: #6b7280; font-size: 14px; padding: 15px 0;">No items found in this order.</div>
                  @endforelse
                </div>

                <!-- Billing Address details -->
                @if(!empty($billingAddress))
                  <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
                    <h2 class="osm-account-section-title">Billing Details</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                      @if(!empty($billingAddress['first_name']) || !empty($billingAddress['last_name']))
                        <div>
                          <small style="color: #6b7280; font-weight: 600; display: block;">Name</small>
                          <span style="font-weight: 500;">{{ ($billingAddress['first_name'] ?? '') . ' ' . ($billingAddress['last_name'] ?? '') }}</span>
                        </div>
                      @endif
                      @if(!empty($billingAddress['email']))
                        <div>
                          <small style="color: #6b7280; font-weight: 600; display: block;">Email</small>
                          <span style="font-weight: 500;">{{ $billingAddress['email'] }}</span>
                        </div>
                      @endif
                      @if(!empty($billingAddress['phone']))
                        <div>
                          <small style="color: #6b7280; font-weight: 600; display: block;">Phone</small>
                          <span style="font-weight: 500;">{{ $billingAddress['phone'] }}</span>
                        </div>
                      @endif
                      @if(!empty($billingAddress['address']))
                        <div>
                          <small style="color: #6b7280; font-weight: 600; display: block;">Address</small>
                          <span style="font-weight: 500;">{{ $billingAddress['address'] }}</span>
                        </div>
                      @endif
                      @if(!empty($billingAddress['city']))
                        <div>
                          <small style="color: #6b7280; font-weight: 600; display: block;">City</small>
                          <span style="font-weight: 500;">{{ $billingAddress['city'] }}</span>
                        </div>
                      @endif
                      @if(!empty($billingAddress['country']))
                        <div>
                          <small style="color: #6b7280; font-weight: 600; display: block;">Country</small>
                          <span style="font-weight: 500;">{{ $billingAddress['country'] }}</span>
                        </div>
                      @endif
                    </div>
                  </div>
                @endif

              </div>

              <!-- Right: Summary and Payment Info -->
              <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; height: fit-content;">
                <h2 class="osm-account-section-title">Order Summary</h2>
                
                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #e5e7eb;">
                  <span style="color: #6b7280;">Subtotal</span>
                  <span style="font-weight: 600;">Rs. {{ number_format((float)$order->total, 2) }}</span>
                </div>

                @if(!empty($order->discount) && $order->discount > 0)
                  <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #e5e7eb;">
                    <span style="color: #6b7280;">Coupon Discount</span>
                    <span style="font-weight: 600; color: #15803d;">-Rs. {{ number_format((float)$order->discount, 2) }}</span>
                  </div>
                  @if(!empty($order->coupon_code))
                    <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e5e7eb; font-size: 13px;">
                      <span style="color: #9ca3af;">Coupon Applied</span>
                      <span style="font-weight: 600; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px;">{{ $order->coupon_code }}</span>
                    </div>
                  @endif
                @endif

                <div class="osm-payment-status-row" style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: none; margin-top: 10px;">
                  <span style="font-weight: 700; color: #111827;">
                    @if($isSuccessStatus)
                      Total Paid
                    @else
                      Total Payable
                    @endif
                  </span>
                  <span style="font-weight: 700; color: #111827; font-size: 18px;">
                    Rs. {{ number_format((float)($order->final_total ?? $order->total), 2) }}
                  </span>
                </div>

                <div style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 20px; display: flex; flex-direction: column; gap: 15px;">
                  <div>
                    <small style="color: #6b7280; font-weight: 600; display: block; margin-bottom: 4px;">Payment Method</small>
                    <span style="font-weight: 600; color: #111827;">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                  </div>

                  <div>
                    <small style="color: #6b7280; font-weight: 600; display: block; margin-bottom: 4px;">Payment Status</small>
                    @if($isSuccessStatus)
                      <span class="osm-badge osm-badge--green">Paid</span>
                    @elseif($isPendingStatus)
                      <span class="osm-badge osm-badge--amber">Pending Verification</span>
                    @elseif($isFailedStatus)
                      <span class="osm-badge osm-badge--red">Rejected</span>
                    @else
                      <span class="osm-badge osm-badge--grey">{{ ucfirst($order->status) }}</span>
                    @endif
                  </div>

                  @if($isFailedStatus && !empty($order->rejection_reason))
                    <div style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; padding: 10px 12px; border-radius: 6px; font-size: 13px; margin-top: 5px;">
                      <strong>Rejection Reason:</strong> {{ $order->rejection_reason }}
                    </div>
                  @endif
                </div>

              </div>

            </div>
          </div>

        </main>

      </div>

    </div>
  </div>
@endsection
