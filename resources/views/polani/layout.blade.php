<!doctype html>
<html lang="en">
  @php
    $footer = $footer ?? \App\Models\FooterSetting::getSettings();
    $whatsappPhone = preg_replace('/\D+/', '', $footer->phone ?? '');
    $cartCount = $cartCount ?? count(session('razatoys_cart', session('polani_cart', [])));
  @endphp
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RazaToys — Joy in Every Box | Quality Toys & Play Essentials')</title>
    <meta name="description" content="@yield('meta_description', 'Discover certified, child-safe toys, action figures, dolls, learning games, and kitchenware essentials at RazaToys. Joy in every box!')" />

    <!-- Google Fonts: Fredoka & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Storefront dependencies & RazaToys Design System -->
    <link rel="stylesheet" href="{{ asset('polani/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/layout.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('razatoys/css/razatoys.min.css') }}" />

    <!-- Favicon Suite -->
    @if(file_exists(public_path('razatoys/images/branding/favicon.ico')))
      <link rel="icon" href="{{ asset('razatoys/images/branding/favicon.ico') }}">
    @else
      <link rel="icon" href="{{ asset('one-stop-mart/images/branding/favicon.ico') }}">
    @endif

    @livewireStyles
    @stack('head')
    @stack('styles')
    
    <style>
      html, body {
        overflow-x: hidden;
        width: 100%;
        position: relative;
      }
    </style>
  </head>
  <body class="razatoys-storefront @yield('body_class')">
    <a class="skip-link" href="#main" style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;">
      Skip to content
    </a>

    <!-- Top Announcement Bar -->
    @include('razatoys.partials.announcement-bar')

    <!-- Main Site Header -->
    @include('razatoys.partials.header')

    <!-- Primary Category Navigation -->
    @include('razatoys.partials.navigation')

    <!-- Mobile Drawer Navigation -->
    @include('razatoys.partials.mobile-drawer')

    <!-- Main Content Area -->
    <main id="main">
      @if (session('error'))
        <div class="razatoys-container" style="padding-top: 16px;">
          <div style="background: #FEE2E2; border: 1.5px solid #EF4444; color: #991B1B; padding: 12px 18px; border-radius: var(--raza-radius-md, 8px); font-weight: 700; font-size: 0.9rem;">
            ⚠️ {{ session('error') }}
          </div>
        </div>
      @endif

      @if (session('success'))
        <div class="razatoys-container" style="padding-top: 16px;">
          <div style="background: #EDF7E7; border: 1.5px solid #79C942; color: #2B6114; padding: 12px 18px; border-radius: var(--raza-radius-md, 8px); font-weight: 700; font-size: 0.9rem;">
            🎉 {{ session('success') }}
          </div>
        </div>
      @endif

      @yield('content')
    </main>

    <!-- Footer -->
    @include('razatoys.partials.footer')

    <!-- WhatsApp Direct Support FAB -->
    @include('razatoys.partials.whatsapp-fab')

    <!-- JavaScript Layer -->
    <script defer src="{{ asset('razatoys/js/razatoys.js') }}"></script>
    <script defer src="{{ asset('polani/js/polani-theme.min.js') }}"></script>
    @livewireScripts
    <script defer src="{{ asset('one-stop-mart/js/theme.min.js') }}"></script>
    @stack('scripts')
  </body>
</html>
