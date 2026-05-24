<?php
// Navigation Bar Component
// Include this file in your pages: <?php include 'navbar.php'; ?>

// Get user information from session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 
           (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 
              (isset($_SESSION['email']) ? $_SESSION['email'] : 'guest@library.com');
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Member';
$notifications_count = rand(2, 8);

// Determine active page for highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Advanced Navigation Bar -->
<nav class="advanced-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100">
            <!-- Brand Logo -->
            <a href="dashboard.php" class="navbar-brand-custom">
                <div class="navbar-logo">
                    <i class="bi bi-book-half"></i>
                </div>
                <div class="brand-text">
                    <h3>SmartLib</h3>
                    <p>Library Management System</p>
                </div>
            </a>
            
            <!-- Search Bar (Desktop) -->
            <div class="navbar-search d-none d-lg-block">
                <input type="text" class="search-input" placeholder="Search books, members..." id="globalSearch">
                <button class="search-btn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            
            <!-- User Dropdown & Mobile Toggle -->
            <div class="d-flex align-items-center gap-3">
                <!-- User Dropdown -->
                <div class="user-dropdown-custom">
                    <div class="user-trigger">
                        <div class="user-avatar-small">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="user-info-small d-none d-md-block">
                            <div class="user-name-small"><?php echo htmlspecialchars($username); ?></div>
                            <div class="user-role-small"><?php echo htmlspecialchars($user_role); ?></div>
                        </div>
                        <i class="bi bi-chevron-down d-none d-md-block"></i>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div class="user-dropdown-menu">
                        <div class="dropdown-header-custom">
                            <div class="dropdown-user-name"><?php echo htmlspecialchars($username); ?></div>
                            <div class="dropdown-user-email"><?php echo htmlspecialchars($user_email); ?></div>
                            <div class="mt-2">
                                <span class="badge bg-primary"><?php echo htmlspecialchars($user_role); ?></span>
                            </div>
                        </div>
                        <div class="dropdown-divider-custom"></div>
                        
                        <a href="#" class="dropdown-item-custom" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                        
                        <a href="#" class="dropdown-item-custom" data-bs-toggle="modal" data-bs-target="#settingsModal">
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                        
                        <a href="#" class="dropdown-item-custom" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                            <span class="badge bg-danger ms-auto" id="navNotificationBadge"><?php echo $notifications_count; ?></span>
                        </a>
                        
                        <div class="dropdown-divider-custom"></div>
                        
                        <div class="dropdown-item-custom" id="themeToggle">
                            <i class="bi bi-moon-stars"></i>
                            <span>Dark Mode</span>
                            <div class="form-check form-switch ms-auto">
                                <input class="form-check-input" type="checkbox" id="navDarkModeSwitch">
                            </div>
                        </div>
                        
                        <div class="dropdown-divider-custom"></div>
                        
                        <a href="logout.php" class="dropdown-item-custom text-danger">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
                
                <!-- Mobile Search Toggle -->
                <button class="btn btn-link text-white d-lg-none" id="mobileSearchToggle">
                    <i class="bi bi-search fs-5"></i>
                </button>
                
                <!-- Hamburger Toggle -->
                <div class="navbar-toggler-custom d-lg-none">
                    <div class="toggler-icon"></div>
                    <div class="toggler-icon"></div>
                    <div class="toggler-icon"></div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Search Bar -->
        <div class="mobile-search-container d-lg-none" id="mobileSearchContainer" style="display: none;">
            <div class="input-group mt-3">
                <input type="text" class="form-control" placeholder="Search books, members..." id="mobileSearchInput">
                <button class="btn btn-light" id="mobileSearchBtn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        
        <!-- Navigation Links -->
        <div class="navbar-collapse-custom">
            <ul class="navbar-nav-custom">
                <li class="nav-item-custom">
                    <a class="nav-link-custom <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                        <span class="tooltip-text">Overview</span>
                    </a>
                </li>
                
                <li class="nav-item-custom">
                    <a class="nav-link-custom <?php echo $current_page == 'books.php' ? 'active' : ''; ?>" href="books.php">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Books Collection</span>
                        <span class="tooltip-text">Manage books</span>
                    </a>
                </li>
                
                <li class="nav-item-custom">
                    <a class="nav-link-custom <?php echo $current_page == 'members.php' ? 'active' : ''; ?>" href="members.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Active Members</span>
                        <span class="tooltip-text">Member management</span>
                    </a>
                </li>
                
                <li class="nav-item-custom">
                    <a class="nav-link-custom <?php echo $current_page == 'transactions.php' ? 'active' : ''; ?>" href="transactions.php">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Transactions</span>
                        <span class="tooltip-text">Borrow/Return</span>
                    </a>
                </li>
                
                <li class="nav-item-custom">
                    <a class="nav-link-custom <?php echo $current_page == 'insights.php' ? 'active' : ''; ?>" href="insights.php">
                        <i class="bi bi-graph-up"></i>
                        <span>Insights</span>
                        <span class="tooltip-text">Analytics</span>
                    </a>
                </li>
                
                <!-- Additional Links for Mobile -->
                <li class="nav-item-custom d-lg-none">
                    <div class="dropdown-divider-custom my-2"></div>
                </li>
                
                <li class="nav-item-custom d-lg-none">
                    <a href="#" class="nav-link-custom" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="bi bi-person"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                
                <li class="nav-item-custom d-lg-none">
                    <a href="#" class="nav-link-custom" data-bs-toggle="modal" data-bs-target="#settingsModal">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Include CSS and JS -->
<link rel="stylesheet" href="css/navbar-style.css">
<script src="js/navbar.js"></script>

<script>
// Mobile search toggle
document.getElementById('mobileSearchToggle')?.addEventListener('click', function() {
    const container = document.getElementById('mobileSearchContainer');
    if (container.style.display === 'none') {
        container.style.display = 'block';
        document.getElementById('mobileSearchInput')?.focus();
    } else {
        container.style.display = 'none';
    }
});

// Sync dark mode with navbar
document.getElementById('navDarkModeSwitch')?.addEventListener('change', function(e) {
    const newTheme = e.target.checked ? 'dark' : 'light';
    document.body.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('libTheme', newTheme);
    
    // Sync with main dark mode switch if exists
    const mainSwitch = document.getElementById('darkModeSwitch');
    if (mainSwitch) mainSwitch.checked = e.target.checked;
});

// Initialize dark mode switch state
const savedTheme = localStorage.getItem('libTheme') || 'light';
const navSwitch = document.getElementById('navDarkModeSwitch');
if (navSwitch) {
    navSwitch.checked = savedTheme === 'dark';
}
</script>