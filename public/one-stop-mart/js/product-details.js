/* ============================================
   ONE STOP MART - SCOPED PRODUCT DETAILS JAVASCRIPT (PHASE 4)
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // Scoped tab selector buttons and panels
    const tabList = document.querySelector('.osm-product-tab-list');
    const tabs = tabList ? tabList.querySelectorAll('.osm-product-tab') : [];
    const panels = document.querySelectorAll('.osm-product-tab-panel');

    if (tabList && tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                const targetId = this.getAttribute('aria-controls');
                
                // Reset tabs active states
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                
                // Hide all panels
                panels.forEach(p => {
                    p.classList.remove('active');
                    p.setAttribute('aria-hidden', 'true');
                });

                // Activate selected tab and panel
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                
                const targetPanel = document.getElementById(targetId);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                    targetPanel.setAttribute('aria-hidden', 'false');
                }
            });
        });
    }
});
