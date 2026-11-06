/**
 * Fix for GitHub buttons and other inline scripts
 */

document.addEventListener('DOMContentLoaded', function() {
    // Fix for GitHub buttons
    if (typeof window.GitHubButtons !== 'undefined') {
        window.GitHubButtons.init();
    }
    
    // Password toggle functionality for auth pages
    const passwordToggles = document.querySelectorAll('.auth-password-toggle');
    if (passwordToggles.length > 0) {
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const passwordField = this.closest('.position-relative').querySelector('input[type="password"], input[type="text"]');
                const eyeIcon = this.querySelector('i');
                
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        });
    }
    
    // Captcha refresh functionality
    const captchaRefreshButtons = document.querySelectorAll('[data-action="refresh-captcha"]');
    if (captchaRefreshButtons.length > 0) {
        captchaRefreshButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                if (typeof generateCaptcha === 'function') {
                    generateCaptcha();
                }
            });
        });
    }
    
    // Form validation for auth forms
    const authForms = document.querySelectorAll('form[action="/signin"], form[action="/signup"]');
    if (authForms.length > 0) {
        authForms.forEach(form => {
            form.addEventListener('submit', function(e) {
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
                
                // Captcha validation
                const captchaInput = document.getElementById('captcha');
                const captchaAnswer = document.getElementById('captcha-answer');
                
                if (captchaInput && captchaAnswer && captchaInput.value !== captchaAnswer.value) {
                    e.preventDefault();
                    alert('Captcha verification failed. Please try again.');
                    if (typeof generateCaptcha === 'function') {
                        generateCaptcha();
                    }
                }
            });
        });
    }
});