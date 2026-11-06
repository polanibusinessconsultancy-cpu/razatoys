/**
 * Livewire Nonce Fix
 * This script adds nonces to Livewire-generated styles
 */

document.addEventListener('DOMContentLoaded', function() {
    const nonceValue = document.querySelector('meta[name="csp-nonce"]')?.getAttribute('content');
    
    if (!nonceValue) {
        console.warn('Livewire Nonce Fix: CSP nonce not found');
        return;
    }
    
    function fixLivewireStyles() {
        const livewireStylesContainer = document.getElementById('livewire-styles-container');
        if (livewireStylesContainer) {
            const styles = livewireStylesContainer.querySelectorAll('style:not([nonce])');
            styles.forEach(style => {
                style.setAttribute('nonce', nonceValue);
            });
        }
        
        // Also fix any other Livewire styles that might be added dynamically
        const allStyles = document.querySelectorAll('style:not([nonce])');
        allStyles.forEach(style => {
            // Check if it's likely a Livewire style
            if (style.textContent && (
                style.textContent.includes('[wire:') ||
                style.textContent.includes('livewire') ||
                style.textContent.includes('alpine')
            )) {
                style.setAttribute('nonce', nonceValue);
            }
        });
    }
    
    // Initial fix
    fixLivewireStyles();
    
    // Set up observer for Livewire updates
    const observer = new MutationObserver(function(mutations) {
        let needsFix = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'STYLE') {
                        needsFix = true;
                    }
                });
            }
        });
        
        if (needsFix) {
            setTimeout(fixLivewireStyles, 10);
        }
    });
    
    observer.observe(document.head, {
        childList: true,
        subtree: true
    });
    
    // Also observe the Livewire container
    const livewireContainer = document.getElementById('livewire-styles-container');
    if (livewireContainer) {
        observer.observe(livewireContainer, {
            childList: true,
            subtree: true
        });
    }
    
    console.log('Livewire Nonce Fix: Initialized');
});