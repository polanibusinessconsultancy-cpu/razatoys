/**
 * Country Phone Selector
 * Handles country code display and phone number formatting
 * Countries are pre-populated from PHP/Blade
 */

(function() {
    'use strict';

    function initCountryPhoneSelector() {
        var countrySelect = document.getElementById('country');
        var phoneInput = document.getElementById('phone');
        var countryCodeDisplay = document.getElementById('country-code-display');
        var countryFormatHint = document.getElementById('country-format-hint');
        
        if (!countrySelect || !phoneInput) {
            console.warn('Country selector or phone input not found');
            return;
        }

        // Initialize display based on current selection
        updatePhoneDisplay();

        function getSelectedDialCode() {
            var selectedOption = countrySelect.options[countrySelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.dialCode) {
                return selectedOption.dataset.dialCode;
            }
            return '+92'; // Default to Pakistan
        }

        function updatePhoneDisplay() {
            var selectedCode = countrySelect.value;
            var dialCode = getSelectedDialCode();
            
            // Update country code display
            if (countryCodeDisplay) {
                countryCodeDisplay.textContent = dialCode;
            }
            
            // Update format hint based on country
            if (countryFormatHint) {
                if (selectedCode === 'PK') {
                    countryFormatHint.textContent = 'Enter your phone number (e.g., 3152771063 or 03152771063)';
                } else {
                    countryFormatHint.textContent = 'Enter your phone number without the country code ' + dialCode;
                }
            }
            
            console.log('Country changed to:', selectedCode, 'Dial code:', dialCode);
        }

        // Update display when country changes
        countrySelect.addEventListener('change', function() {
            console.log('Country select changed');
            updatePhoneDisplay();
        });

        // Format phone input on blur
        phoneInput.addEventListener('blur', function() {
            var selectedCode = countrySelect.value;
            if (!selectedCode) {
                return;
            }

            var dialCode = getSelectedDialCode();
            var countryCode = dialCode.replace('+', '').replace(/\s/g, '');
            var phoneValue = this.value.trim();

            // Remove any non-digit characters except +
            phoneValue = phoneValue.replace(/[^\d+]/g, '');

            // Remove leading + if present
            if (phoneValue.indexOf('+') === 0) {
                phoneValue = phoneValue.substring(1);
            }

            // Don't format if empty
            if (!phoneValue) {
                return;
            }

            // Special handling for Pakistan
            if (selectedCode === 'PK') {
                // If starts with 0, convert to 92
                if (phoneValue.indexOf('0') === 0) {
                    phoneValue = '92' + phoneValue.substring(1);
                }
                // If doesn't start with 92, prepend it
                else if (phoneValue.indexOf('92') !== 0) {
                    phoneValue = '92' + phoneValue;
                }
            } else {
                // For other countries, prepend country code if not present
                if (phoneValue.indexOf(countryCode) !== 0) {
                    phoneValue = countryCode + phoneValue;
                }
            }

            // Update the input with formatted number
            this.value = phoneValue;
            console.log('Phone formatted to:', phoneValue);
        });

        // Handle the confirm button in modal
        var confirmBtn = document.querySelector('.confirm-submit-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                var form = phoneInput.closest('form');
                if (form && validateForm()) {
                    // Trigger blur to ensure formatting
                    phoneInput.blur();
                    form.submit();
                }
            });
        }

        function validateForm() {
            var selectedCode = countrySelect.value;
            var phoneValue = phoneInput.value.trim();

            if (!selectedCode) {
                alert('Please select a country');
                countrySelect.focus();
                return false;
            }

            if (!phoneValue) {
                alert('Please enter a phone number');
                phoneInput.focus();
                return false;
            }

            return true;
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountryPhoneSelector);
    } else {
        initCountryPhoneSelector();
    }
})();
