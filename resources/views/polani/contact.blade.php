@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
  $whatsappPhone = !empty($footer->phone) ? preg_replace('/\D+/', '', $footer->phone) : '';
  
  $hasPhone = !empty($footer->phone);
  $hasEmail = !empty($footer->email);
  $hasAddress = !empty($footer->address);
  $hasWhatsApp = !empty($whatsappPhone);
@endphp

@section('title', 'Contact Us — RazaToys')
@section('meta_description', 'Have questions? Get in touch with the RazaToys customer support team.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/contact.css') }}" />
<style>
  .one-stop-mart-storefront .osm-contact-info-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius-lg);
    padding: 30px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  
  .one-stop-mart-storefront .osm-contact-row {
    display: flex;
    gap: 15px;
    align-items: flex-start;
    margin-bottom: 24px;
  }
  
  .one-stop-mart-storefront .osm-contact-icon-box {
    background: rgba(255, 193, 7, 0.15);
    color: var(--color-primary-dark);
    padding: 10px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .one-stop-mart-storefront .osm-contact-details h3 {
    font-size: var(--font-size-sm);
    font-weight: 700;
    color: var(--color-secondary);
    margin: 0 0 4px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .one-stop-mart-storefront .osm-contact-details p {
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
    margin: 0;
    line-height: 1.5;
  }

  .one-stop-mart-storefront .osm-quick-links-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 30px;
  }

  .one-stop-mart-storefront .osm-quick-link-card {
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    padding: 12px 15px;
    text-decoration: none;
    color: var(--color-secondary);
    background: var(--color-bg-light);
    transition: var(--transition);
    text-align: center;
    font-weight: 600;
    font-size: var(--font-size-sm);
  }

  .one-stop-mart-storefront .osm-quick-link-card:hover {
    border-color: var(--color-primary-dark);
    background: #fff;
    color: var(--color-primary-dark);
  }

  .one-stop-mart-storefront .form-input-control {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    outline: none;
    background: var(--color-white);
    color: var(--color-text);
    transition: border-color 0.2s ease;
  }

  .one-stop-mart-storefront .form-input-control:focus {
    border-color: var(--color-secondary);
    box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.08);
  }

  .one-stop-mart-storefront .form-input-control.is-invalid {
    border-color: #dc3545;
  }

  .one-stop-mart-storefront .form-error-msg {
    color: #dc3545;
    font-size: 0.82rem;
    font-weight: 600;
    margin-top: 4px;
    display: block;
  }
</style>
@endpush

