/**
 * Account signin page JavaScript functionality
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

// Captcha generation function
function generateCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    let captcha = '';
    for (let i = 0; i < 6; i++) {
        captcha += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('captcha-display').textContent = captcha;
    document.getElementById('captcha-answer').value = captcha;
    document.getElementById('captcha').value = '';
}

// Initialize page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize captcha on page load
    if (document.getElementById('captcha-display')) {
        generateCaptcha();
    }

    // Form submission with captcha validation
    const signinForm = document.querySelector('form[action="/signin"]');
    if (signinForm) {
        signinForm.addEventListener('submit', function(e) {
            const captchaInput = document.getElementById('captcha').value;
            const captchaAnswer = document.getElementById('captcha-answer').value;

            if (captchaInput !== captchaAnswer) {
                e.preventDefault();
                alert('Captcha verification failed. Please try again.');
                generateCaptcha();
                return false;
            }
            
            // Form validation
            const requiredFields = this.querySelectorAll('[required]');
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
    }
});