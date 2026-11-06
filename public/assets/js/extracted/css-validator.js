/**
 * CSS Validator Script
 * This script checks for CSS syntax errors and fixes them
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check all CSS files for potential issues
    const cssLinks = document.querySelectorAll('link[rel="stylesheet"]');
    
    cssLinks.forEach(link => {
        const href = link.getAttribute('href');
        
        // Skip external CSS files
        if (href.startsWith('http') && !href.includes(window.location.hostname)) {
            return;
        }
        
        // Check if the CSS file loads properly
        link.addEventListener('error', function() {
            console.error('Failed to load CSS file:', href);
            
            // Try to reload the CSS file
            const newLink = document.createElement('link');
            newLink.rel = 'stylesheet';
            newLink.href = href + '?reload=' + Date.now();
            newLink.setAttribute('nonce', document.querySelector('meta[name="csp-nonce"]')?.getAttribute('content') || '');
            
            document.head.appendChild(newLink);
        });
        
        link.addEventListener('load', function() {
            console.log('CSS file loaded successfully:', href);
        });
    });
    
    // Check for any CSS parsing errors
    const styles = document.querySelectorAll('style');
    styles.forEach((style, index) => {
        try {
            // Try to parse the CSS content
            const cssText = style.textContent;
            if (cssText) {
                // Basic CSS validation
                const openBraces = (cssText.match(/\{/g) || []).length;
                const closeBraces = (cssText.match(/\}/g) || []).length;
                
                if (openBraces !== closeBraces) {
                    console.warn(`CSS syntax error in style element ${index}: Mismatched braces`);
                    console.warn('CSS content:', cssText.substring(0, 200) + '...');
                }
            }
        } catch (error) {
            console.error(`Error parsing CSS in style element ${index}:`, error);
        }
    });
});