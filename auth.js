// auth.js - Shared authentication for all SmartLib pages

// Check if user is logged in
function checkAuth() {
    const isLoggedIn = sessionStorage.getItem('logged_in') === 'true';
    
    if (!isLoggedIn) {
        window.location.href = 'index.html';
        return false;
    }
    return true;
}

// Get current logged in user data
function getCurrentUser() {
    return {
        id: sessionStorage.getItem('user_id'),
        name: sessionStorage.getItem('user_name') || 'User',
        email: sessionStorage.getItem('user_email') || 'user@library.com',
        role: sessionStorage.getItem('user_role') || 'Member',
        lastActivity: sessionStorage.getItem('last_activity')
    };
}

// Update user profile on any page
function updateUserProfileOnPage() {
    const user = getCurrentUser();
    
    // Update all common user name elements
    const userNameElements = ['userName', 'welcomeUserName', 'profileName', 'modalProfileName', 'dropdownUserName', 'displayName'];
    userNameElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.textContent = user.name;
    });
    
    // Update user role elements
    const userRoleElements = ['userRole', 'modalProfileRole', 'displayRole', 'dropdownUserRole'];
    userRoleElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            if (id === 'userRole' || id === 'dropdownUserRole') {
                element.innerHTML = `<i class="bi bi-briefcase"></i> ${user.role}`;
            } else {
                element.textContent = user.role;
            }
        }
    });
    
    // Update email elements
    const emailElements = ['dropdownUserEmail', 'displayEmail', 'modalDisplayEmail', 'profileDetails'];
    emailElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            if (id === 'profileDetails') {
                element.textContent = `${user.role} · ${user.email}`;
            } else {
                element.textContent = user.email;
            }
        }
    });
    
    // Update date/time if the element exists
    const dateTimeElement = document.getElementById('currentDateTime');
    if (dateTimeElement) {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('en-US', options);
        dateTimeElement.innerHTML = `<i class="bi bi-calendar3"></i> ${dateStr} | <i class="bi bi-clock"></i> Welcome back, ${user.name}!`;
    }
}

// Logout function - preserves registered users
function logoutUser() {
    sessionStorage.clear();
    localStorage.removeItem('library_remember_token');
    localStorage.removeItem('remembered_email');
    window.location.href = 'index.html';
}

// Attach logout handlers to any page
function attachLogoutHandlers() {
    const logoutLinks = document.querySelectorAll('a[href="index.html"], .logout-btn, #logoutBtn, [data-logout], .btn-outline-danger, #logoutDropdown');
    
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            logoutUser();
        });
    });
}

// Initialize page authentication
function initPageAuth() {
    if (checkAuth()) {
        updateUserProfileOnPage();
        attachLogoutHandlers();
    }
}

// Run initialization when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageAuth);
} else {
    initPageAuth();
}