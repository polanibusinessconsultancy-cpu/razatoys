// Simple password toggle functionality
(function() {
    'use strict';
    
    console.log('Password toggle script loaded');
    
    function initPasswordToggle() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('password-eye');
        
        console.log('Elements:', {
            toggleBtn: toggleBtn,
            passwordField: passwordField,
            eyeIcon: eyeIcon
        });
        
        if (!toggleBtn || !passwordField || !eyeIcon) {
            console.error('Missing elements for password toggle');
            return;
        }
        
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Toggle clicked, current type:', passwordField.type);
            
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
        });
        
        console.log('Password toggle initialized successfully');
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPasswordToggle);
    } else {
        initPasswordToggle();
    }
})();
