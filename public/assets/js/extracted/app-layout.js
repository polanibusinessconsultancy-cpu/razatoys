/**
 * App Layout JavaScript functionality
 */

// CSRF Token refresh functionality
function initializeCSRFRefresh() {
    // Refresh CSRF token every 20 minutes (1200000 ms)
    setInterval(function () {
        fetch(window.dashboardRoute || '/dashboard', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                return response.text();
            }
            throw new Error('Session refresh failed');
        }).then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('meta[name="csrf-token"]').content;
            document.querySelector('meta[name="csrf-token"]').content = newToken;
            console.log('CSRF token refreshed');
        }).catch(error => {
            console.error('Error refreshing session:', error);
        });
    }, 1200000); // 20 minutes
}

// Sidebar color functionality
function sidebarColor(element) {
    var color = element.getAttribute('data-color');
    
    // Get all color buttons in the same group
    var parent = element.parentElement.children;
    
    // Remove active class from all buttons
    for (var i = 0; i < parent.length; i++) {
        parent[i].classList.remove('active');
    }
    
    // Add active class to clicked button
    element.classList.add('active');
    
    // Get the sidebar element
    var sidebar = document.querySelector('.sidenav');
    if (sidebar) {
        // Remove all color classes
        sidebar.classList.remove(
            'bg-gradient-primary',
            'bg-gradient-info',
            'bg-gradient-success',
            'bg-gradient-warning',
            'bg-gradient-danger',
            'bg-gradient-dark'
        );
        
        // Add the selected color class
        sidebar.classList.add('bg-gradient-' + color);
        
        // Update data attribute for consistency
        sidebar.setAttribute('data-color', color);
        
        // Update sidenav card icon if it exists
        if (document.querySelector('#sidenavCardIcon')) {
            var sidenavCardIcon = document.querySelector('#sidenavCardIcon');
            sidenavCardIcon.className = ''; // Clear all classes
            sidenavCardIcon.classList.add('text-' + color);
        }
    }
    
    console.log('Sidebar color changed to:', color);
}

// Sidebar type functionality
function sidebarType(element) {
    var type = element.getAttribute('data-class');
    
    // Get all type buttons in the same group
    var parent = element.parentElement.children;
    var types = [];
    
    // Remove active class from all buttons and collect all possible types
    for (var i = 0; i < parent.length; i++) {
        parent[i].classList.remove('active');
        types.push(parent[i].getAttribute('data-class'));
    }
    
    // Add active class to clicked button
    element.classList.add('active');
    
    // Get the sidebar element
    var sidebar = document.querySelector('.sidenav');
    if (sidebar) {
        // Remove all type classes
        for (var i = 0; i < types.length; i++) {
            sidebar.classList.remove(types[i]);
        }
        
        // Add the selected type class
        sidebar.classList.add(type);
    }
    
    console.log('Sidebar type changed to:', type);
}

// Navbar fixed functionality
function navbarFixed(element) {
    var isFixed = element.checked;
    // Implementation for navbar fixed toggle
    console.log('Navbar fixed:', isFixed);
}

