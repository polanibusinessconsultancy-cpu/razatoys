let countriesArray = [];

// Phone digit map for different countries
const phoneDigitMap = {
    'PK': 10, 'US': 10, 'GB': 10, 'CA': 10, 'AU': 9,
    'IN': 10, 'BD': 10, 'AE': 9, 'SA': 9, 'MY': 9,
    'SG': 8, 'TH': 9, 'ID': 10, 'PH': 10, 'NZ': 9,
    'ZA': 9, 'NG': 10, 'KE': 9, 'DE': 10, 'FR': 9,
    'IT': 10, 'ES': 9, 'NL': 9, 'SE': 9, 'CH': 9,
    'AT': 10, 'BE': 9, 'BR': 11, 'MX': 10, 'AR': 10,
    'CL': 9, 'CO': 10, 'JP': 10, 'CN': 11, 'KR': 10,
    'RU': 10, 'TR': 10, 'EG': 10, 'IL': 9, 'IR': 10,
    'IQ': 10, 'JO': 9, 'LB': 8, 'OM': 8, 'QA': 8,
    'KW': 8, 'BH': 8
};

// Phone format examples for different countries
const phoneFormatMap = {
    'PK': '3152771063', 'US': '2025551234', 'GB': '2071838750', 'CA': '2025551234', 'AU': '212345678',
    'IN': '9876543210', 'BD': '1812345678', 'AE': '501234567', 'SA': '501234567', 'MY': '123456789',
    'SG': '91234567', 'TH': '812345678', 'ID': '8123456789', 'PH': '9123456789', 'NZ': '212345678',
    'ZA': '712345678', 'NG': '8012345678', 'KE': '712345678', 'DE': '3012345678', 'FR': '123456789',
    'IT': '3123456789', 'ES': '612345678', 'NL': '612345678', 'SE': '701234567', 'CH': '791234567',
    'AT': '6601234567', 'BE': '412345678', 'BR': '11912345678', 'MX': '5512345678', 'AR': '1123456789',
    'CL': '912345678', 'CO': '3012345678', 'JP': '9012345678', 'CN': '13812345678', 'KR': '1012345678',
    'RU': '9123456789', 'TR': '5012345678', 'EG': '1001234567', 'IL': '501234567', 'IR': '9123456789',
    'IQ': '7912345678', 'JO': '791234567', 'LB': '71234567', 'OM': '91234567', 'QA': '33123456',
    'KW': '91234567', 'BH': '33123456'
};

// Load country data from API endpoint
function loadCountryData() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/countries', true);
    xhr.responseType = 'json';
    
    xhr.onload = function() {
        if (xhr.status === 200 && xhr.response && Array.isArray(xhr.response)) {
            countriesArray = xhr.response;
            console.log('✓ Countries loaded successfully from API:', countriesArray.length);
            initializePhoneForm();
        } else {
            console.error('✗ Failed to load countries. Status:', xhr.status);
            console.error('Response:', xhr.response);
            initializePhoneForm();
        }
    };
    
    xhr.onerror = function() {
        console.error('✗ Error loading country data from API');
        initializePhoneForm();
    };
    
    xhr.ontimeout = function() {
        console.error('✗ Timeout loading country data');
        initializePhoneForm();
    };
    
    xhr.timeout = 5000; // 5 second timeout
    xhr.send();
}

function getPhoneDigits(countryCode) {
    return phoneDigitMap[countryCode] || 10;
}

function getPhoneFormat(countryCode) {
    return phoneFormatMap[countryCode] || '1234567890';
}

function initializePhoneForm() {
    const countrySelect = document.getElementById('country');
    const phoneInput = document.getElementById('phone');
    const phonePrefix = document.getElementById('phone-prefix');
    const phoneFormatHint = document.getElementById('phone-format-hint');

    if (!countrySelect || !phoneInput) {
        console.error('✗ Required form elements not found');
        return;
    }

    console.log('✓ Initializing phone form with', countriesArray.length, 'countries');

    // Populate country dropdown
    populateCountryDropdown(countrySelect);

    // Handle country change
    countrySelect.addEventListener('change', function() {
        const countryCode = this.value;
        
        if (countryCode) {
            // Find country in array
            const country = countriesArray.find(c => c.code === countryCode);
            
            if (country) {
                const dialCode = country.dial_code.replace('+', '');
                const digits = getPhoneDigits(countryCode);
                const format = getPhoneFormat(countryCode);
                
                phonePrefix.textContent = country.dial_code;
                phoneInput.placeholder = `Enter your phone number (${digits} digits)`;
                phoneInput.pattern = '[0-9]{' + digits + '}';
                phoneInput.maxLength = digits;
                phoneFormatHint.textContent = `For ${country.name}: Enter ${digits} digits (e.g., ${format})`;
                phoneInput.value = '';
                
                console.log('✓ Country selected:', country.name, country.dial_code);
            }
        } else {
            phoneInput.value = '';
        }
    });

    // Format phone input - only allow digits
    phoneInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Set initial country if already selected
    if (countrySelect.value) {
        countrySelect.dispatchEvent(new Event('change'));
    }
}

function populateCountryDropdown(selectElement) {
    // Clear existing options except the first one
    while (selectElement.options.length > 1) {
        selectElement.remove(1);
    }

    if (countriesArray.length === 0) {
        console.warn('⚠ No countries data available');
        return;
    }

    // Sort countries by name
    const sortedCountries = [...countriesArray].sort((a, b) => 
        a.name.localeCompare(b.name)
    );

    // Add countries to dropdown
    sortedCountries.forEach(country => {
        const option = document.createElement('option');
        option.value = country.code;
        option.textContent = `${country.name} (${country.dial_code})`;
        selectElement.appendChild(option);
    });
    
    console.log('✓ Dropdown populated with', sortedCountries.length, 'countries');
}

// Load country data when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ DOM Content Loaded - Starting to load countries');
    loadCountryData();
});