@section('content')
<div class="osm-contact-page">
  
  <!-- ============ BREADCRUMB & HERO ============ -->
  <section class="contact-hero" style="padding: 20px 0 10px; background: var(--color-bg-light);">
    <div class="container">
      <nav class="breadcrumb" id="breadcrumb" aria-label="Breadcrumb" style="display: flex; gap: 8px; font-size: 0.88rem; color: var(--color-text-muted); margin-bottom: 8px;">
        <a href="{{ route('home') }}" class="breadcrumb__link" style="color: var(--color-text-muted); text-decoration: none;">Home</a>
        <span class="breadcrumb__sep">›</span>
        <span class="breadcrumb__current" style="color: var(--color-secondary); font-weight: 700;">Contact Us</span>
      </nav>
    </div>
  </section>

  <!-- ============ INTRO BANNER ============ -->
  <section class="contact-banner-sec" style="padding: 10px 0 30px; background: var(--color-bg-light);">
    <div class="container">
      <div class="contact-banner" style="background: linear-gradient(135deg, #FAF7F0 0%, #F4EFE6 100%); padding: 32px 36px; border-radius: 18px; border: 1px solid #EAE5D9; text-align: center; max-width: 920px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <span class="contact-banner__subtitle" style="display: inline-block; background: rgba(197, 146, 69, 0.14); color: #8C6B2D; border: 1px solid rgba(197, 146, 69, 0.3); font-size: 0.76rem; font-weight: 700; padding: 4px 14px; border-radius: 30px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.08em;">Customer Support</span>
        <h1 class="contact-banner__heading" style="font-size: 2.2rem; font-weight: 800; color: #111111; margin: 0 0 10px; line-height: 1.2;">We’re Here To Help You</h1>
        <p class="contact-banner__desc" style="font-size: 1rem; color: #4A4A4A; max-width: 620px; margin: 0 auto; line-height: 1.6;">Have a question about our catalog, orders, or delivery policies? Reach out to us using the options below.</p>
      </div>
    </div>
  </section>

  <!-- ============ TWO-COLUMN LAYOUT ============ -->
  <section class="contact-main" style="padding: 40px 0 60px; background: var(--color-bg-light);">
    <div class="container">
      <div class="contact-main__grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; align-items: stretch; max-width: 1000px; margin: 0 auto;">
        
        <!-- Left Column: Dynamic Contact info & links -->
        <div>
          <div class="osm-contact-info-card">
            <div>
              <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--color-secondary); margin-top: 0; margin-bottom: 15px;">Support & Details</h2>
              <p style="font-size: 0.95rem; color: var(--color-text-muted); line-height: 1.6; margin-bottom: 30px;">
                Use the following customer care channels to get in touch with our team. We verify all inquiries and respond promptly during store operating hours.
              </p>

              @if($hasEmail)
                <div class="osm-contact-row">
                  <div class="osm-contact-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                  </div>
                  <div class="osm-contact-details">
                    <h3>Email Us</h3>
                    <p><a href="mailto:{{ $footer->email }}" style="color: inherit; text-decoration: none; font-weight: 600;">{{ $footer->email }}</a></p>
                  </div>
                </div>
              @endif

              @if($hasPhone)
                <div class="osm-contact-row">
                  <div class="osm-contact-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  </div>
                  <div class="osm-contact-details">
                    <h3>Call Us</h3>
                    <p><a href="tel:{{ $footer->phone }}" style="color: inherit; text-decoration: none; font-weight: 600;">{{ $footer->phone }}</a></p>
                  </div>
                </div>
              @endif

              @if($hasAddress)
                <div class="osm-contact-row">
                  <div class="osm-contact-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  </div>
                  <div class="osm-contact-details">
                    <h3>Visit Us</h3>
                    <p>{{ $footer->address }}</p>
                  </div>
                </div>
              @endif

              <div class="osm-contact-row">
                <div class="osm-contact-icon-box">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="osm-contact-details">
                  <h3>Business Hours</h3>
                  <p>Mon – Sat: 10:00 AM – 8:00 PM (PKT)</p>
                </div>
              </div>

              @if($hasWhatsApp)
                <div class="osm-contact-row" style="margin-bottom: 0;">
                  <div class="osm-contact-icon-box" style="background: rgba(37, 211, 102, 0.15); color: #25d366;">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 2.509 1.405 4.549 1.406 5.489 0 9.954-4.467 9.957-9.96.002-2.64-1.019-5.123-2.877-6.983-1.859-1.861-4.343-2.883-6.985-2.884-5.496 0-9.96 4.464-9.963 9.956-.001 2.032.545 3.016 1.492 4.604l-.99 3.616 3.768-.989z"/></svg>
                  </div>
                  <div class="osm-contact-details">
                    <h3>WhatsApp Chat</h3>
                    <p><a href="https://wa.me/{{ $whatsappPhone }}?text={{ rawurlencode('Hello RazaToys, I have a customer support inquiry.') }}" target="_blank" rel="noopener noreferrer" style="color: #25d366; font-weight: bold; text-decoration: underline;">Chat on WhatsApp</a></p>
                  </div>
                </div>
              @endif
            </div>

            <!-- Helpful Navigation Links -->
            <div class="osm-quick-links-grid">
              <a href="{{ route('polani.track-order') }}" class="osm-quick-link-card">Track Order</a>
              <a href="{{ route('polani.faq') }}" class="osm-quick-link-card">FAQs</a>
              <a href="{{ route('polani.collection') }}" class="osm-quick-link-card" style="grid-column: span 2;">Browse All Products</a>
            </div>
          </div>
        </div>

        <!-- Right Column: Production Contact Form -->
        <div>
          <div class="osm-contact-info-card" style="justify-content: flex-start;">
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--color-secondary); margin-top: 0; margin-bottom: 20px;">Send a Message</h2>
            
            @if(session('success'))
              <div class="osm-alert osm-alert--success" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 14px 18px; border-radius: 6px; margin-bottom: 24px; font-size: 0.95rem; font-weight: 600; line-height: 1.5;">
                {{ session('success') }}
              </div>
            @endif

            <form action="{{ route('polani.contact.submit') }}" method="POST" class="contact-form" style="display: flex; flex-direction: column; gap: 18px; width: 100%;">
              @csrf
              
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                  <label for="contact-name" style="font-size: 0.9rem; font-weight: bold; color: var(--color-secondary);">Full Name <span style="color: #dc3545;">*</span></label>
                  <input 
                    type="text" 
                    id="contact-name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="Enter your full name" 
                    required 
                    class="form-input-control @error('name') is-invalid @enderror"
                  >
                  @error('name')
                    <span class="form-error-msg">{{ $message }}</span>
                  @enderror
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 6px;">
                  <label for="contact-email" style="font-size: 0.9rem; font-weight: bold; color: var(--color-secondary);">Email Address <span style="color: #dc3545;">*</span></label>
                  <input 
                    type="email" 
                    id="contact-email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Enter your email address" 
                    required 
                    class="form-input-control @error('email') is-invalid @enderror"
                  >
                  @error('email')
                    <span class="form-error-msg">{{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="contact-subject" style="font-size: 0.9rem; font-weight: bold; color: var(--color-secondary);">Subject <span style="color: #dc3545;">*</span></label>
                <input 
                  type="text" 
                  id="contact-subject" 
                  name="subject" 
                  value="{{ old('subject') }}"
                  placeholder="Subject of your message" 
                  required 
                  class="form-input-control @error('subject') is-invalid @enderror"
                >
                @error('subject')
                  <span class="form-error-msg">{{ $message }}</span>
                @enderror
              </div>

              <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="contact-message" style="font-size: 0.9rem; font-weight: bold; color: var(--color-secondary);">Message <span style="color: #dc3545;">*</span></label>
                <textarea 
                  id="contact-message" 
                  name="message" 
                  rows="5" 
                  placeholder="How can we help you today?" 
                  required 
                  class="form-input-control @error('message') is-invalid @enderror"
                  style="resize: vertical;"
                >{{ old('message') }}</textarea>
                @error('message')
                  <span class="form-error-msg">{{ $message }}</span>
                @enderror
              </div>

              <button 
                type="submit" 
                style="background: var(--color-secondary); color: #fff; font-weight: bold; border: none; padding: 14px 28px; border-radius: 6px; font-size: 0.95rem; cursor: pointer; transition: var(--transition); text-align: center; width: 100%; min-height: 48px; margin-top: 6px;"
                onmouseover="this.style.background='var(--color-primary-dark)'; this.style.color='var(--color-secondary)'"
                onmouseout="this.style.background='var(--color-secondary)'; this.style.color='#fff'"
              >
                Send Message
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
  
</div>
@endsection
