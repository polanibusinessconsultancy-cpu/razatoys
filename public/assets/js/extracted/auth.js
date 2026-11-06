/**
 * Authentication Pages JavaScript
 *
 * IMPORTANT: CAPTCHA is handled by unified-captcha.js
 * This file only handles password toggle and other auth page features
 */

// Password toggle function
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + '-eye');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

// Initialize auth page functionality
document.addEventListener('DOMContentLoaded', function () {
    console.log('Auth.js initialized - Password toggle ready');

    // Direct password toggle button handler
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordEyeIcon = document.getElementById('password-eye');

    console.log('Toggle button found:', togglePasswordBtn);
    console.log('Eye icon found:', passwordEyeIcon);

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Toggle button clicked');

            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('password-eye');

            console.log('Password field:', passwordField);
            console.log('Eye icon:', eyeIcon);

            if (passwordField && eyeIcon) {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                    console.log('Password shown');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                    console.log('Password hidden');
                }
            }
        });
    }

    // Also add listener to the icon itself
    if (passwordEyeIcon) {
        passwordEyeIcon.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Eye icon clicked directly');

            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('password-eye');

            if (passwordField && eyeIcon) {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                    console.log('Password shown via icon');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                    console.log('Password hidden via icon');
                }
            }
        });
    }

    // Event delegation for password toggle buttons (fallback)
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('.auth-password-toggle');
        if (toggleBtn && toggleBtn.id !== 'togglePasswordBtn') {
            e.preventDefault();
            const container = toggleBtn.closest('.position-relative');
            const passwordField = container.querySelector('input[type="password"], input[type="text"]');
            const eyeIcon = container.querySelector('i');

            if (passwordField && eyeIcon) {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            }
        }
    });
});

// Additional auth page functionality

// GitHub buttons initialization
document.addEventListener('DOMContentLoaded', function () {
    // Handle GitHub buttons
    if (typeof window.GitHubButtons !== 'undefined') {
        window.GitHubButtons.init();
    }

    // Handle any form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Handle password strength indicator if present
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.addEventListener('input', function () {
            const strength = document.getElementById('password-strength');
            if (strength) {
                // Simple password strength check
                const value = this.value;
                let strengthValue = 0;

                if (value.length >= 8) strengthValue += 1;
                if (/[A-Z]/.test(value)) strengthValue += 1;
                if (/[0-9]/.test(value)) strengthValue += 1;
                if (/[^A-Za-z0-9]/.test(value)) strengthValue += 1;

                // Update strength indicator
                switch (strengthValue) {
                    case 0:
                    case 1:
                        strength.textContent = 'Weak';
                        strength.className = 'text-danger';
                        break;
                    case 2:
                        strength.textContent = 'Medium';
                        strength.className = 'text-warning';
                        break;
                    case 3:
                        strength.textContent = 'Strong';
                        strength.className = 'text-success';
                        break;
                    case 4:
                        strength.textContent = 'Very Strong';
                        strength.className = 'text-success';
                        break;
                }
            }
        });
    }
});

// Fix for GitHub buttons
if (typeof window.GitHubButtons === 'undefined') {
    window.GitHubButtons = {
        init: function () {
            console.log('GitHub Buttons initialized');
        }
    };
}
