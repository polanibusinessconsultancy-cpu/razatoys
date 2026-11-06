@extends('polani.layout')

@section('title', 'About Us — RazaToys')
@section('meta_description', 'RazaToys is your premium online general store offering quality products across toys, kitchenware, electronics, fashion, sports, home and everyday essentials.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/about.css') }}">
@endpush

@section('content')
<main class="about-page">
    <!-- Breadcrumb & Title -->
    <section class="about-hero" style="padding: 20px 0; background: var(--color-bg-light);">
        <div class="container">
            <nav class="breadcrumb" id="breadcrumb" aria-label="Breadcrumb" style="display: flex; gap: 8px; font-size: 0.9rem; color: var(--color-text-muted);">
                <a href="{{ route('home') }}" class="breadcrumb__link" style="color: var(--color-text-muted); text-decoration: none;">Home</a>
                <span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current" style="color: var(--color-secondary); font-weight: bold;">About Us</span>
            </nav>
        </div>
    </section>

    <!-- Main Support Banner -->
    <section class="about-banner-sec" style="padding: 10px 0 40px; background: var(--color-bg-light);">
        <div class="container">
            <div class="about-banner" style="display: flex; align-items: center; justify-content: space-between; gap: 40px; flex-wrap: wrap; background: linear-gradient(135deg, #FAF7F0 0%, #F4EFE6 100%); padding: 36px 44px; border-radius: 20px; border: 1px solid #EAE5D9; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                <div class="about-banner__left" style="flex: 1.2; min-width: 300px;">
                    <span class="about-banner__subtitle" style="display: inline-block; background: rgba(197, 146, 69, 0.14); color: #8C6B2D; border: 1px solid rgba(197, 146, 69, 0.3); font-size: 0.76rem; font-weight: 700; padding: 4px 14px; border-radius: 30px; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.08em;">ABOUT RazaToys</span>
                    <h1 class="about-banner__heading" style="font-size: 2.4rem; font-weight: 800; color: #111111; margin: 0 0 12px; line-height: 1.25;">Quality Products.<br><span class="highlight" style="color: #C59245;">Better Everyday.</span></h1>
                    <p class="about-banner__desc" style="font-size: 1.02rem; color: #4A4A4A; line-height: 1.6; margin-bottom: 24px; max-width: 520px;">RazaToys is your trusted destination for quality products across every part of life. We bring everything you need, all in one place.</p>
                    <a href="{{ route('polani.collection') }}" class="about-banner__btn" style="display: inline-flex; align-items: center; background: #111111; color: #fff; font-weight: 700; padding: 12px 26px; border-radius: 12px; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease;">
                        Shop Now
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="margin-left: 8px;"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
                <div class="about-banner__right" style="flex: 0.8; min-width: 250px; display: flex; justify-content: center;">
                    <div style="background: #ffffff; padding: 32px 28px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.04); text-align: center; border: 1px solid #EAE5D9; max-width: 320px; width: 100%;">
                        <div style="font-size: 3.2rem; margin-bottom: 12px; color: #C59245;">🛒</div>
                        <h3 style="margin: 0 0 8px 0; color: #111111; font-size: 1.25rem; font-weight: 800;">RazaToys</h3>
                        <p style="margin: 0; font-size: 0.85rem; color: #666666;">Est. 2026 • Verified Premium Quality</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="about-story-sec" style="padding: 60px 0; background: var(--color-bg-light);">
        <div class="container">
            <div class="about-story-grid" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                <div class="about-story__left" style="flex: 1; min-width: 300px; display: flex; justify-content: center;">
                    <div style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%); color: var(--color-secondary); padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); text-align: center; max-width: 360px; width: 100%;">
                        <div style="font-size: 4rem; margin-bottom: 20px;">📦</div>
                        <h3 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 10px 0;">Global Logistics</h3>
                        <p style="font-size: 0.9rem; line-height: 1.5; margin: 0; opacity: 0.9;">Securely warehousing and shipping thousands of orders daily to homes nationwide.</p>
                    </div>
                </div>
                <div class="about-story__right" style="flex: 1.2; min-width: 300px;">
                    <span class="about-story__subtitle" style="font-weight: bold; color: var(--color-primary-dark); font-size: 0.9rem; text-transform: uppercase; tracking: 1px;">OUR STORY</span>
                    <h2 class="about-story__heading" style="font-size: 2.2rem; font-weight: 800; color: var(--color-secondary); margin: 8px 0 16px; line-height: 1.3;">Built with a Simple Idea –<br>To Make Life Easier</h2>
                    <p class="about-story__desc" style="font-size: 1rem; color: var(--color-text-light); line-height: 1.6; margin-bottom: 16px;">RazaToys was founded with a simple idea: to bring all your daily needs together in one convenient place. From household essentials to the latest trends, we curate a wide range of quality products at the best prices.</p>
                    <p class="about-story__desc" style="font-size: 1rem; color: var(--color-text-light); line-height: 1.6; margin-bottom: 24px;">We are committed to exceptional service, fast delivery, and a seamless shopping experience you can trust.</p>
                    
                    <div class="about-story-badges" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div class="story-badge" style="display: flex; gap: 12px; align-items: flex-start;">
                            <div class="story-badge__icon" style="background: rgba(255,193,7,0.15); padding: 8px; border-radius: 6px; color: var(--color-primary-dark); display: flex;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="story-badge__text">
                                <strong style="display: block; color: var(--color-secondary); font-size: 0.95rem;">Quality Products</strong>
                                <span style="font-size: 0.85rem; color: var(--color-text-muted);">Carefully selected for you</span>
                            </div>
                        </div>

                        <div class="story-badge" style="display: flex; gap: 12px; align-items: flex-start;">
                            <div class="story-badge__icon" style="background: rgba(255,193,7,0.15); padding: 8px; border-radius: 6px; color: var(--color-primary-dark); display: flex;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"/><line x1="12" y1="4" x2="12" y2="20"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                            </div>
                            <div class="story-badge__text">
                                <strong style="display: block; color: var(--color-secondary); font-size: 0.95rem;">Affordable Prices</strong>
                                <span style="font-size: 0.85rem; color: var(--color-text-muted);">Best value for every budget</span>
                            </div>
                        </div>

                        <div class="story-badge" style="display: flex; gap: 12px; align-items: flex-start;">
                            <div class="story-badge__icon" style="background: rgba(255,193,7,0.15); padding: 8px; border-radius: 6px; color: var(--color-primary-dark); display: flex;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            </div>
                            <div class="story-badge__text">
                                <strong style="display: block; color: var(--color-secondary); font-size: 0.95rem;">Fast Delivery</strong>
                                <span style="font-size: 0.85rem; color: var(--color-text-muted);">Quick &amp; reliable delivery</span>
                            </div>
                        </div>

                        <div class="story-badge" style="display: flex; gap: 12px; align-items: flex-start;">
                            <div class="story-badge__icon" style="background: rgba(255,193,7,0.15); padding: 8px; border-radius: 6px; color: var(--color-primary-dark); display: flex;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div class="story-badge__text">
                                <strong style="display: block; color: var(--color-secondary); font-size: 0.95rem;">Customer First</strong>
                                <span style="font-size: 0.85rem; color: var(--color-text-muted);">We're here for you always</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="about-values-sec" style="padding: 60px 0; background: var(--color-white);">
        <div class="container">
            <div class="section-title-wrap" style="text-align: center; margin-bottom: 40px;">
                <span class="about-values__subtitle" style="font-weight: bold; color: var(--color-primary-dark); font-size: 0.9rem; text-transform: uppercase;">OUR VALUES</span>
                <h2 class="about-values__heading" style="font-size: 2.2rem; font-weight: 800; color: var(--color-secondary); margin-top: 8px;">The Values That Drive Us</h2>
            </div>
            
            <div class="values-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px;">
                <!-- Value 1: Trust & Integrity -->
                <div class="value-card" style="background: var(--color-bg-light); padding: 30px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
                    <div class="value-card__icon-wrap" style="color: var(--color-primary-dark); margin-bottom: 16px; display: inline-flex; background: #fff; padding: 12px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    </div>
                    <h3 class="value-card__title" style="font-size: 1.15rem; font-weight: bold; color: var(--color-secondary); margin-bottom: 8px;">Trust &amp; Integrity</h3>
                    <p class="value-card__desc" style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5; margin: 0;">We believe in honest communication and transparent service.</p>
                </div>

                <!-- Value 2: Quality First -->
                <div class="value-card" style="background: var(--color-bg-light); padding: 30px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
                    <div class="value-card__icon-wrap" style="color: var(--color-primary-dark); margin-bottom: 16px; display: inline-flex; background: #fff; padding: 12px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    </div>
                    <h3 class="value-card__title" style="font-size: 1.15rem; font-weight: bold; color: var(--color-secondary); margin-bottom: 8px;">Quality First</h3>
                    <p class="value-card__desc" style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5; margin: 0;">We never compromise on quality - because you deserve the best.</p>
                </div>

                <!-- Value 3: Customer Focused -->
                <div class="value-card" style="background: var(--color-bg-light); padding: 30px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
                    <div class="value-card__icon-wrap" style="color: var(--color-primary-dark); margin-bottom: 16px; display: inline-flex; background: #fff; padding: 12px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="value-card__title" style="font-size: 1.15rem; font-weight: bold; color: var(--color-secondary); margin-bottom: 8px;">Customer Focused</h3>
                    <p class="value-card__desc" style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5; margin: 0;">Our customers are at the heart of everything we do.</p>
                </div>

                <!-- Value 4: Convenience -->
                <div class="value-card" style="background: var(--color-bg-light); padding: 30px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
                    <div class="value-card__icon-wrap" style="color: var(--color-primary-dark); margin-bottom: 16px; display: inline-flex; background: #fff; padding: 12px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <h3 class="value-card__title" style="font-size: 1.15rem; font-weight: bold; color: var(--color-secondary); margin-bottom: 8px;">Convenience</h3>
                    <p class="value-card__desc" style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5; margin: 0;">Everything you need, all in one place, effortlessly.</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
