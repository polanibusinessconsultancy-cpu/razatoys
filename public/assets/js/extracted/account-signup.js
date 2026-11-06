// Account Signup JavaScript

// Global variables
let captchaAnswer = 0;

// Country dial codes lookup table
const countryDialCodes = {
    'AF': '+93', 'AL': '+355', 'DZ': '+213', 'AD': '+376', 'AO': '+244',
    'AG': '+1268', 'AR': '+54', 'AM': '+374', 'AU': '+61', 'AT': '+43',
    'AZ': '+994', 'BS': '+1242', 'BH': '+973', 'BD': '+880', 'BB': '+1246',
    'BY': '+375', 'BE': '+32', 'BZ': '+501', 'BJ': '+229', 'BT': '+975',
    'BO': '+591', 'BA': '+387', 'BW': '+267', 'BR': '+55', 'BN': '+673',
    'BG': '+359', 'BF': '+226', 'BI': '+257', 'KH': '+855', 'CM': '+237',
    'CA': '+1', 'CV': '+238', 'CF': '+236', 'TD': '+235', 'CL': '+56',
    'CN': '+86', 'CO': '+57', 'KM': '+269', 'CG': '+242', 'CD': '+243',
    'CR': '+506', 'CI': '+225', 'HR': '+385', 'CU': '+53', 'CY': '+357',
    'CZ': '+420', 'DK': '+45', 'DJ': '+253', 'DM': '+1767', 'DO': '+1849',
    'EC': '+593', 'EG': '+20', 'SV': '+503', 'GQ': '+240', 'ER': '+291',
    'EE': '+372', 'ET': '+251', 'FJ': '+679', 'FI': '+358', 'FR': '+33',
    'GA': '+241', 'GM': '+220', 'GE': '+995', 'DE': '+49', 'GH': '+233',
    'GR': '+30', 'GD': '+1473', 'GT': '+502', 'GN': '+224', 'GW': '+245',
    'GY': '+592', 'HT': '+509', 'HN': '+504', 'HK': '+852', 'HU': '+36',
    'IS': '+354', 'IN': '+91', 'ID': '+62', 'IR': '+98', 'IQ': '+964',
    'IE': '+353', 'IT': '+39', 'JM': '+1876', 'JP': '+81',
    'JO': '+962', 'KZ': '+7', 'KE': '+254', 'KI': '+686', 'KP': '+850',
    'KR': '+82', 'KW': '+965', 'KG': '+996', 'LA': '+856', 'LV': '+371',
    'LB': '+961', 'LS': '+266', 'LR': '+231', 'LY': '+218', 'LI': '+423',
    'LT': '+370', 'LU': '+352', 'MO': '+853', 'MK': '+389', 'MG': '+261',
    'MW': '+265', 'MY': '+60', 'MV': '+960', 'ML': '+223', 'MT': '+356',
    'MH': '+692', 'MR': '+222', 'MU': '+230', 'MX': '+52', 'FM': '+691',
    'MD': '+373', 'MC': '+377', 'MN': '+976', 'ME': '+382', 'MA': '+212',
    'MZ': '+258', 'MM': '+95', 'NA': '+264', 'NR': '+674', 'NP': '+977',
    'NL': '+31', 'NZ': '+64', 'NI': '+505', 'NE': '+227', 'NG': '+234',
    'NO': '+47', 'OM': '+968', 'PK': '+92', 'PW': '+680', 'PS': '+970',
    'PA': '+507', 'PG': '+675', 'PY': '+595', 'PE': '+51', 'PH': '+63',
    'PL': '+48', 'PT': '+351', 'QA': '+974', 'RO': '+40', 'RU': '+7',
    'RW': '+250', 'KN': '+1869', 'LC': '+1758', 'VC': '+1784', 'WS': '+685',
    'SM': '+378', 'ST': '+239', 'SA': '+966', 'SN': '+221', 'RS': '+381',
    'SC': '+248', 'SL': '+232', 'SG': '+65', 'SK': '+421', 'SI': '+386',
    'SB': '+677', 'SO': '+252', 'ZA': '+27', 'SS': '+211', 'ES': '+34',
    'LK': '+94', 'SD': '+249', 'SR': '+597', 'SZ': '+268', 'SE': '+46',
    'CH': '+41', 'SY': '+963', 'TW': '+886', 'TJ': '+992', 'TZ': '+255',
    'TH': '+66', 'TL': '+670', 'TG': '+228', 'TO': '+676', 'TT': '+1868',
    'TN': '+216', 'TR': '+90', 'TM': '+993', 'TV': '+688', 'UG': '+256',
    'UA': '+380', 'AE': '+971', 'GB': '+44', 'US': '+1', 'UY': '+598',
    'UZ': '+998', 'VU': '+678', 'VA': '+379', 'VE': '+58', 'VN': '+84',
    'YE': '+967', 'ZM': '+260', 'ZW': '+263',
    'AI': '+1264', 'AS': '+1684', 'AW': '+297', 'AX': '+358', 'BL': '+590',
    'BM': '+1441', 'BQ': '+599', 'CC': '+61', 'CK': '+682', 'CW': '+599',
    'CX': '+61', 'FK': '+500', 'FO': '+298', 'GF': '+594', 'GG': '+44',
    'GI': '+350', 'GL': '+299', 'GP': '+590', 'GU': '+1671', 'IM': '+44',
    'IO': '+246', 'JE': '+44', 'KY': '+1345', 'MF': '+590', 'MP': '+1670',
    'MQ': '+596', 'MS': '+1664', 'NC': '+687', 'NF': '+672', 'NU': '+683',
    'PF': '+689', 'PM': '+508', 'PR': '+1787', 'RE': '+262', 'SH': '+290',
    'SJ': '+47', 'SX': '+1721', 'TC': '+1649', 'TK': '+690', 'VI': '+1340',
    'VG': '+1284', 'WF': '+681', 'YT': '+262', 'AQ': '+672', 'GS': '+500'
};

