<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>One Stop Mart Admin - @yield('title')</title>

    <!-- Bootstrap - Load asynchronously to avoid render blocking (Est. savings: 160ms LCP) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('one-stop-mart/images/branding/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('one-stop-mart/images/branding/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('one-stop-mart/images/branding/favicon-48x48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('one-stop-mart/images/branding/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('one-stop-mart/images/branding/favicon.ico') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/css/corporate-ui-dashboard.css?v=1.0.0') }}" rel="stylesheet" />

    <!-- Conditional Livewire Styles (All admin pages use Livewire) -->
    @livewireStyles

    <style>
        :root {
            --sidebar-bg: #1e293b;
            --sidebar-hover: rgba(255, 255, 255, 0.08);
            --sidebar-active: #3b82f6;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --sidebar-heading: #64748b;
        }

        body {
            background-color: #f1f5f9;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            padding: 0;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 1rem;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-logo-circle {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);
        }

        .admin-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.75rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background-color: var(--sidebar-active);
            color: var(--sidebar-text-active);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            font-size: 1rem;
            opacity: 0.8;
        }

        .sidebar-heading {
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--sidebar-heading);
            padding: 1.5rem 1.5rem 0.5rem;
            letter-spacing: 0.05rem;
        }

        .badge-pending {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 2rem;
            margin-left: auto;
        }

        .card-stats {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .card-stats:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        .card-stats .card-body {
            padding: 1.5rem;
            z-index: 1;
        }

        .card-stats .icon-shape {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            backdrop-filter: blur(4px);
        }

        .card-stats .stats-title {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .card-stats .stats-value {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            color: white;
        }

        .card-stats .card-footer {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-stats .card-footer a {
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            color: white;
        }

        .main-content-wrapper {
            background: #ffffff;
            border-radius: 1.25rem;
            padding: 2.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            min-height: calc(100vh - 140px);
            border: 1px solid #e2e8f0;
        }

        .header-title {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            height: 70%;
            width: 4px;
            background: white;
            border-radius: 0 4px 4px 0;
        }

        .btn-action {
            border-radius: 0.75rem;
            padding: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #1e293b;
        }

        .btn-action:hover {
            background: #3b82f6;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .btn-action i {
            font-size: 1.25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                left: -100%;
                width: 250px;
            }
            .sidebar.show {
                left: 0;
            }
        }

        .polani-admin-body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top, rgba(212, 166, 88, 0.18), transparent 40%),
                linear-gradient(180deg, #050505 0%, #0f0f0f 40%, #151515 100%);
            color: #f8e7d0;
            font-family: 'Montserrat', sans-serif;
        }

        .polani-admin-shell {
            min-height: 100vh;
        }

        .polani-admin-topbar {
            position: sticky;
            top: 0;
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 18px 28px;
            background: rgba(7, 7, 7, 0.96);
            border-bottom: 1px solid rgba(212, 166, 88, 0.18);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }

        .polani-admin-brand {
            text-decoration: none;
            color: #d4a658;
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .polani-admin-brand__word {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            letter-spacing: 0.18em;
        }

        .polani-admin-brand__sub {
            font-size: 0.72rem;
            letter-spacing: 0.45em;
            margin-top: 4px;
            color: #f4d7ab;
        }

        .polani-admin-topbar__center {
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .polani-admin-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            align-self: center;
            padding: 6px 12px;
            border-radius: 999px;
            background: linear-gradient(180deg, #d4a658, #9d6f20);
            color: #111;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .polani-admin-topbar__text {
            color: rgba(248, 231, 208, 0.8);
            font-size: 0.92rem;
        }

        .polani-admin-topbar__actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .polani-admin-link,
        .polani-admin-logout {
            border: 1px solid rgba(212, 166, 88, 0.3);
            background: rgba(255, 255, 255, 0.03);
            color: #f8e7d0;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .polani-admin-link:hover,
        .polani-admin-logout:hover {
            background: rgba(212, 166, 88, 0.12);
            color: #fff;
            border-color: rgba(212, 166, 88, 0.55);
        }

        .polani-admin-logout {
            cursor: pointer;
        }

        .polani-admin-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 0;
            min-height: calc(100vh - 84px);
        }

        .polani-admin-sidebar {
            padding: 24px 16px;
            background: linear-gradient(180deg, #0b0b0b 0%, #141414 100%);
            border-right: 1px solid rgba(212, 166, 88, 0.14);
        }

        .polani-admin-sidebar__panel {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 166, 88, 0.14);
            border-radius: 22px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .polani-admin-sidebar__panel--light {
            background: rgba(212, 166, 88, 0.06);
        }

        .polani-admin-sidebar__title {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            color: #d4a658;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .polani-admin-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            text-decoration: none;
            color: #f5e5cf;
            padding: 12px 14px;
            border-radius: 16px;
            margin-bottom: 8px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .polani-admin-nav:hover,
        .polani-admin-nav.active {
            background: linear-gradient(135deg, rgba(212, 166, 88, 0.22), rgba(255, 255, 255, 0.04));
            border-color: rgba(212, 166, 88, 0.35);
            color: #fff;
        }

        .polani-admin-sidebar__info {
            color: rgba(248, 231, 208, 0.82);
            font-size: 0.92rem;
            margin-bottom: 6px;
        }

        .polani-admin-main {
            padding: 30px;
        }

        .polani-admin-pagehead {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 22px;
        }

        .polani-admin-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: #f8e7d0;
            margin: 0;
        }

        .polani-admin-subtitle {
            margin-top: 10px;
            color: rgba(248, 231, 208, 0.78);
        }

        .polani-admin-alert {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            border-radius: 18px;
            margin-bottom: 18px;
            border: 1px solid rgba(212, 166, 88, 0.16) !important;
        }

        .polani-admin-card {
            background: rgba(10, 10, 10, 0.82);
            border: 1px solid rgba(212, 166, 88, 0.16);
            border-radius: 30px;
            padding: 28px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
        }

        .polani-dashboard {
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        .polani-dashboard__hero {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            padding: 8px 0 4px;
        }

        .polani-dashboard__eyebrow {
            color: #d4a658;
            font-size: 0.78rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .polani-dashboard__headline {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3.25rem);
            color: #f8e7d0;
            margin: 0;
        }

        .polani-dashboard__copy {
            max-width: 760px;
            color: rgba(248, 231, 208, 0.78);
            margin: 14px 0 0;
        }

        .polani-dashboard__pill {
            padding: 18px 20px;
            border-radius: 22px;
            border: 1px solid rgba(212, 166, 88, 0.18);
            background: rgba(255, 255, 255, 0.04);
            color: rgba(248, 231, 208, 0.84);
            min-width: 240px;
        }

        .polani-dashboard__pill span {
            display: block;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            margin-bottom: 6px;
            color: #d4a658;
        }

        .polani-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .polani-stat-card {
            border-radius: 24px;
            padding: 22px;
            min-height: 170px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.18);
        }

        .polani-stat-card__label {
            color: rgba(255, 255, 255, 0.82);
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .polani-stat-card__value {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: 2.3rem;
            line-height: 1;
            margin-top: 10px;
        }

        .polani-stat-card__meta {
            color: rgba(255, 255, 255, 0.84);
            font-size: 0.92rem;
            margin-top: 8px;
        }

        .polani-stat-card__link {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .polani-stat-card--gold { background: linear-gradient(145deg, #2b1c07, #7d5417 60%, #c89a52); }
        .polani-stat-card--emerald { background: linear-gradient(145deg, #101a12, #295b35 60%, #4f8f50); }
        .polani-stat-card--blue { background: linear-gradient(145deg, #0e1623, #2f67a1 60%, #5da2f0); }
        .polani-stat-card--violet { background: linear-gradient(145deg, #17112a, #5b46a9 60%, #8a6fe3); }
        .polani-stat-card--amber { background: linear-gradient(145deg, #231509, #a66414 60%, #f2b556); }
        .polani-stat-card--rose { background: linear-gradient(145deg, #251216, #92334e 60%, #db6b8f); }
        .polani-stat-card--midnight { background: linear-gradient(145deg, #0c1020, #1f2840 60%, #424f7f); }

        .polani-quick-actions {
            margin-top: 8px;
        }

        .polani-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: #f8e7d0;
            margin-bottom: 16px;
        }

        .polani-actions-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .polani-action-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
            min-height: 130px;
            padding: 20px;
            border-radius: 22px;
            text-decoration: none;
            color: #f8e7d0;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(212, 166, 88, 0.16);
            transition: all 0.2s ease;
        }

        .polani-action-card:hover {
            transform: translateY(-4px);
            border-color: rgba(212, 166, 88, 0.35);
            background: rgba(212, 166, 88, 0.08);
            color: #fff;
        }

        .polani-action-card span {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .polani-action-card small {
            color: rgba(248, 231, 208, 0.76);
            font-size: 0.9rem;
        }

        /* Sidebar Toggle Button Styles */
        .polani-admin-toggle-sidebar-btn {
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 166, 88, 0.25);
            color: #d4a658;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.25s ease;
            margin-right: 12px;
        }

        .polani-admin-toggle-sidebar-btn:hover {
            background: rgba(212, 166, 88, 0.15);
            border-color: #d4a658;
            color: #fff;
        }

        /* Backdrop Styles */
        .polani-admin-sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 1099;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .polani-admin-sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        .polani-admin-sidebar__header {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 1100px) {
            .polani-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
            .polani-actions-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 991.98px) {
            .polani-admin-toggle-sidebar-btn {
                display: flex;
            }

            .polani-admin-layout {
                grid-template-columns: 1fr !important;
                min-height: calc(100vh - 75px);
            }

            .polani-admin-sidebar {
                position: fixed !important;
                top: 0;
                left: -295px;
                width: 285px;
                height: 100vh;
                overflow-y: auto;
                z-index: 1100;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 10px 0 35px rgba(0,0,0,0.6);
                background: #0d0d0d !important;
                border-right: 1px solid rgba(212, 166, 88, 0.2) !important;
                padding: 24px 20px !important;
            }

            .polani-admin-sidebar.open {
                left: 0 !important;
            }

            .polani-admin-sidebar__header {
                display: flex !important;
            }

            .polani-admin-main {
                padding: 20px !important;
                width: 100% !important;
            }
        }

        @media (max-width: 767.98px) {
            .polani-admin-topbar {
                padding: 12px 16px !important;
                gap: 12px !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
            }

            .polani-admin-topbar__center {
                display: none !important;
            }

            .polani-admin-brand__word {
                font-size: 1.3rem !important;
                letter-spacing: 0.12em !important;
            }

            .polani-admin-brand__sub {
                font-size: 0.6rem !important;
                letter-spacing: 0.3em !important;
                margin-top: 1px !important;
            }

            .polani-admin-topbar__actions {
                gap: 6px !important;
            }

            .polani-admin-link,
            .polani-admin-logout {
                padding: 8px 12px !important;
                font-size: 0.8rem !important;
                border-radius: 12px !important;
            }

            .polani-admin-pagehead {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 12px !important;
            }

            .polani-admin-title {
                font-size: 1.8rem !important;
            }

            .polani-admin-actions {
                width: 100% !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 8px !important;
            }

            .polani-admin-actions > * {
                flex: 1 1 auto !important;
                text-align: center !important;
            }

            .polani-stats-grid {
                grid-template-columns: 1fr !important;
            }

            .polani-actions-grid {
                grid-template-columns: 1fr !important;
            }

            .polani-dashboard__hero {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 20px !important;
                gap: 16px !important;
            }
            
            .polani-dashboard__pill {
                margin-top: 8px !important;
            }
        }
        /* ═══════════════════════════════════════════
           SHARED PAGE CONTENT THEME CLASSES
           Available on every admin page
        ═══════════════════════════════════════════ */

        /* Buttons */
        .pf-btn-gold {
            background: linear-gradient(135deg, #d4a658, #9d6f20);
            color: #111 !important;
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.04em;
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .pf-btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(212,166,88,0.35); color: #000 !important; }
        .pf-btn-outline {
            background: transparent;
            color: rgba(248,231,208,0.85) !important;
            border: 1px solid rgba(212,166,88,0.3);
            font-size: 0.88rem;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .pf-btn-outline:hover { border-color: #d4a658; background: rgba(212,166,88,0.08); color: #fff !important; }
        .pf-btn-cancel {
            background: rgba(255,255,255,0.05);
            color: rgba(248,231,208,0.7) !important;
            border: 1px solid rgba(255,255,255,0.1);
            font-size: 0.88rem;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .pf-btn-cancel:hover { background: rgba(255,255,255,0.08); color: #fff !important; }
        .pf-btn-edit {
            background: rgba(212,166,88,0.12);
            color: #d4a658;
            border: 1px solid rgba(212,166,88,0.3);
            border-radius: 9px;
            padding: 7px 12px;
            font-size: 0.82rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .pf-btn-edit:hover { background: rgba(212,166,88,0.22); color: #f4d7ab; border-color: #d4a658; }
        .pf-btn-delete {
            background: rgba(220,53,69,0.12);
            color: #f07080;
            border: 1px solid rgba(220,53,69,0.3);
            border-radius: 9px;
            padding: 7px 12px;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .pf-btn-delete:hover { background: rgba(220,53,69,0.22); color: #ff8090; border-color: #dc3545; }
        .pf-btn-view {
            background: rgba(90,160,255,0.12);
            color: #7ab8ff;
            border: 1px solid rgba(90,160,255,0.3);
            border-radius: 9px;
            padding: 7px 12px;
            font-size: 0.82rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .pf-btn-view:hover { background: rgba(90,160,255,0.22); color: #aad4ff; }
        .pf-btn-success {
            background: rgba(79,200,100,0.12);
            color: #5fcf6e;
            border: 1px solid rgba(79,200,100,0.3);
            border-radius: 9px;
            padding: 7px 12px;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .pf-btn-success:hover { background: rgba(79,200,100,0.22); color: #90e09a; }

        /* Form fields */
        .pf-form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .pf-form-label .req { color: #dc2626; }
        .pf-form-label .opt { color: #64748b; font-weight: 500; text-transform: none; letter-spacing: 0; font-size: 0.8rem; }
        .pf-input, .pf-textarea, .pf-select-field {
            width: 100%;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            color: #0f172a;
            padding: 11px 16px;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
            box-sizing: border-box;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .pf-input::placeholder, .pf-textarea::placeholder { color: #94a3b8; }
        .pf-input:focus, .pf-textarea:focus, .pf-select-field:focus { border-color: #d4a658; box-shadow: 0 0 0 3px rgba(212,166,88,0.25); }
        .pf-input.is-invalid, .pf-textarea.is-invalid, .pf-select-field.is-invalid { border-color: #dc2626; }
        .pf-textarea { resize: vertical; min-height: 90px; }
        .pf-select-field option { background: #ffffff; color: #0f172a; }
        .pf-hint { margin-top: 6px; font-size: 0.8rem; color: #64748b; }
        .pf-error { margin-top: 6px; font-size: 0.8rem; color: #dc2626; display: flex; align-items: center; gap: 5px; }
        .pf-field { margin-bottom: 22px; }
        .pf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .pf-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        @media (max-width:640px) { .pf-row, .pf-row-3 { grid-template-columns: 1fr; } }
        .pf-divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .pf-section-head {
            display: flex; align-items: center; gap: 12px; margin: 28px 0 20px;
        }
        .pf-section-head span { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #1e293b; white-space: nowrap; }
        .pf-section-head::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        .pf-form-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-top: 28px; padding-top: 22px; border-top: 1px solid #e2e8f0; }

        /* Toggle switch */
        .pf-switch-wrap { display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; }
        .pf-switch-wrap input[type="checkbox"] { width: 40px; height: 22px; cursor: pointer; accent-color: #d4a658; flex-shrink: 0; }
        .pf-switch-label { font-size: 0.9rem; color: #1e293b; line-height: 1.4; }
        .pf-switch-label strong { color: #0f172a; }

        /* Tables */
        .pf-table-wrap { overflow-x: auto; border-radius: 18px; border: 1px solid #e2e8f0; background: #ffffff; }
        .pf-table { width: 100%; border-collapse: collapse; color: #334155; font-size: 0.92rem; }
        .pf-table thead th { background: #f8fafc; color: #0f172a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.12em; font-weight: 700; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .pf-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
        .pf-table tbody tr:last-child { border-bottom: none; }
        .pf-table tbody tr:hover { background: #f8fafc; }
        .pf-table td { padding: 14px 16px; vertical-align: middle; }
        .pf-table td strong { color: #0f172a; }
        .pf-table td small { color: #64748b; }

        /* Badges */
        .pf-badge-gold { background: #fefce8; color: #a16207; border: 1px solid #fef08a; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 8px; white-space: nowrap; }
        .pf-badge-active { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; }
        .pf-badge-inactive { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; }
        .pf-badge-order { background: #fefce8; color: #a16207; border: 1px solid #fef08a; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
        .pf-badge-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; }
        .pf-badge-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; }
        .pf-badge-blue { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; }

        /* Info/sidebar box */
        .pf-sidebar-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .pf-sidebar-box__head { background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; font-weight: 700; color: #0f172a; letter-spacing: 0.05em; }
        .pf-sidebar-box__body { padding: 20px; color: #334155; font-size: 0.88rem; line-height: 1.65; }
        .pf-sidebar-box__body p { margin-bottom: 10px; }
        .pf-sidebar-box__body strong { color: #0f172a; }
        .pf-sidebar-box__body ul { margin: 6px 0 12px 16px; }
        .pf-meta-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 0.85rem; color: #475569; }
        .pf-meta-row:last-child { border-bottom: none; }
        .pf-meta-row strong { color: #0f172a; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; }

        /* Info note */
        .pf-info-note { padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; color: #475569; font-size: 0.85rem; display: flex; align-items: flex-start; gap: 10px; }
        .pf-info-note i { color: #0f172a; margin-top: 2px; flex-shrink: 0; }
        .pf-info-note--danger { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .pf-info-note--danger i { color: #dc2626; }

        /* Empty state */
        .pf-empty { text-align: center; padding: 60px 20px; color: #64748b; }
        .pf-empty i { font-size: 3rem; margin-bottom: 16px; color: #cbd5e1; display: block; }
        .pf-empty p { margin-bottom: 20px; font-size: 1.05rem; }

        /* Grid layouts */
        .pf-grid-2col { display: grid; grid-template-columns: 1fr 320px; gap: 28px; align-items: start; }
        @media (max-width: 900px) { .pf-grid-2col { grid-template-columns: 1fr; } }

        /* Modals */
        .pf-modal .modal-content { background: #111; border: 1px solid rgba(212,166,88,0.25); border-radius: 18px; color: #f8e7d0; }
        .pf-modal .modal-header { border-bottom: 1px solid rgba(212,166,88,0.15); padding: 18px 24px; }
        .pf-modal .modal-footer { border-top: 1px solid rgba(212,166,88,0.15); padding: 16px 24px; }
        .pf-modal .modal-title { color: #d4a658; font-weight: 700; }
        .pf-modal .modal-body { padding: 20px 24px; }
        .pf-modal .btn-close { filter: invert(1) opacity(0.7); }

        /* Pagination */
        .pf-pagination .page-link { background: rgba(255,255,255,0.05); border-color: rgba(212,166,88,0.2); color: #f8e7d0; border-radius: 8px; margin: 0 2px; }
        .pf-pagination .page-link:hover { background: rgba(212,166,88,0.15); color: #d4a658; border-color: #d4a658; }
        .pf-pagination .page-item.active .page-link { background: linear-gradient(135deg,#d4a658,#9d6f20); border-color: #d4a658; color: #111; }

        /* File input */
        .pf-file-input { width: 100%; background: rgba(255,255,255,0.05); border: 1px dashed rgba(212,166,88,0.3); border-radius: 12px; color: #f8e7d0; padding: 11px 16px; font-size: 0.9rem; cursor: pointer; box-sizing: border-box; transition: border-color 0.2s; }
        .pf-file-input:hover { border-color: #d4a658; }

        /* ==========================================
           ONE STOP MART BRANDING & ACCENTS OVERRIDES
           ========================================== */
        :root {
            --sidebar-bg: #1e293b !important;
            --sidebar-hover: rgba(245, 184, 0, 0.12) !important;
            --sidebar-active: #F5B800 !important;
            --sidebar-text: #cbd5e1 !important;
            --sidebar-text-active: #222222 !important;
            --sidebar-heading: #94a3b8 !important;
        }

        .polani-admin-body {
            background: #F7F7F7 !important;
            color: #222222 !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        }

        .polani-admin-topbar {
            background: #ffffff !important;
            border-bottom: 1px solid #E5E5E5 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02) !important;
        }

        .polani-admin-topbar__text {
            color: #666666 !important;
        }

        .polani-admin-badge {
            background: #F5B800 !important;
            color: #222222 !important;
            border: none !important;
        }

        .polani-admin-link, .polani-admin-logout {
            border: 1px solid #E5E5E5 !important;
            background: #ffffff !important;
            color: #222222 !important;
        }

        .polani-admin-link:hover, .polani-admin-logout:hover {
            background: rgba(245, 184, 0, 0.1) !important;
            color: #222222 !important;
            border-color: #F5B800 !important;
        }

        .polani-admin-sidebar {
            background: #1e293b !important;
            border-right: 1px solid #E5E5E5 !important;
        }

        .polani-admin-sidebar__panel {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .polani-admin-sidebar__panel--light {
            background: rgba(245, 184, 0, 0.08) !important;
            border-color: rgba(245, 184, 0, 0.2) !important;
        }

        .polani-admin-sidebar__panel--light .polani-admin-sidebar__title {
            color: #F5B800 !important;
        }

        .polani-admin-sidebar__info {
            color: #cbd5e1 !important;
        }

        .polani-admin-sidebar__title {
            color: #F5B800 !important;
        }

        .polani-admin-nav {
            color: #cbd5e1 !important;
        }

        .polani-admin-nav:hover {
            background: rgba(245, 184, 0, 0.1) !important;
            color: #ffffff !important;
        }

        .polani-admin-nav.active {
            background: #F5B800 !important;
            color: #222222 !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px rgba(245, 184, 0, 0.25) !important;
        }

        .polani-admin-main {
            background: #F7F7F7 !important;
        }

        .polani-admin-title {
            color: #222222 !important;
        }

        .polani-admin-subtitle {
            color: #666666 !important;
        }

        .polani-admin-card {
            background: #ffffff !important;
            border: 1px solid #E5E5E5 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.02) !important;
            color: #222222 !important;
        }

        .polani-dashboard__hero {
            background: #ffffff !important;
            border: 1px solid #E5E5E5 !important;
            border-radius: 12px !important;
            color: #222222 !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04) !important;
        }

        .polani-dashboard__eyebrow {
            color: #F5B800 !important;
        }

        .polani-dashboard__headline {
            color: #222222 !important;
        }

        .polani-dashboard__copy {
            color: #666666 !important;
        }

        .polani-dashboard__pill {
            background: #F7F7F7 !important;
            border: 1px solid #E5E5E5 !important;
            color: #222222 !important;
        }

        .polani-dashboard__pill strong {
            color: #F5B800 !important;
        }

        /* Stat cards */
        .polani-stat-card {
            border: 1px solid #E5E5E5 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04) !important;
            padding: 20px !important;
            color: #222222 !important;
            background: #ffffff !important;
        }

        .polani-stat-card__label {
            color: #666666 !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
        }

        .polani-stat-card__value {
            color: #222222 !important;
            font-size: 2rem !important;
            font-weight: 800 !important;
            margin: 8px 0 !important;
        }

        .polani-stat-card__meta {
            color: #999999 !important;
        }

        .polani-stat-card__link {
            color: #F5B800 !important;
            font-weight: 600 !important;
            text-decoration: none !important;
        }

        .polani-stat-card__link:hover {
            color: #cca600 !important;
        }

        .polani-stat-card--gold,
        .polani-stat-card--emerald,
        .polani-stat-card--blue,
        .polani-stat-card--violet,
        .polani-stat-card--amber,
        .polani-stat-card--rose,
        .polani-stat-card--midnight {
            background: #ffffff !important;
        }

        .polani-section-title {
            color: #222222 !important;
        }

        .polani-action-card {
            background: #ffffff !important;
            border: 1px solid #E5E5E5 !important;
            border-radius: 12px !important;
            color: #222222 !important;
        }

        .polani-action-card:hover {
            border-color: #F5B800 !important;
            background: rgba(245, 184, 0, 0.05) !important;
        }

        .polani-action-card span {
            color: #222222 !important;
        }

        .polani-action-card small {
            color: #666666 !important;
        }

        /* CRUD Buttons */
        .pf-btn-gold {
            background: #F5B800 !important;
            color: #222222 !important;
            font-weight: 700 !important;
            border: none !important;
        }

        .pf-btn-gold:hover {
            background: #cca600 !important;
            color: #222222 !important;
        }

        .pf-btn-outline {
            border-color: #E5E5E5 !important;
            background: #ffffff !important;
            color: #222222 !important;
        }

        .pf-btn-outline:hover {
            border-color: #F5B800 !important;
            background: rgba(245, 184, 0, 0.05) !important;
            color: #222222 !important;
        }

        .pf-btn-edit {
            background: rgba(245, 184, 0, 0.1) !important;
            color: #222222 !important;
            border-color: rgba(245, 184, 0, 0.3) !important;
        }

        .pf-btn-edit:hover {
            background: #F5B800 !important;
            color: #222222 !important;
            border-color: #F5B800 !important;
        }

        .pf-btn-delete {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
            border-color: rgba(220, 53, 69, 0.3) !important;
        }

        .pf-btn-delete:hover {
            background: #dc3545 !important;
            color: #ffffff !important;
            border-color: #dc3545 !important;
        }

        /* Inputs */
        .pf-input, .pf-textarea, .pf-select-field {
            background: #ffffff !important;
            border: 1px solid #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-input:focus, .pf-textarea:focus, .pf-select-field:focus {
            border-color: #F5B800 !important;
            box-shadow: 0 0 0 3px rgba(245, 184, 0, 0.15) !important;
        }

        /* Table overrides */
        .pf-table-wrap {
            border: 1px solid #E5E5E5 !important;
            border-radius: 12px !important;
            background: #ffffff !important;
        }

        .pf-table thead th {
            background: #F7F7F7 !important;
            color: #222222 !important;
            border-bottom: 1px solid #E5E5E5 !important;
        }

        .pf-table tbody tr {
            border-bottom: 1px solid #E5E5E5 !important;
            background: #ffffff !important;
            color: #222222 !important;
        }

        .pf-table tbody tr:hover {
            background: #F7F7F7 !important;
        }

        .pf-table tbody td {
            color: #222222 !important;
        }

        /* Badges */
        .pf-badge-gold {
            background: rgba(245, 184, 0, 0.1) !important;
            color: #222222 !important;
            border: 1px solid rgba(245, 184, 0, 0.3) !important;
        }

        .pf-badge-order {
            background: rgba(245, 184, 0, 0.1) !important;
            color: #222222 !important;
            border: 1px solid rgba(245, 184, 0, 0.3) !important;
        }

        .pf-badge-green {
            background: rgba(40, 167, 69, 0.1) !important;
            color: #28a745 !important;
            border: 1px solid rgba(40, 167, 69, 0.3) !important;
        }

        .pf-badge-red {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
            border: 1px solid rgba(220, 53, 69, 0.3) !important;
        }

        /* Sidebar boxes */
        .pf-sidebar-box {
            background: #ffffff !important;
            border: 1px solid #E5E5E5 !important;
            border-radius: 12px !important;
        }

        .pf-sidebar-box__head {
            background: #F7F7F7 !important;
            border-bottom: 1px solid #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-sidebar-box__body {
            color: #222222 !important;
        }

        .pf-sidebar-box__body strong {
            color: #F5B800 !important;
        }

        .pf-meta-row {
            border-bottom: 1px solid #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-meta-row strong {
            color: #222222 !important;
        }

        .pf-divider {
            border-top: 1px solid #E5E5E5 !important;
        }

        .pf-section-head::after {
            background: #E5E5E5 !important;
        }

        .pf-section-head span {
            color: #666666 !important;
        }

        .pf-form-actions {
            border-top: 1px solid #E5E5E5 !important;
        }

        .pf-switch-wrap {
            background: #F7F7F7 !important;
            border: 1px solid #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-switch-label strong {
            color: #F5B800 !important;
        }

        .pf-switch-wrap input[type="checkbox"] {
            accent-color: #F5B800 !important;
        }

        .pf-info-note {
            background: rgba(245, 184, 0, 0.05) !important;
            border: 1px solid rgba(245, 184, 0, 0.15) !important;
            color: #666666 !important;
        }

        .pf-info-note i {
            color: #F5B800 !important;
        }

        .pf-modal .modal-content {
            background: #ffffff !important;
            border: 1px solid #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-modal .modal-header {
            border-bottom: 1px solid #E5E5E5 !important;
        }

        .pf-modal .modal-footer {
            border-top: 1px solid #E5E5E5 !important;
        }

        .pf-modal .modal-title {
            color: #222222 !important;
        }

        .pf-modal .btn-close {
            filter: none !important;
        }

        .pf-pagination .page-link {
            background: #ffffff !important;
            border-color: #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-pagination .page-link:hover {
            background: rgba(245, 184, 0, 0.1) !important;
            color: #222222 !important;
            border-color: #F5B800 !important;
        }

        .pf-pagination .page-item.active .page-link {
            background: #F5B800 !important;
            border-color: #F5B800 !important;
            color: #222222 !important;
        }

        .pf-file-input {
            background: #ffffff !important;
            border: 1px dashed #E5E5E5 !important;
            color: #222222 !important;
        }

        .pf-file-input:hover {
            border-color: #F5B800 !important;
        }
        
        .alert-success {
            background: #d4edda !important;
            border-color: #c3e6cb !important;
            color: #155724 !important;
        }
        .alert-danger {
            background: #f8d7da !important;
            border-color: #f5c6cb !important;
            color: #721c24 !important;
        }
    </style>
</head>
<body class="polani-admin-body">
    <div class="polani-admin-shell">
        <header class="polani-admin-topbar">
            <div style="display: flex; align-items: center;">
                <button type="button" class="polani-admin-toggle-sidebar-btn" id="polaniAdminToggleSidebar" aria-label="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="polani-admin-brand" href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 10px; flex-direction: row; text-decoration: none;">
                    <img src="{{ asset('one-stop-mart/images/branding/one-stop-mart-logo-admin.png') }}" alt="One Stop Mart" class="osm-admin-logo" style="max-height: 55px; width: auto; object-fit: contain;">
                </a>
            </div>

            <div class="polani-admin-topbar__center">
                <span class="polani-admin-badge">Admin Panel</span>
                <span class="polani-admin-topbar__text">Storefront catalog and orders management</span>
            </div>

            <div class="polani-admin-topbar__actions">
                <a class="polani-admin-link" href="{{ route('home') }}" target="_blank" rel="noopener">View Store</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="polani-admin-logout">Logout</button>
                </form>
            </div>
        </header>

        <div class="polani-admin-layout">
            <div class="polani-admin-sidebar-backdrop" id="polaniAdminSidebarBackdrop"></div>
            <aside class="polani-admin-sidebar">
                <div class="polani-admin-sidebar__header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(212,166,88,0.14); padding-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <img src="{{ asset('one-stop-mart/images/branding/one-stop-mart-emblem.png') }}" alt="OSM" class="osm-admin-logo--collapsed" style="height: 24px; width: auto; object-fit: contain;">
                        <span style="color: #d4a658; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.15em; text-transform: uppercase;">Navigation</span>
                    </div>
                    <button type="button" class="polani-admin-close-sidebar-btn" id="polaniAdminCloseSidebar" style="background: transparent; border: none; color: #f8e7d0; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(212,166,88,0.25);">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="polani-admin-sidebar__panel">
                    <div class="polani-admin-sidebar__title">Management</div>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Overview</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">Products</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders') }}">Orders</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">Coupons</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.banners.*') || request()->routeIs('admin.carousel.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">Banner Management</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}" href="{{ route('admin.payment-methods.index') }}">Payment Methods</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.footer.*') ? 'active' : '' }}" href="{{ route('admin.footer.index') }}">Footer Settings</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">Blog Management</a>
                    <a class="polani-admin-nav {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">Reviews</a>
                </div>

                <div class="polani-admin-sidebar__panel polani-admin-sidebar__panel--light">
                    <div class="polani-admin-sidebar__title">Account</div>
                    <div class="polani-admin-sidebar__info">{{ Auth::user()->name }}</div>
                    <div class="polani-admin-sidebar__info">{{ Auth::user()->email }}</div>
                    <div class="polani-admin-sidebar__info">Admin</div>
                </div>
            </aside>

            <main class="polani-admin-main">
                <div class="polani-admin-pagehead">
                    <div>
                        <h1 class="polani-admin-title">@yield('header', 'Dashboard')</h1>
                        <div class="polani-admin-subtitle">Manage One Stop Mart products, orders and store settings.</div>
                    </div>
                    <div class="polani-admin-actions">
                        @yield('actions')
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm polani-admin-alert" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm polani-admin-alert" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="polani-admin-card">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Livewire Scripts (All admin pages require Livewire for interactive features) -->
    @livewireScripts

    <!-- Event Handler CSP Fixer (Minified) -->
    <script src="{{ asset('js/event-handler-fixer.min.js') }}"></script>
    <!-- Dynamic Styles Handler (Minified) -->
    <script src="{{ asset('js/dynamic-styles-handler.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('polaniAdminToggleSidebar');
            const closeBtn = document.getElementById('polaniAdminCloseSidebar');
            const sidebar = document.querySelector('.polani-admin-sidebar');
            const backdrop = document.getElementById('polaniAdminSidebarBackdrop');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('open');
                if (backdrop) backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (sidebar && sidebar.classList.contains('open')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeSidebar);
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking links inside it on mobile
            const navLinks = document.querySelectorAll('.polani-admin-nav');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
