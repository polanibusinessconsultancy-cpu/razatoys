@extends('polani.layout')

@section('title', 'Learning Dashboard — One Stop Mart')

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
        <span>My Dashboard</span>
      </nav>

      <!-- Success/Error Alert Handlers -->
      @if(session('success'))
        <div style="background-color: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
          {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
          {{ session('error') }}
        </div>
      @endif

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
                <h1 class="osm-account-title">My Learning Dashboard</h1>
                <p class="osm-account-subtitle">Welcome back, {{ auth()->user()->name }}</p>
              </div>
              <div style="display: flex; gap: 10px;">
                <button type="button" class="osm-btn osm-btn--secondary osm-btn--sm" data-bs-toggle="modal" data-bs-target="#suggestionModal">
                  Suggestion & Feedback
                </button>
                <a href="{{ route('courses') }}" class="osm-btn osm-btn--primary osm-btn--sm">
                  Browse Store
                </a>
              </div>
            </div>

            <!-- Stats Grid -->
            <div class="osm-account-stats-grid">
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">{{ count($purchasedItems) }}</div>
                  <div class="osm-account-stat-label">Active Materials</div>
                </div>
                <div class="osm-account-stat-icon">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">{{ count($pendingItems) }}</div>
                  <div class="osm-account-stat-label">Pending Orders</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #fef3c7; color: #d97706;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
              </div>
              @if(!in_array(auth()->user()->role, ['Admin', 'Super Admin']))
                <div class="osm-account-stat-card">
                  <div>
                    <div class="osm-account-stat-num">{{ $quizStats['total'] ?? 0 }}</div>
                    <div class="osm-account-stat-label">Quizzes Taken</div>
                  </div>
                  <div class="osm-account-stat-icon" style="background-color: #dbeafe; color: #2563eb;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                  </div>
                </div>
              @endif
            </div>

            <!-- Active Learning Sections -->
            <h2 class="osm-account-section-title">My Purchased Courses & Lectures</h2>
            @if(count($purchasedItems) > 0)
              <div class="osm-course-grid">
                @foreach($purchasedItems as $item)
                  <article class="osm-course-card">
                    <div class="osm-course-image">
                      @if($item['type'] === 'course')
                        <img src="{{ $item['item']->image_path ? Storage::url($item['item']->image_path) : asset('one-stop-mart/images/product-perfume.png') }}" alt="{{ $item['item']->name }}" loading="lazy">
                        <span class="osm-course-type-badge">Course</span>
                      @else
                        <img src="{{ $item['item']->image_path ? Storage::url($item['item']->image_path) : ($item['item']->course && $item['item']->course->image_path ? Storage::url($item['item']->course->image_path) : asset('one-stop-mart/images/product-perfume.png')) }}" alt="{{ $item['item']->name }}" loading="lazy">
                        <span class="osm-course-type-badge">Lecture</span>
                      @endif
                    </div>
                    <div class="osm-course-body">
                      <h3 class="osm-course-name">{{ $item['item']->name }}</h3>
                      @if($item['type'] === 'lecture' && $item['item']->course)
                        <p class="osm-course-meta">From Course: <strong>{{ $item['item']->course->name }}</strong></p>
                      @else
                        <p class="osm-course-meta">Purchased On: {{ $item['order_date'] ? \Carbon\Carbon::parse($item['order_date'])->format('F j, Y') : 'N/A' }}</p>
                      @endif
                      <div class="osm-course-actions">
                        @if($item['type'] === 'course')
                          <a href="{{ route('user.course.purchased', $item['item']->id) }}" class="osm-btn osm-btn--primary osm-btn--sm" style="width: 100%;">
                            Go to Course
                          </a>
                        @else
                          <a href="{{ route('user.lecture.standalone', $item['item']->id) }}" class="osm-btn osm-btn--primary osm-btn--sm" style="width: 100%;">
                            Go to Lecture
                          </a>
                        @endif
                      </div>
                    </div>
                  </article>
                @endforeach
              </div>
            @else
              <div class="osm-empty-state">
                <span class="osm-empty-icon" aria-hidden="true">🎓</span>
                <p>You have not purchased any courses or lectures yet.</p>
                <a href="{{ route('courses') }}" class="osm-btn osm-btn--primary" style="margin-top: 15px;">
                  Browse Courses
                </a>
              </div>
            @endif
          </div>

          <!-- Pending Items -->
          @if(count($pendingItems) > 0)
            <div class="osm-account-card">
              <h2 class="osm-account-section-title" style="color: #b45309;">Orders Awaiting Payment</h2>
              <div class="osm-table-wrapper">
                <table class="osm-table">
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Item Name</th>
                      <th>Type</th>
                      <th>Order Date</th>
                      <th style="text-align: right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($pendingItems as $pending)
                      <tr>
                        <td><strong>#PF-{{ $pending['order_id'] }}</strong></td>
                        <td>{{ $pending['item']->name }}</td>
                        <td><span class="osm-badge osm-badge--grey">{{ ucfirst($pending['type']) }}</span></td>
                        <td>{{ $pending['order_date'] ? \Carbon\Carbon::parse($pending['order_date'])->format('M d, Y') : 'N/A' }}</td>
                        <td style="text-align: right;">
                          <a href="{{ route('polani.track-order', ['order_number' => 'PF-' . $pending['order_id']]) }}" class="osm-btn osm-btn--secondary osm-btn--sm">
                            View Order Details
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif

        </main>

      </div>

    </div>
  </div>

  @include('suggestions.modal')
@endsection