// Update country code display when country changes
function updateCountryCode() {
    const countrySelect = document.getElementById('country');
    const countryCodeDisplay = document.getElementById('country-code-display');
    const countryFormatHint = document.getElementById('country-format-hint');
    
    if (!countrySelect) return;
    
    const countryCode = countrySelect.value;
    const dialCode = countryDialCodes[countryCode] || '+92';
    
    if (countryCodeDisplay) {
        countryCodeDisplay.textContent = dialCode;
    }
    
    if (countryFormatHint) {
        if (countryCode === 'PK') {
            countryFormatHint.textContent = 'Enter your phone number (e.g., 3152771063 or 03152771063)';
        } else {
            countryFormatHint.textContent = 'Enter your phone number without the country code ' + dialCode;
        }
    }
}

// Initialize country selector
function initializeCountrySelector() {
    const countrySelect = document.getElementById('country');
    if (countrySelect) {
        // Add change event listener
        countrySelect.addEventListener('change', updateCountryCode);
        // Initial update
        updateCountryCode();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // CAPTCHA is now handled by unified-captcha.js - do NOT call generateCaptcha() here
    // generateCaptcha() is called from unified-captcha.js
    detectDevice();
    initializePasswordToggles();
    initializeFormHandlers();
    initializeCountrySelector();
});

// Password toggle functionality
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + '-eye');
    
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

// Initialize password toggle buttons
function initializePasswordToggles() {
    const passwordToggleBtns = document.querySelectorAll('.password-toggle-btn');
    passwordToggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const fieldId = this.getAttribute('data-field');
            if (fieldId) {
                togglePassword(fieldId);
            }
        });
    });
}

// Generate captcha
function generateCaptcha() {
    const num1 = Math.floor(Math.random() * 10) + 1;
    const num2 = Math.floor(Math.random() * 10) + 1;
    const operators = ['+', '-', '*'];
    const operator = operators[Math.floor(Math.random() * operators.length)];
    
    let question = `${num1} ${operator} ${num2}`;
    
    switch(operator) {
        case '+':
            captchaAnswer = num1 + num2;
            break;
        case '-':
            captchaAnswer = num1 - num2;
            break;
        case '*':
            captchaAnswer = num1 * num2;
            break;
    }
    
    const captchaDisplay = document.getElementById('captcha-display');
    if (captchaDisplay) {
        captchaDisplay.textContent = question;
    }
    
    // Clear captcha input
    const captchaInput = document.getElementById('captcha');
    if (captchaInput) {
        captchaInput.value = '';
    }
}

// Initialize captcha refresh button
function initializeCaptchaButton() {
    const captchaBtn = document.querySelector('.captcha-refresh-btn');
    if (captchaBtn) {
        captchaBtn.addEventListener('click', generateCaptcha);
    }
}