// Swiper initialization - only runs if Swiper library is loaded
function initializeSwiper() {
    // Check if Swiper library is available before trying to use it
    if (typeof Swiper === 'undefined') {
        // Swiper not loaded - this is expected on pages that don't need it
        return;
    }

    if (document.getElementsByClassName('mySwiper').length > 0) {
        var swiper = new Swiper(".mySwiper", {
            effect: "cards",
            grabCursor: true,
            initialSlide: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
}

// Perfect Scrollbar initialization
function initializePerfectScrollbar() {
    // Disable Perfect Scrollbar initialization to prevent CSP violations
    // Perfect Scrollbar adds inline styles which violate CSP when nonces are used
    
    // Instead, add CSS classes to scrollable elements
    const scrollableElements = document.querySelectorAll('.ps');
    if (scrollableElements.length > 0) {
        scrollableElements.forEach(element => {
            // Add CSS class for custom scrollbar styling
            element.classList.add('custom-scrollbar');
            console.log('✅ Custom scrollbar class added to element:', element);
        });
    } else {
        console.log('ℹ️ No .ps elements found for scrollbar styling');
    }
    
    // Original code (commented out to prevent CSP violations)
    /*
    if (scrollableElements.length > 0 && typeof PerfectScrollbar !== 'undefined') {
        scrollableElements.forEach(element => {
            try {
                new PerfectScrollbar(element);
                console.log('✅ Perfect scrollbar initialized for element:', element);
            } catch (error) {
                console.warn('⚠️ Failed to initialize perfect scrollbar:', error);
            }
        });
    } else if (scrollableElements.length === 0) {
        console.log('ℹ️ No .ps elements found for perfect scrollbar');
    } else {
        console.warn('⚠️ PerfectScrollbar library not loaded');
    }
    */
}

// Chart initialization - only runs if Chart.js library is loaded
function initializeCharts() {
    // Check if Chart.js library is available before trying to use it
    if (typeof Chart === 'undefined') {
        // Chart.js not loaded - this is expected on pages that don't need it
        return;
    }
    initializeBarChart();
    initializeLineChart();
}

// Bar chart initialization
function initializeBarChart() {
    var ctx = document.getElementById("chart-bars");
    if (ctx && typeof Chart !== 'undefined') {
        ctx = ctx.getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"],
                datasets: [{
                        label: "Sales",
                        tension: 0.4,
                        borderWidth: 0,
                        borderSkipped: false,
                        backgroundColor: "#2ca8ff",
                        data: [450, 200, 100, 220, 500, 100, 400, 230, 500, 200],
                        maxBarThickness: 6
                    },
                    {
                        label: "Sales",
                        tension: 0.4,
                        borderWidth: 0,
                        borderSkipped: false,
                        backgroundColor: "#7c3aed",
                        data: [200, 300, 200, 420, 400, 200, 300, 430, 400, 300],
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1e293b',
                        bodyColor: '#1e293b',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        usePointStyle: true
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        stacked: true,
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [4, 4],
                        },
                        ticks: {
                            beginAtZero: true,
                            padding: 10,
                            font: {
                                size: 12,
                                family: "Noto Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#64748B"
                        },
                    },
                    x: {
                        stacked: true,
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family: "Noto Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#64748B"
                        },
                    },
                },
            },
        });
    }
}

// Line chart initialization
function initializeLineChart() {
    var ctx2 = document.getElementById("chart-line");
    if (ctx2 && typeof Chart !== 'undefined') {
        ctx2 = ctx2.getContext("2d");
        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(45,168,255,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(45,168,255,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(45,168,255,0)'); //blue colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(119,77,211,0.4)');
        gradientStroke2.addColorStop(0.7, 'rgba(119,77,211,0.1)');
        gradientStroke2.addColorStop(0, 'rgba(119,77,211,0)'); //purple colors

        new Chart(ctx2, {
            plugins: [{
                beforeInit(chart) {
                    const originalFit = chart.legend.fit;
                    chart.legend.fit = function fit() {
                        originalFit.bind(chart.legend)();
                        this.height += 40;
                    }
                },
            }],
            type: "line",
            data: {
                labels: ["Aug 18", "Aug 19", "Aug 20", "Aug 21", "Aug 22", "Aug 23", "Aug 24", "Aug 25", "Aug 26",
                    "Aug 27", "Aug 28", "Aug 29", "Aug 30", "Aug 31", "Sept 01", "Sept 02", "Sept 03", "Aug 22",
                    "Sept 04", "Sept 05", "Sept 06", "Sept 07", "Sept 08", "Sept 09"
                ],
                datasets: [{
                        label: "Volume",
                        tension: 0,
                        borderWidth: 2,
                        pointRadius: 3,
                        borderColor: "#2ca8ff",
                        pointBorderColor: '#2ca8ff',
                        pointBackgroundColor: '#2ca8ff',
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: [2828, 1291, 3360, 3223, 1630, 980, 2059, 3092, 1831, 1842, 1902, 1478, 1123,
                            2444, 2636, 2593, 2885, 1764, 898, 1356, 2573, 3382, 2858, 4228
                        ],
                        maxBarThickness: 6

                    },
                    {
                        label: "Trade",
                        tension: 0,
                        borderWidth: 2,
                        pointRadius: 3,
                        borderColor: "#832bf9",
                        pointBorderColor: '#832bf9',
                        pointBackgroundColor: '#832bf9',
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: [2797, 2182, 1069, 2098, 3309, 3881, 2059, 3239, 6215, 2185, 2115, 5430, 4648,
                            2444, 2161, 3018, 1153, 1068, 2192, 1152, 2129, 1396, 2067, 1215, 712, 2462,
                            1669, 2360, 2787, 861
                        ],
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 6,
                            boxHeight: 6,
                            padding: 20,
                            pointStyle: 'circle',
                            borderRadius: 50,
                            usePointStyle: true,
                            font: {
                                weight: 400,
                            },
                        },
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1e293b',
                        bodyColor: '#1e293b',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        pointRadius: 2,
                        usePointStyle: true,
                        boxWidth: 8,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [4, 4]
                        },
                        ticks: {
                            callback: function(value, index, ticks) {
                                return parseInt(value).toLocaleString() + ' EUR';
                            },
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 12,
                                family: "Noto Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#64748B"
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [4, 4]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 12,
                                family: "Noto Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#64748B"
                        }
                    },
                },
            },
        });
    }
}

