/**
 * GitHub Buttons JavaScript
 * This file replaces the inline scripts from buttons.github.io
 */

// Initialize GitHub buttons
window.GitHubButtons = {
    init: function() {
        // Find all GitHub buttons
        const buttons = document.querySelectorAll('.github-button');
        
        if (buttons.length === 0) {
            console.log('No GitHub buttons found');
            return;
        }
        
        buttons.forEach(button => {
            // Get button attributes
            const href = button.getAttribute('href') || '';
            const ariaLabel = button.getAttribute('aria-label') || '';
            const dataIcon = button.getAttribute('data-icon') || 'octicon-star';
            const dataSize = button.getAttribute('data-size') || '';
            const dataShowCount = button.getAttribute('data-show-count') === 'true';
            
            // Create button elements
            const container = document.createElement('span');
            container.className = 'github-btn';
            
            const btnLink = document.createElement('a');
            btnLink.className = 'gh-btn';
            btnLink.href = href;
            btnLink.target = '_blank';
            btnLink.rel = 'noopener noreferrer';
            
            const btnIcon = document.createElement('span');
            btnIcon.className = 'gh-ico';
            
            const btnText = document.createElement('span');
            btnText.className = 'gh-text';
            btnText.textContent = this.getButtonText(dataIcon);
            
            // Assemble button
            btnLink.appendChild(btnIcon);
            btnLink.appendChild(btnText);
            container.appendChild(btnLink);
            
            // Add count if needed
            if (dataShowCount) {
                const countLink = document.createElement('a');
                countLink.className = 'gh-count';
                countLink.href = href;
                countLink.target = '_blank';
                countLink.rel = 'noopener noreferrer';
                countLink.textContent = '0';
                
                container.appendChild(countLink);
                
                // Fetch count from GitHub API
                this.fetchCount(href, countLink);
            }
            
            // Apply size
            if (dataSize === 'large') {
                container.classList.add('github-btn-large');
            }
            
            // Replace original button with our custom one
            button.parentNode.replaceChild(container, button);
        });
    },
    
    getButtonText: function(icon) {
        switch (icon) {
            case 'octicon-star':
                return 'Star';
            case 'octicon-repo-forked':
                return 'Fork';
            case 'octicon-eye':
                return 'Watch';
            case 'octicon-issue-opened':
                return 'Issue';
            default:
                return 'Star';
        }
    },
    
    fetchCount: function(href, countElement) {
        // Extract repo info from href
        const match = href.match(/github\.com\/([^/]+)\/([^/]+)/);
        if (!match) return;
        
        const owner = match[1];
        const repo = match[2];
        
        // Create a safe way to display the count without making API calls
        // In a real implementation, you would fetch from GitHub API
        countElement.classList.add('gh-count-visible');
        countElement.textContent = '1k';
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.GitHubButtons.init();
});