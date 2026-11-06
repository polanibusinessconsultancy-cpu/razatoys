/**
 * Dashboard route for CSRF refresh
 */
if (typeof dashboardRouteUrl === 'undefined') {
    window.dashboardRouteUrl = '/dashboard'; // fallback to default route
}
if (typeof corporateUIScriptPath === 'undefined') {
    window.corporateUIScriptPath = '/assets/js/corporate-ui-dashboard.min.js?v=1.0.0'; // fallback to default path
}
window.dashboardRoute = dashboardRouteUrl;
window.corporateUIScriptPath = corporateUIScriptPath;