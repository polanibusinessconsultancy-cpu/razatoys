/**
 * Livewire Styles Fix
 * This script specifically targets Livewire's style injection
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get CSP nonce from meta tag
    const nonceMetaTag = document.querySelector('meta[name="csp-nonce"]');
    const nonce = nonceMetaTag ? nonceMetaTag.getAttribute('content') : null;
    
    if (!nonce) {
        console.warn('CSP nonce not found in meta tag');
        return;
    }
    
    // Function to fix Livewire styles
    function fixLivewireStyles() {
        // Find the Livewire styles element
        const livewireStyles = document.querySelector('style[wire\\:initial-data]');
        
        if (livewireStyles && !livewireStyles.hasAttribute('nonce')) {
            // Add nonce to the Livewire styles element
            livewireStyles.setAttribute('nonce', nonce);
            console.log('Added nonce to Livewire styles');
        }
        
        // Find all style elements without nonce
        const styleElements = document.querySelectorAll('style:not([nonce])');
        
        if (styleElements.length > 0) {
            console.log(`Found ${styleElements.length} style elements without nonce`);
            
            styleElements.forEach((style, index) => {
                // Add nonce to the style element
                style.setAttribute('nonce', nonce);
                console.log(`Added nonce to style element ${index + 1}`);
            });
        }
    }
    
    // Run immediately
    fixLivewireStyles();
    
    // Set up a MutationObserver to watch for new style elements
    const observer = new MutationObserver(mutations => {
        let hasNewStyles = false;
        
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && node.tagName === 'STYLE' && !node.hasAttribute('nonce')) {
                        hasNewStyles = true;
                    }
                });
            }
        });
        
        if (hasNewStyles) {
            fixLivewireStyles();
        }
    });
    
    // Start observing the document
    observer.observe(document.head, {
        childList: true,
        subtree: true
    });
    
    // Run periodically to catch any missed styles
    setInterval(fixLivewireStyles, 200);
    
    // Hook into Livewire events
    if (typeof window.Livewire !== 'undefined') {
        document.addEventListener('livewire:load', fixLivewireStyles);
        document.addEventListener('livewire:update', fixLivewireStyles);
    }
});