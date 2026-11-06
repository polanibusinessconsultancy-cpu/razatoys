/**
 * Admin Dashboard JavaScript functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add hover effect to stat cards
    const statCards = document.querySelectorAll('.card-stats');
    if (statCards.length > 0) {
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
    }
    
    // Add click effect to quick links buttons
    const quickLinkButtons = document.querySelectorAll('.quick-links-btn');
    if (quickLinkButtons.length > 0) {
        quickLinkButtons.forEach(button => {
            button.addEventListener('mousedown', function() {
                this.style.transform = 'translateY(2px)';
            });
            
            button.addEventListener('mouseup', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    }
    
    // Initialize any data visualization if needed
    initializeCharts();
});

/**
 * Initialize charts and data visualizations
 */
function initializeCharts() {
    // This function would contain chart initialization code
    // For now, it's just a placeholder
    console.log('Charts initialized');
}