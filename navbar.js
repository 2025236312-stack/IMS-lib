// ============================================
// NAVBAR FUNCTIONALITY - Mobile Responsive
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const toggler = document.querySelector('.navbar-toggler-custom');
    const navbarCollapse = document.querySelector('.navbar-collapse-custom');
    
    if (toggler && navbarCollapse) {
        toggler.addEventListener('click', function() {
            this.classList.toggle('active');
            navbarCollapse.classList.toggle('show');
        });
    }
    
    // Mobile sidebar toggle button (add to your pages)
    const sidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 1024) {
            if (sidebar && !sidebar.contains(event.target) && 
                !sidebarToggle?.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
    
    // Global search functionality
    const globalSearch = document.getElementById('globalSearch');
    if (globalSearch) {
        globalSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = `books.php?search=${encodeURIComponent(searchTerm)}`;
                }
            }
        });
    }
});