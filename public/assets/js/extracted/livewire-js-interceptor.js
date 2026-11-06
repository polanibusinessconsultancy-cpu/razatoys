/**
 * Livewire.js Interceptor
 * Intercepts Livewire.js style injection to add nonces
 */

(function() {
    'use strict';
    
    // Get nonce
    const nonce = document.querySelector('meta[name="csp-nonce"]')?.getAttribute('content') || 
                  document.currentScript?.getAttribute('nonce');
    
    if (!nonce) {
        // CSP removed - nonce check disabled
        return;
    }
    
    console.log('Livewire.js Interceptor: Starting with nonce:', nonce);
    
    // Override document.createElement to add nonces to style elements
    const originalCreateElement = document.createElement;
    document.createElement = function(tagName) {
        const element = originalCreateElement.call(this, tagName);
        
        if (tagName.toLowerCase() === 'style') {
            element.setAttribute('nonce', nonce);
        }
        
        return element;
    };
    
    // Override appendChild to ensure styles have nonces
    const originalAppendChild = Node.prototype.appendChild;
    Node.prototype.appendChild = function(child) {
        if (child && child.tagName === 'STYLE' && !child.hasAttribute('nonce')) {
            child.setAttribute('nonce', nonce);
        }
        return originalAppendChild.call(this, child);
    };
    
    // Override insertBefore to ensure styles have nonces
    const originalInsertBefore = Node.prototype.insertBefore;
    Node.prototype.insertBefore = function(newNode, referenceNode) {
        if (newNode && newNode.tagName === 'STYLE' && !newNode.hasAttribute('nonce')) {
            newNode.setAttribute('nonce', nonce);
        }
        return originalInsertBefore.call(this, newNode, referenceNode);
    };
    
    // Override replaceChild to ensure styles have nonces
    const originalReplaceChild = Node.prototype.replaceChild;
    Node.prototype.replaceChild = function(newChild, oldChild) {
        if (newChild && newChild.tagName === 'STYLE' && !newChild.hasAttribute('nonce')) {
            newChild.setAttribute('nonce', nonce);
        }
        return originalReplaceChild.call(this, newChild, oldChild);
    };
    
    // Function to fix existing styles
    function fixExistingStyles() {
        const stylesWithoutNonce = document.querySelectorAll('style:not([nonce])');
        stylesWithoutNonce.forEach(style => {
            // Check if it's likely a Livewire style
            if (style.textContent && (
                style.textContent.includes('nprogress') ||
                style.textContent.includes('[wire:') ||
                style.textContent.includes('livewire') ||
                style.textContent.includes('alpine')
            )) {
                style.setAttribute('nonce', nonce);
                console.log('Livewire.js Interceptor: Added nonce to Livewire style');
            }
        });
    }
    
    // Run immediately
    fixExistingStyles();
    
    // Set up observer for new styles
    const observer = new MutationObserver(function(mutations) {
        fixExistingStyles();
    });
    
    observer.observe(document.head, {
        childList: true,
        subtree: true
    });
    
    // Run periodically for first 5 seconds
    const interval = setInterval(fixExistingStyles, 100);
    setTimeout(() => {
        clearInterval(interval);
        console.log('Livewire.js Interceptor: Periodic fixing stopped');
    }, 5000);
    
    console.log('Livewire.js Interceptor: Initialized');
    
})();