// Sidenav scrollbar initialization
function initializeSidenavScrollbar() {
    // Use CSS-based scrollbar instead of JavaScript-based scrollbar
    const sidenavElement = document.querySelector('#sidenav-scrollbar');

    if (sidenavElement) {
        // Add custom scrollbar class
        sidenavElement.classList.add('custom-scrollbar');
        console.log('✅ Sidenav custom scrollbar class added');
    } else {
        console.log('ℹ️ No #sidenav-scrollbar element found');
    }
    
    // Original code (commented out to prevent CSP violations)
    /*
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && sidenavElement) {
        var options = {
            damping: '0.5'
        }
        try {
            if (typeof Scrollbar !== 'undefined') {
                Scrollbar.init(sidenavElement, options);
                console.log('✅ Sidenav scrollbar initialized');
            } else {
                console.warn('⚠️ Scrollbar library not available');
            }
        } catch (e) {
            console.warn("⚠️ Perfect scrollbar initialization failed:", e);
        }
    }
    */
}

// Corporate UI Dashboard script loader
function loadCorporateUIScript() {
    // Only load corporate-ui-dashboard.min.js if needed elements exist
    if (document.querySelector('.sidenav') || document.querySelector('.fixed-plugin')) {
        const script = document.createElement('script');
        script.src = window.corporateUIScriptPath || '/assets/js/corporate-ui-dashboard.min.js?v=1.0.0';
        script.defer = true;
        script.onerror = function() {
            console.warn("Failed to load corporate-ui-dashboard.min.js");
        };
        document.body.appendChild(script);
    }
}

// Event delegation for onclick handlers
function initializeEventDelegation() {
    document.addEventListener('click', function(e) {
        // Handle sidebar color changes
        if (e.target.matches('[data-color]')) {
            e.preventDefault();
            sidebarColor(e.target);
        }
        
        // Handle sidebar type changes
        if (e.target.matches('[data-class]')) {
            e.preventDefault();
            sidebarType(e.target);
        }
        
        // Handle navbar fixed toggle
        if (e.target.matches('#navbarFixed')) {
            navbarFixed(e.target);
        }
        
        // Handle switch-trigger background-color link clicks
        if (e.target.closest('.switch-trigger.background-color')) {
            e.preventDefault();
        }
        
        // Handle all javascript: links to prevent CSP violations
        const link = e.target.closest('a[href^="#"]') || e.target.closest('a[href="javascript:"]') || e.target.closest('a[href="javascript:void(0)"]') || e.target.closest('a[href="javascript:;"]');
        if (link) {
            e.preventDefault();
        }
    });
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeCSRFRefresh();
    initializeSwiper();
    initializePerfectScrollbar();
    initializeCharts();
    initializeSidenavScrollbar();
    loadCorporateUIScript();
    initializeEventDelegation();
});