/**
 * Livewire Styles Nonce Fix
 * Specifically targets the @livewireStyles directive output
 */

(function() {
    'use strict';
    
    // Get nonce
    const nonce = document.querySelector('meta[name="csp-nonce"]')?.getAttribute('content') || 
                  document.currentScript?.getAttribute('nonce');
    
    if (!nonce) {
        console.error('Livewire Styles Nonce Fix: No nonce available');
        return;
    }
    
    console.log('Livewire Styles Nonce Fix: Starting with nonce:', nonce);
    
    // Function to fix Livewire styles container
    function fixLivewireStylesContainer() {
        const container = document.getElementById('livewire-styles-container');
        if (container) {
            // Find all style elements in the container
            const styles = container.querySelectorAll('style');
            let fixed = 0;
            
            styles.forEach(style => {
                if (!style.hasAttribute('nonce')) {
                    style.setAttribute('nonce', nonce);
                    fixed++;
                }
            });
            
            if (fixed > 0) {
                console.log('Livewire Styles Nonce Fix: Added nonce to', fixed, 'Livewire styles');
            }
            
            return fixed > 0;
        }
        return false;
    }
    
    // Run immediately
    fixLivewireStylesContainer();
    
    // Set up observer specifically for the Livewire styles container
    const observer = new MutationObserver(function(mutations) {
        fixLivewireStylesContainer();
    });
    
    // Observe the entire document for the container
    observer.observe(document.documentElement, {
        childList: true,
        subtree: true
    });
    
    // Run periodically for first 2 seconds
    const interval = setInterval(fixLivewireStylesContainer, 10);
    setTimeout(() => {
        clearInterval(interval);
        console.log('Livewire Styles Nonce Fix: Periodic fixing stopped');
    }, 2000);
    
    // Also run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fixLivewireStylesContainer);
    }
    
    console.log('Livewire Styles Nonce Fix: Initialized');
    
})();