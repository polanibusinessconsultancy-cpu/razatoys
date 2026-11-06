@extends('polani.layout')

@section('title', 'Order History — One Stop Mart')

@push('styles')
  <link rel="stylesheet" href="{{ asset('one-stop-mart/css/customer-account.css') }}">
@endpush

@section('content')
  <div class="osm-customer-account-page">
    <div class="osm-account-container">
      
      <!-- Breadcrumbs -->
      <nav class="osm-account-breadcrumb" aria-label="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Order History</span>
      </nav>

      <!-- Layout Grid -->
      <div class="osm-account-shell">
        
        <!-- Sidebar Navigation -->
        @include('polani.partials.account-sidebar')

        <!-- Main Account Content -->
        <main class="osm-account-main">
          
          <!-- Summary Header -->
          <div class="osm-account-card">
            <div class="osm-account-header">
              <h1 class="osm-account-title">Order & Transaction History</h1>
              <p class="osm-account-subtitle">Track your transaction history and course purchases</p>
            </div>

            <!-- Stats Grid -->
            <div class="osm-account-stats-grid">
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">Rs. {{ number_format((float)$totalSpent, 2) }}</div>
                  <div class="osm-account-stat-label">Total Spent</div>
                </div>
                <div class="osm-account-stat-icon">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">{{ $totalCourses }}</div>
                  <div class="osm-account-stat-label">Total Items</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #dbeafe; color: #2563eb;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">{{ $paidCourses }}</div>
                  <div class="osm-account-stat-label">Paid Purchases</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #dcfce7; color: #15803d;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">{{ $freeCourses }}</div>
                  <div class="osm-account-stat-label">Free Purchases</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #fef3c7; color: #d97706;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
              </div>
            </div>

            <!-- Transaction Table -->
            <h2 class="osm-account-section-title">Transactions</h2>
            @if($orders->count() > 0)
              <div class="osm-table-wrapper">
                <table class="osm-table">
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Date</th>
                      <th>Items Count</th>
                      <th>Amount</th>
                      <th>Coupon Discount</th>
                      <th>Total</th>
                      <th>Payment Method</th>
                      <th>Status</th>
                      <th style="text-align: right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orders as $order)
                      @php
                        $cartItems = json_decode($order->cart_items, true);
                        $itemCount = is_array($cartItems) ? count($cartItems) : 0;
                        $isSuccessStatus = in_array(strtolower($order->status), ['paid', 'completed']);
                        $isPendingStatus = strtolower($order->status) === 'pending';
                        $isFailedStatus = in_array(strtolower($order->status), ['rejected', 'failed']);
                      @endphp
                      <tr>
                        <td><strong>#PF-{{ $order->id }}</strong></td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>{{ $itemCount }} item(s)</td>
                        <td>Rs. {{ number_format((float)$order->total, 2) }}</td>
                        <td>Rs. {{ number_format((float)($order->discount ?? 0), 2) }}</td>
                        <td class="fw-bold">Rs. {{ number_format((float)($order->final_total ?? $order->total), 2) }}</td>
                        <td>{{ ucfirst($order->payment_method ?? 'N/A') }}</td>
                        <td>
                          @if($isSuccessStatus)
                            <span class="osm-badge osm-badge--green">Completed</span>
                          @elseif($isPendingStatus)
                            <span class="osm-badge osm-badge--amber">Pending</span>
                          @elseif($isFailedStatus)
                            <span class="osm-badge osm-badge--red">Rejected</span>
                          @else
                            <span class="osm-badge osm-badge--grey">{{ ucfirst($order->status) }}</span>
                          @endif
                        </td>
                        <td style="text-align: right;">
                          <a href="{{ route('user.order.details', $order->id) }}" class="osm-btn osm-btn--secondary osm-btn--sm">
                            View Details
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $orders->links() }}
              </div>
            @else
              <div class="osm-empty-state">
                <span class="osm-empty-icon" aria-hidden="true">📥</span>
                <p>No transactions found. If you have purchased courses recently, they may still be pending admin approval.</p>
                <a href="{{ route('courses') }}" class="osm-btn osm-btn--primary" style="margin-top: 15px;">
                  Browse Courses
                </a>
              </div>
            @endif
          </div>

        </main>

      </div>

    </div>
  </div>
@endsection
