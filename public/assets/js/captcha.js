/**
 * Simple CAPTCHA System
 * Generates random 6-character alphanumeric codes
 * Format: "aBc123"
 */

class SimpleCaptcha {
    constructor() {
        this.answer = null;
        this.charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Removed confusing chars: I, O, 1
    }

    /**
     * Generate a random CAPTCHA
     */
    generate() {
        this.answer = '';
        for (let i = 0; i < 6; i++) {
            this.answer += this.charset.charAt(Math.floor(Math.random() * this.charset.length));
        }
        console.log('✓ CAPTCHA generated:', this.answer);
        this.displayCaptcha();
        this.clearInput();
    }

    /**
     * Display CAPTCHA on page
     */
    displayCaptcha() {
        const display = document.getElementById('captcha-display');
        if (display) {
            // Set the CAPTCHA text - CSS handles styling
            display.textContent = this.answer;
            // Clear any previous inline styles - let CSS handle the styling
            display.style.display = 'inline-flex';
            display.style.alignItems = 'center';
            display.style.justifyContent = 'center';
        }
    }

    /**
     * Clear the input field
     */
    clearInput() {
        const input = document.getElementById('captcha-input');
        if (input) {
            input.value = '';
            input.focus();
        }
    }

    /**
     * Validate user's answer (case-insensitive)
     */
    validate(userInput) {
        if (!userInput) {
            return false;
        }
        const isValid = userInput.toUpperCase() === this.answer.toUpperCase();
        console.log('CAPTCHA validation:', {
            userInput: userInput,
            correctAnswer: this.answer,
            isValid: isValid
        });
        return isValid;
    }

    /**
     * Handle refresh button
     */
    handleRefresh(e) {
        e.preventDefault();
        e.stopPropagation();
        this.generate();
    }

    /**
     * Initialize CAPTCHA
     */
    init() {
        console.log('Initializing Simple CAPTCHA...');

        // Generate initial CAPTCHA
        this.generate();

        // Attach refresh button listener
        const refreshBtn = document.querySelector('[data-action="refresh-captcha"]');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', (e) => this.handleRefresh(e));
            console.log('✓ Refresh button listener attached');
        }

        // Attach form submission listener - find the auth form (signin or signup)
        const forms = document.querySelectorAll('form');
        console.log('Found', forms.length, 'form(s) on page');

        forms.forEach((form) => {
            const action = form.getAttribute('action');
            console.log('Form action:', action);

            // Only attach to signin and signup forms
            if (action === '/signin' || action === '/signup' || action === '/sign-in' || action === '/sign-up') {
                form.addEventListener('submit', (e) => this.handleFormSubmit(e));
                console.log('✓ Form submission listener attached to form with action:', action);
            }
        });

        console.log('✓ Simple CAPTCHA initialized successfully');
    }

    /**
     * Handle form submission - validate CAPTCHA before allowing submit
     */
    handleFormSubmit(e) {
        console.log('=== FORM SUBMISSION EVENT FIRED ===');
        console.log('Event type:', e.type);
        console.log('Event target:', e.target);

        const captchaInput = document.getElementById('captcha-input');
        const userAnswer = captchaInput ? captchaInput.value.trim() : '';

        console.log('Form submission attempted with CAPTCHA input:', userAnswer);
        console.log('Correct answer is:', this.answer);

        // Check if CAPTCHA field is empty
        if (!userAnswer || userAnswer === '') {
            console.log('✗ CAPTCHA field is EMPTY - preventing form submission');
            e.preventDefault();
            e.stopPropagation();
            alert('Please enter the security code.');
            return false;
        }

        if (!this.validate(userAnswer)) {
            console.log('✗ CAPTCHA validation FAILED - preventing form submission');
            e.preventDefault();
            e.stopPropagation();
            alert('Invalid CAPTCHA. Please try again.');
            this.generate();
            return false;
        }

        // CAPTCHA is valid - allow form to submit by NOT calling preventDefault()
        console.log('✓ CAPTCHA validation PASSED - allowing form submission to proceed');
        console.log('Form will submit to:', e.target.getAttribute('action'));
        // Don't call preventDefault() - let the form submit normally
    }
}

// Initialize when DOM is ready
const captcha = new SimpleCaptcha();
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => captcha.init());
} else {
    captcha.init();
}
