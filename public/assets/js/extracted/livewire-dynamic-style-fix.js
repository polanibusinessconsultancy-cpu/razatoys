/**
 * Livewire Dynamic Style Fix
 * This script fixes CSP issues with Livewire's dynamic styles
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get CSP nonce from meta tag
    const nonceMetaTag = document.querySelector('meta[name="csp-nonce"]');
    const nonce = nonceMetaTag ? nonceMetaTag.getAttribute('content') : null;
    
    if (!nonce) {
        // CSP removed - nonce check disabled
        return;
    }
    
    // Function to add nonce to style elements
    function addNonceToStyles() {
        const styles = document.querySelectorAll('style:not([nonce])');
        styles.forEach(style => {
            style.setAttribute('nonce', nonce);
        });
    }
    
    // Add nonce to existing style elements
    addNonceToStyles();
    
    // Set up a MutationObserver to watch for new style elements
    const observer = new MutationObserver(mutations => {
        let needsUpdate = false;
        
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && node.tagName === 'STYLE' && !node.hasAttribute('nonce')) {
                        needsUpdate = true;
                    }
                });
            }
        });
        
        if (needsUpdate) {
            addNonceToStyles();
        }
    });
    
    // Start observing the document
    observer.observe(document.documentElement, {
        childList: true,
        subtree: true
    });
    
    // Special handling for Livewire
    if (window.Livewire) {
        // Wait for Livewire to be fully initialized
        setTimeout(() => {
            addNonceToStyles();
        }, 500);
        
        // Hook into Livewire events if possible
        document.addEventListener('livewire:load', function() {
            addNonceToStyles();
        });
        
        document.addEventListener('livewire:update', function() {
            addNonceToStyles();
        });
    }
});