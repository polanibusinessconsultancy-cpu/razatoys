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
                                        <h1 class="mt-3 text-white font-weight-bolder">Create Your <br> New Password.</h1>
                                        <p class="text-white text-lg mt-4 mb-4">Set a strong password to secure your account and regain access.</p>
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

                                    <!-- Header Text -->
                                    <h3 class="font-weight-black text-dark mb-2">Reset Your Password</h3>
                                    <p class="text-muted mb-4">Create a strong password to secure your account.</p>

                                    {{-- Password Requirements Badge --}}
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="badge p-3 rounded-3 shadow-sm device-badge">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <i class="fas fa-lock text-white me-2"></i>
                                                <span class="text-white fw-bold">Password Requirements</span>
                                            </div>
                                            <div class="text-start">
                                                <small class="text-white d-block">
                                                    <i class="fas fa-check me-1" id="req-length"></i>
                                                    <span id="req-length-text">At least 6 characters</span>
                                                </small>
                                                <small class="text-white d-block">
                                                    <i class="fas fa-check me-1" id="req-upper"></i>
                                                    <span id="req-upper-text">One uppercase letter</span>
                                                </small>
                                                <small class="text-white d-block">
                                                    <i class="fas fa-check me-1" id="req-lower"></i>
                                                    <span id="req-lower-text">One lowercase letter</span>
                                                </small>
                                                <small class="text-white d-block">
                                                    <i class="fas fa-check me-1" id="req-number"></i>
                                                    <span id="req-number-text">One number</span>
                                                </small>
                                                <small class="text-white d-block">
                                                    <i class="fas fa-check me-1" id="req-special"></i>
                                                    <span id="req-special-text">One special character (@$!%*?&#+=~)</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-4">
                                    <form role="form" method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                                        @csrf

                                        {{-- Email Field (Hidden) --}}
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        {{-- Password Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-lock me-2 form-icon"></i>New Password
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" id="password" name="password" class="form-control form-control-lg border-2"
                                                    placeholder="Create a new password" required>
                                                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" type="button" data-field="password">
                                                    <i class="fas fa-eye password-eye-icon" id="password-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Confirm Password Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-lock me-2 form-icon"></i>Confirm Password
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg border-2"
                                                    placeholder="Confirm your new password" required>
                                                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" type="button" data-field="password_confirmation">
                                                    <i class="fas fa-eye password-eye-icon" id="password_confirmation-eye"></i>
                                                </button>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg w-100 shadow-lg text-white fw-bold btn-gradient-primary">
                                                <i class="fas fa-check me-2"></i>
                                                Reset Password
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

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/extracted/auth-signup-custom.css') }}">
    @endpush

    @push('scripts')
    <script>
        // Password validation checker
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function checkPasswordRequirements() {
            const password = passwordInput.value;

            // Check requirements
            const hasLength = password.length >= 6;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&#+=~]/.test(password);

            // Update UI
            updateRequirement('req-length', hasLength);
            updateRequirement('req-upper', hasUpper);
            updateRequirement('req-lower', hasLower);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-special', hasSpecial);
        }

        function updateRequirement(elementId, isMet) {
            const element = document.getElementById(elementId);
            if (isMet) {
                element.classList.remove('opacity-50');
                element.classList.add('text-success');
            } else {
                element.classList.add('opacity-50');
                element.classList.remove('text-success');
            }
        }

        passwordInput.addEventListener('input', checkPasswordRequirements);
        checkPasswordRequirements(); // Initial check
    </script>
    @endpush

</x-app-layout>
