/**
 * Livewire Style Handler
 * This script handles Livewire's dynamic style injection to make it CSP compliant
 */

document.addEventListener('DOMContentLoaded', function() {
    // Only proceed if Livewire is present
    if (typeof window.Livewire === 'undefined') {
        return;
    }
    
    // Get CSP nonce from meta tag
    const nonceMetaTag = document.querySelector('meta[name="csp-nonce"]');
    const nonce = nonceMetaTag ? nonceMetaTag.getAttribute('content') : null;
    
    if (!nonce) {
        // CSP removed - nonce check disabled
        return;
    }
    
    // Create a style element for Livewire's dynamic styles
    const livewireStyleElement = document.createElement('style');
    livewireStyleElement.setAttribute('nonce', nonce);
    livewireStyleElement.setAttribute('id', 'livewire-dynamic-styles');
    document.head.appendChild(livewireStyleElement);
    
    // Function to update Livewire's dynamic styles
    function updateLivewireStyles() {
        // Find all style elements without nonce
        const styleElements = document.querySelectorAll('style:not([nonce])');
        
        // Collect all styles
        let combinedStyles = '';
        
        styleElements.forEach(style => {
            // Add the style content to our combined styles
            combinedStyles += style.textContent + '\n';
            
            // Remove the original style element
            if (style.parentNode) {
                style.parentNode.removeChild(style);
            }
        });
        
        // Update our nonced style element
        if (combinedStyles) {
            livewireStyleElement.textContent += combinedStyles;
        }
    }
    
    // Set up a MutationObserver to watch for new style elements
    const observer = new MutationObserver(function(mutations) {
        let hasNewStyles = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.tagName === 'STYLE' && !node.hasAttribute('nonce')) {
                        hasNewStyles = true;
                    }
                });
            }
        });
        
        if (hasNewStyles) {
            updateLivewireStyles();
        }
    });
    
    // Start observing the document
    observer.observe(document.head, {
        childList: true,
        subtree: true
    });
    
    // Hook into Livewire events
    document.addEventListener('livewire:load', updateLivewireStyles);
    document.addEventListener('livewire:update', updateLivewireStyles);
    
    // Run periodically to catch any missed styles
    setInterval(updateLivewireStyles, 500);
});