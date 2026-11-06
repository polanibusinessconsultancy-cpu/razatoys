<x-app-layout>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- <x-guest.sidenav-guest /> -->
            </div>
        </div>
    </div>

    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8 signup-bg-image">
                                    <div class="my-auto text-start max-width-350 ms-7">
                                        <h1 class="mt-3 text-white font-weight-bolder">Reset Your <br> Account Password.</h1>
                                        <p class="text-white text-lg mt-4 mb-4">Enter your email address and we'll send you a secure link to reset your password.</p>
                                    </div>
                                    <div class="text-start position-absolute fixed-bottom ms-7">
                                        <h6 class="text-white text-sm mb-5">Copyright © {{ now()->year }} One Stop Mart. All Rights Reserved.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column mx-auto">

                            <div class="card card-plain mt-0 shadow-lg">
                                <div class="card-header pb-3 bg-transparent header-logo-section">
                                    <!-- Logo centered -->
                                    <div class="logo-container-center mb-2">
                                        <img src="{{ asset('one-stop-mart/images/branding/one-stop-mart-logo-admin.png') }}" alt="One Stop Mart Logo" class="header-logo" style="max-height: 55px; width: auto; object-fit: contain;">
                                    </div>

                                    <!-- Back to Home Button centered below logo -->
                                    <div style="text-align: center;">
                                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary back-home-btn mb-3">
                                            <i class="fas fa-home me-2"></i>Back to Home
                                        </a>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <!-- Header Text -->
                                    <h3 class="font-weight-black text-dark mb-2">Forgot Your Password?</h3>
                                    <p class="text-muted mb-0">No worries! Enter your email address and we'll send you a secure link to reset your password.</p>
                                </div>
                                <div class="card-body px-4">
                                    <form role="form" method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                                        @csrf

                                        {{-- Email Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-envelope me-2 form-icon"></i>Email Address
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control form-control-lg border-2"
                                                placeholder="Enter your email address" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg w-100 shadow-lg text-white fw-bold btn-gradient-primary">
                                                <i class="fas fa-envelope me-2"></i>
                                                Send Reset Link
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-3 px-4 bg-light">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="text-muted me-2">Remember your password?</span>
                                        <a href="{{ route('sign-in') }}" class="btn btn-sm text-white btn-gradient-primary">
                                            <i class="fas fa-sign-in-alt me-1"></i>Sign In
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-muted me-2">Don't have an account?</span>
                                        <a href="{{ route('sign-up') }}" class="btn btn-sm text-white btn-gradient-primary">
                                            <i class="fas fa-user-plus me-1"></i>Sign Up
                                        </a>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1 security-icon"></i>
                                            Your data is protected with enterprise-grade security
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-app-layout>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/extracted/auth-signup-custom.css') }}">
@endpush

@push('scripts')
    {{-- Additional scripts if needed --}}
@endpush
