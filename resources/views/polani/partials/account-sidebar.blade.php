@php
  $user = auth()->user();
  $currentRouteName = request()->route() ? request()->route()->getName() : '';
@endphp

<aside class="osm-account-sidebar">
  <div class="osm-account-user-info">
    <div class="osm-account-avatar" aria-hidden="true">
      {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
    </div>
    <h3 class="osm-account-name">{{ $user->name ?? 'Guest' }}</h3>
    <p class="osm-account-email">{{ $user->email ?? '' }}</p>
  </div>

  <nav class="osm-account-nav" aria-label="Account navigation">
    <a href="{{ route('user.dashboard') }}" class="osm-account-nav-link {{ $currentRouteName === 'user.dashboard' ? 'osm-account-nav-link--active' : '' }}">
      <!-- Book Icon -->
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
      <span>My Courses</span>
    </a>

    <a href="{{ route('user.payment-details') }}" class="osm-account-nav-link {{ in_array($currentRouteName, ['user.payment-details', 'user.order.details']) ? 'osm-account-nav-link--active' : '' }}">
      <!-- Receipt Icon -->
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
      <span>Order History</span>
    </a>

    @if(Route::has('user.quiz-results') && !in_array($user->role, ['Admin', 'Super Admin']))
      <a href="{{ route('user.quiz-results') }}" class="osm-account-nav-link {{ $currentRouteName === 'user.quiz-results' ? 'osm-account-nav-link--active' : '' }}">
        <!-- Clipboard Icon -->
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        <span>Quiz Results</span>
      </a>
    @endif

    <a href="{{ route('polani.track-order') }}" class="osm-account-nav-link {{ $currentRouteName === 'polani.track-order' ? 'osm-account-nav-link--active' : '' }}">
      <!-- Map Pin/Search Icon -->
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
      <span>Track Order</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="osm-account-logout-form" style="margin: 0;">
      @csrf
      <button type="submit" class="osm-account-nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
        <!-- Logout Icon -->
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        <span>Logout</span>
      </button>
    </form>
  </nav>
</aside>