// Device detection
function detectDevice() {
    const userAgent = navigator.userAgent.toLowerCase();
    let deviceType = 'Desktop';
    let iconClass = 'fas fa-desktop text-white';

    if (/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i.test(userAgent)) {
        deviceType = 'Mobile Device';
        iconClass = 'fas fa-mobile-alt text-white';
    } else if (/tablet|ipad/i.test(userAgent)) {
        deviceType = 'Tablet';
        iconClass = 'fas fa-tablet-alt text-white';
    }

    // Update current device display
    const currentDeviceText = document.getElementById('current-device');
    const deviceIcon = document.getElementById('device-icon');
    
    if (currentDeviceText) {
        currentDeviceText.textContent = deviceType;
    }
    
    if (deviceIcon) {
        deviceIcon.className = iconClass + ' me-2';
    }

    // Update modal content
    const modalDeviceType = document.getElementById('modal-device-type');
    const modalDeviceIcon = document.getElementById('modal-device-icon');
    
    if (modalDeviceType) {
        modalDeviceType.textContent = deviceType;
    }
    
    if (modalDeviceIcon) {
        modalDeviceIcon.className = iconClass.replace('text-white', '') + ' device-icon';
    }
}

// Form validation
function validateForm() {
    const form = document.querySelector('form[action="/signup"]');
    if (!form) return false;

    const formData = new FormData(form);
    const captchaInput = document.getElementById('captcha');

    // Validate captcha using the unified CAPTCHA system (window.captchaAnswer)
    if (captchaInput && parseInt(captchaInput.value) !== window.captchaAnswer) {
        alert('Captcha verification failed. Please try again.');
        // Call the unified generateCaptcha from unified-captcha.js
        if (typeof window.generateCaptcha === 'function') {
            window.generateCaptcha();
        }
        captchaInput.value = '';
        captchaInput.focus();
        return false;
    }

    // Validate password match
    const password = formData.get('password');
    const passwordConfirmation = formData.get('password_confirmation');
    
    if (password !== passwordConfirmation) {
        alert('Passwords do not match.');
        return false;
    }

    // Validate terms acceptance
    const termsCheckbox = document.getElementById('terms');
    if (termsCheckbox && !termsCheckbox.checked) {
        alert('Please accept the Terms and Conditions.');
        termsCheckbox.focus();
        return false;
    }

    return true;
}

// Confirm and submit form
function confirmAndSubmit() {
    if (validateForm()) {
        // Close the modal first
        const modal = document.getElementById('deviceConfirmModal');
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
        
        // Submit the form
        const form = document.querySelector('form[action="/signup"]');
        if (form) {
            form.submit();
        }
    }
}

// Initialize form handlers
function initializeFormHandlers() {
    // Initialize captcha button
    initializeCaptchaButton();
    
    // Handle form submission
    const form = document.querySelector('form[action="/signup"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Handle confirm button
    const confirmBtn = document.querySelector('.confirm-submit-btn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', confirmAndSubmit);
    }

    // Handle captcha refresh button
    const captchaRefreshBtn = document.querySelector('.captcha-refresh-btn');
    if (captchaRefreshBtn) {
        captchaRefreshBtn.addEventListener('click', generateCaptcha);
    }

    // Handle modal show event
    const deviceModal = document.getElementById('deviceConfirmModal');
    if (deviceModal) {
        deviceModal.addEventListener('show.bs.modal', function () {
            // Update modal with current device info when it opens
            detectDevice();
        });
    }
}

// Show device confirmation modal
function showDeviceConfirmation() {
    const modal = document.getElementById('deviceConfirmModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Handle back to form
function backToForm() {
    const modal = document.getElementById('deviceConfirmModal');
    if (modal) {
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
            bsModal.hide();
        }
    }
}

// Initialize back button
document.addEventListener('DOMContentLoaded', function() {
    const backBtn = document.querySelector('.back-to-form-btn');
    if (backBtn) {
        backBtn.addEventListener('click', backToForm);
    }
});

// Make functions globally available for inline event handlers
window.togglePassword = togglePassword;
window.generateCaptcha = generateCaptcha;
window.confirmAndSubmit = confirmAndSubmit;
window.updateCountryCode = updateCountryCode;