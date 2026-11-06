@extends('polani.layout')

@section('title', 'Sign In — One Stop Mart')
@section('meta_description', 'Sign in to your One Stop Mart customer account to track orders and manage shopping details.')

@push('styles')
<style>
  /* Scoped auth styling for One Stop Mart */
  .osm-auth-page {
    background-color: var(--color-bg-light);
    padding: 60px 0;
    min-height: calc(100vh - 400px);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .osm-auth-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    padding: 40px;
    max-width: 480px;
    width: 100%;
    margin: 0 auto;
  }
  
  .osm-auth-header {
    text-align: center;
    margin-bottom: 30px;
  }
  
  .osm-auth-logo {
    display: block;
    margin: 0 auto 15px auto;
    max-width: 200px;
    height: auto;
  }
  
  .osm-auth-eyebrow {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--color-primary-dark);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
    display: block;
  }
  
  .osm-auth-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--color-secondary);
    margin: 0 0 8px 0;
  }

  .osm-auth-subtitle {
    font-size: 0.9rem;
    color: var(--color-text-muted);
    margin: 0;
  }

  .osm-auth-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .osm-auth-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .osm-auth-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--color-secondary);
  }

  .osm-auth-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    font-size: 0.95rem;
    outline: none;
    background: var(--color-white);
    color: var(--color-text);
  }

  .osm-auth-input:focus {
    border-color: var(--color-primary-dark);
  }

  .osm-auth-error {
    font-size: 0.8rem;
    color: var(--color-red);
    margin-top: 4px;
    font-weight: 600;
  }

  .osm-auth-row-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
  }

  .osm-auth-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: var(--color-text-light);
  }

  .osm-auth-checkbox input {
    cursor: pointer;
  }

  .osm-auth-link {
    color: var(--color-primary-dark);
    text-decoration: underline;
    font-weight: 600;
  }

  .osm-auth-link:hover {
    color: var(--color-secondary);
  }

  .osm-auth-submit-btn {
    background-color: var(--color-secondary);
    color: var(--color-white);
    padding: 12px;
    border-radius: var(--border-radius);
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-align: center;
    transition: var(--transition);
  }

  .osm-auth-submit-btn:hover {
    background-color: var(--color-primary);
    color: var(--color-secondary);
  }

  .osm-auth-divider {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 10px 0;
  }

  .osm-auth-divider-line {
    flex: 1;
    height: 1px;
    background-color: var(--color-border);
  }

  .osm-auth-divider-text {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .osm-auth-google-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background-color: var(--color-white);
    color: var(--color-secondary);
    border: 1px solid var(--color-border);
    padding: 11px;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
  }

  .osm-auth-google-btn:hover {
    background-color: var(--color-bg-light);
    border-color: var(--color-text-muted);
  }

  .osm-auth-footer {
    text-align: center;
    margin-top: 25px;
    font-size: 0.9rem;
    color: var(--color-text-light);
  }
</style>
@endpush

