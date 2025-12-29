
// Force show Dashboard button on homepage
(function() {
    // Wait for page to load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔧 Enabling Dashboard button...');
        
        // Force show dashboard
        const dashboardNav = document.getElementById('nav-dashboard');
        if (dashboardNav) {
            dashboardNav.style.display = 'block';
            console.log('✅ Dashboard button enabled');
        }
        
        // Hide login button
        const loginNav = document.getElementById('nav-login');
        if (loginNav) {
            loginNav.style.display = 'none';
        }
        
        // Show logout button
        const logoutNav = document.getElementById('nav-logout');
        if (logoutNav) {
            logoutNav.style.display = 'block';
        }
        
        // Add admin styling
        const style = document.createElement('style');
        style.textContent = `
            #nav-dashboard { display: block !important; }
            .dashboard-link { 
                color: #3498db !important; 
                font-weight: 600 !important;
                background: rgba(52, 152, 219, 0.1) !important;
                padding: 8px 16px !important;
                border-radius: 6px !important;
                transition: all 0.3s ease !important;
            }
            .dashboard-link:hover {
                background: #3498db !important;
                color: white !important;
                transform: translateY(-1px) !important;
            }
        `;
        document.head.appendChild(style);
        
        console.log('🎉 Dashboard button is now visible on homepage!');
    });
})();
