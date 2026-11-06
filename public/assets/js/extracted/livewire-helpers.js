/**
 * Livewire Helper Functions
 * This file provides helper functions for Livewire to work with CSP
 */

// Function to safely inject styles with nonce
function injectStyledWithNonce(css, nonce) {
    // Get the nonce from the meta tag if not provided
    if (!nonce) {
        const nonceMetaTag = document.querySelector('meta[name="csp-nonce"]');
        if (nonceMetaTag) {
            nonce = nonceMetaTag.getAttribute('content');
        }
    }
    
    // Create style element with nonce
    const style = document.createElement('style');
    if (nonce) {
        style.setAttribute('nonce', nonce);
    }
    
    style.textContent = css;
    document.head.appendChild(style);
    
    return style;
}

// Override Livewire's style injection method
document.addEventListener('DOMContentLoaded', function() {
    // Store original createElement method
    const originalCreateElement = document.createElement;
    
    // Get CSP nonce from meta tag
    const nonceMetaTag = document.querySelector('meta[name="csp-nonce"]');
    const nonce = nonceMetaTag ? nonceMetaTag.getAttribute('content') : null;
    
    // Override createElement to add nonce to style elements
    document.createElement = function(tagName) {
        const element = originalCreateElement.apply(document, arguments);
        
        if (tagName.toLowerCase() === 'style' && nonce) {
            element.setAttribute('nonce', nonce);
        }
        
        return element;
    };
    
    // Handle existing style tags without nonce
    function fixExistingStyleTags() {
        const styleTagsWithoutNonce = document.querySelectorAll('style:not([nonce])');
        
        styleTagsWithoutNonce.forEach(styleTag => {
            // Create a new style tag with nonce
            const newStyle = injectStyledWithNonce(styleTag.textContent, nonce);
            
            // Replace the original style tag
            if (styleTag.parentNode) {
                styleTag.parentNode.replaceChild(newStyle, styleTag);
            }
        });
    }
    
    // Fix existing style tags
    fixExistingStyleTags();
    
    // Set up a MutationObserver to watch for new style tags
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.tagName === 'STYLE' && !node.hasAttribute('nonce') && nonce) {
                        node.setAttribute('nonce', nonce);
                    }
                });
            }
        });
    });
    
    // Start observing the document
    observer.observe(document.documentElement, {
        childList: true,
        subtree: true
    });
});

// Override Livewire's style injection method if it exists
document.addEventListener('livewire:load', function() {
    if (window.Livewire && window.Livewire.hook) {
        // Get CSP nonce from meta tag
        const nonceMetaTag = document.querySelector('meta[name="csp-nonce"]');
        const nonce = nonceMetaTag ? nonceMetaTag.getAttribute('content') : null;
        
        // Hook into Livewire's style injection
        window.Livewire.hook('message.processed', (message, component) => {
            // Find any style tags without nonce that Livewire might have added
            const styleTagsWithoutNonce = document.querySelectorAll('style:not([nonce])');
            
            styleTagsWithoutNonce.forEach(styleTag => {
                if (nonce) {
                    styleTag.setAttribute('nonce', nonce);
                }
            });
        });
        
        // Override Livewire's original style injection if possible
        if (window.Livewire.directive) {
            const originalStyleDirective = window.Livewire.directive('style');
            if (originalStyleDirective) {
                window.Livewire.directive('style', (el, directive, component) => {
                    originalStyleDirective(el, directive, component);
                    
                    // Add nonce to any style elements created
                    if (el.tagName === 'STYLE' && nonce) {
                        el.setAttribute('nonce', nonce);
                    }
                });
            }
        }
    }
});