@section('content')
<div class="osm-auth-page">
  <div class="osm-auth-card">
    
    <!-- Auth Header -->
    <div class="osm-auth-header">
      <div class="osm-auth-logo">
        <img src="{{ asset('one-stop-mart/images/branding/one-stop-mart-logo-auth.png') }}" alt="One Stop Mart" class="osm-auth-logo-img" style="max-width: 200px; height: auto;">
      </div>
      <span class="osm-auth-eyebrow">Welcome Back</span>
      <h1 class="osm-auth-title">Sign In</h1>
      <p class="osm-auth-subtitle">Sign in to access your One Stop Mart account details.</p>
    </div>

    <!-- Alert bags -->
    @if (session('status'))
      <div style="background: rgba(40,167,69,0.1); border: 1px solid rgba(40,167,69,0.2); padding: 12px; border-radius: 6px; color: #1e7e34; font-size: 0.85rem; margin-bottom: 20px; font-weight: 600;">
        {{ session('status') }}
      </div>
    @endif

    @if (session('warning'))
      <div style="background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.2); padding: 12px; border-radius: 6px; color: #856404; font-size: 0.85rem; margin-bottom: 20px; font-weight: 600;">
        {{ session('warning') }}
      </div>
    @endif

    @if (session('error'))
      <div style="background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); padding: 12px; border-radius: 6px; color: #bd2130; font-size: 0.85rem; margin-bottom: 20px; font-weight: 600;">
        {{ session('error') }}
      </div>
    @endif

    <!-- Authentication Form -->
    <form class="osm-auth-form" method="POST" action="{{ url('/sign-in') }}">
      @csrf

      <!-- Email Field -->
      <div class="osm-auth-field">
        <label for="signin-email" class="osm-auth-label">Email Address</label>
        <input 
          type="email" 
          name="email" 
          id="signin-email" 
          value="{{ old('email') }}" 
          class="osm-auth-input" 
          placeholder="e.g. name@domain.com"
          required 
          autofocus
        >
        @error('email') 
          <div class="osm-auth-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="margin-right: 4px; vertical-align: text-bottom;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </div> 
        @enderror
      </div>

      <!-- Password Field with Accessible show/hide control -->
      <div class="osm-auth-field">
        <label for="signin-password" class="osm-auth-label">Password</label>
        <div style="position: relative; width: 100%;">
          <input 
            type="password" 
            name="password" 
            id="signin-password" 
            class="osm-auth-input" 
            placeholder="Enter password"
            required
            style="padding-right: 65px;"
          >
          <button 
            type="button" 
            id="password-toggle" 
            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--color-primary-dark); font-weight: 700; font-size: 0.8rem; text-transform: uppercase;" 
            aria-label="Show password"
          >
            Show
          </button>
        </div>
        @error('password') 
          <div class="osm-auth-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="margin-right: 4px; vertical-align: text-bottom;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </div> 
        @enderror
      </div>

      <!-- Checkbox & Forgot Password -->
      <div class="osm-auth-row-between">
        <label class="osm-auth-checkbox">
          <input type="checkbox" name="remember" value="1">
          <span>Remember me</span>
        </label>
        <a class="osm-auth-link" href="{{ route('password.request') }}">Forgot password?</a>
      </div>

      <!-- Submit Button -->
      <button class="osm-auth-submit-btn" type="submit">Sign In</button>

      <!-- Divider -->
      <div class="osm-auth-divider">
        <span class="osm-auth-divider-line"></span>
        <span class="osm-auth-divider-text">or</span>
        <span class="osm-auth-divider-line"></span>
      </div>

      <!-- Google Login Button -->
      <a class="osm-auth-google-btn" href="{{ route('google.redirect') }}">
        <svg viewBox="0 0 24 24" width="18" height="18">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
        </svg>
        Continue with Google
      </a>

      <!-- Link back to shop/home -->
      <div style="text-align: center; margin-top: 5px;">
        <a class="osm-auth-link" href="{{ route('home') }}" style="font-size: 0.85rem; font-weight: normal;">&larr; Back to home page</a>
      </div>
    </form>

    <!-- Card Footer -->
    <div class="osm-auth-footer">
      Don’t have an account?
      <a class="osm-auth-link" href="{{ route('sign-up') }}">Create account</a>
    </div>

  </div>
</div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('password-toggle');
      const passwordField = document.getElementById('signin-password');

      if (toggleBtn && passwordField) {
        toggleBtn.addEventListener('click', function () {
          if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleBtn.textContent = 'Hide';
            toggleBtn.setAttribute('aria-label', 'Hide password');
          } else {
            passwordField.type = 'password';
            toggleBtn.textContent = 'Show';
            toggleBtn.setAttribute('aria-label', 'Show password');
          }
        });
      }
    });
  </script>
@endpush
