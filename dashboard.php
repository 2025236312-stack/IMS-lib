<?php
// dashboard.php - Advanced Main Dashboard with Expert Design
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Get user information from session with fallbacks
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 
           (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 
              (isset($_SESSION['email']) ? $_SESSION['email'] : 'user@library.com');
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 
             (isset($_SESSION['role']) ? $_SESSION['role'] : 'Member');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A';

// Generate random notifications count
$notifications_count = rand(3, 12);

// Sample notifications data
$notifications = [
    ['type' => 'success', 'icon' => 'bi-check-circle-fill', 'title' => 'New Book Added', 'message' => '"The Midnight Library" has been added to the collection.', 'time' => '5 minutes ago', 'read' => false],
    ['type' => 'warning', 'icon' => 'bi-exclamation-triangle-fill', 'title' => 'Overdue Books', 'message' => '3 books are overdue. Please send reminders to members.', 'time' => '1 hour ago', 'read' => false],
    ['type' => 'info', 'icon' => 'bi-person-plus', 'title' => 'New Member Registered', 'message' => 'Sarah Johnson has joined the library as a Premium member.', 'time' => '3 hours ago', 'read' => false],
    ['type' => 'success', 'icon' => 'bi-arrow-return-left', 'title' => 'Book Returned', 'message' => '"Atomic Habits" was returned in good condition.', 'time' => '5 hours ago', 'read' => true],
    ['type' => 'danger', 'icon' => 'bi-exclamation-circle', 'title' => 'System Update', 'message' => 'Scheduled maintenance tonight at 2:00 AM.', 'time' => 'Yesterday', 'read' => false],
    ['type' => 'info', 'icon' => 'bi-graph-up', 'title' => 'Monthly Report Ready', 'message' => 'March 2025 library statistics report is available for download.', 'time' => 'Yesterday', 'read' => true],
    ['type' => 'success', 'icon' => 'bi-trophy', 'title' => 'Achievement Unlocked', 'message' => 'Library reached 10,000 total checkouts this month!', 'time' => '2 days ago', 'read' => true],
];

// Update last activity timestamp
$_SESSION['last_activity'] = time();

require_once 'config/data.php';

// Report data for generation
$report_data = [
    'total_books' => 40,
    'active_members' => 20,
    'books_borrowed' => 10,
    'pending_returns' => 4,
    'generated_date' => date('Y-m-d H:i:s'),
    'generated_by' => $username
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard | SmartLib - Library Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS - ALL STYLES NOW IN EXTERNAL FILES -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body data-bs-theme="light">
<div class="wrapper">
    <!-- Sidebar Navigation -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2">
                <div class="logo-icon-small">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <h3>SmartLib</h3>
                    <p>Library Management System</p>
                </div>
            </div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i> 
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="books.php">
                    <i class="bi bi-journal-bookmark-fill"></i> 
                    <span>Books Collection</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="members.php">
                    <i class="bi bi-people-fill"></i> 
                    <span>Active Members</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="transactions.php">
                    <i class="bi bi-arrow-left-right"></i> 
                    <span>Transactions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="insights.php">
                    <i class="bi bi-graph-up"></i> 
                    <span>Insights</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <div class="theme-toggle-wrapper">
                <i class="bi bi-sun-fill"></i>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                </div>
                <i class="bi bi-moon-fill"></i>
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 mt-2">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid px-4 py-3">
            
            <!-- Enhanced Header with User Profile Dropdown -->
            <div class="dashboard-header">
                <div class="header-title">
                    <h2 class="page-title">Library Dashboard</h2>
                    <p class="header-date">
                        <i class="bi bi-calendar3"></i> <?php echo date('l, F j, Y'); ?> | 
                        <i class="bi bi-clock"></i> Welcome back!
                    </p>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <div class="user-avatar-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
                            <div class="user-role">
                                <i class="bi bi-briefcase"></i> <?php echo htmlspecialchars($user_role); ?>
                            </div>
                        </div>
                        <div class="avatar-circle">
                            <i class="bi bi-person-fill avatar-icon"></i>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <div class="dropdown-user-name"><?php echo htmlspecialchars($username); ?></div>
                            <div class="dropdown-user-email"><?php echo htmlspecialchars($user_email); ?></div>
                            <div class="dropdown-user-badge">
                                <span class="role-badge">
                                    <i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($user_role); ?>
                                </span>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person"></i> My Profile</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="bi bi-gear"></i> Settings</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#notificationsModal"><i class="bi bi-bell"></i> Notifications <span class="badge bg-danger ms-2" id="notificationCountBadge"><?php echo $notifications_count; ?></span></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Welcome Banner with Working View Updates Button -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <div>
                        <i class="bi bi-emoji-smile welcome-icon"></i>
                        <strong>Hello, <?php echo htmlspecialchars($username); ?>!</strong> 
                        Welcome back to your library dashboard. You have <span id="welcomeNotificationCount"><?php echo $notifications_count; ?></span> new notifications.
                    </div>
                    <a href="#" class="btn-update" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                        <i class="bi bi-bell"></i> View Updates
                        <?php if($notifications_count > 0): ?>
                        <span class="notification-badge"><?php echo $notifications_count; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-row">
                <div class="stat-card-wrapper">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="stat-content">
                                <div>
                                    <span class="stat-label">Total Books</span>
                                    <h2 class="stat-value">50</h2>
                                    <small class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> +5.2%</small>
                                </div>
                                <div class="stat-icon stat-icon-primary">
                                    <i class="bi bi-journal-bookmark-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card-wrapper">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="stat-content">
                                <div>
                                    <span class="stat-label">Active Members</span>
                                    <h2 class="stat-value">28</h2>
                                    <small class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> +12%</small>
                                </div>
                                <div class="stat-icon stat-icon-success">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card-wrapper">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="stat-content">
                                <div>
                                    <span class="stat-label">Books Borrowed</span>
                                    <h2 class="stat-value">10</h2>
                                    <small class="stat-trend trend-down"><i class="bi bi-arrow-down"></i> -3%</small>
                                </div>
                                <div class="stat-icon stat-icon-warning">
                                    <i class="bi bi-bookmark-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card-wrapper">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="stat-content">
                                <div>
                                    <span class="stat-label">Pending Returns</span>
                                    <h2 class="stat-value">4</h2>
                                    <small class="stat-trend trend-warning"><i class="bi bi-clock"></i> Due soon</small>
                                </div>
                                <div class="stat-icon stat-icon-info">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Library Sections Row -->
            <div class="content-grid">
                <div class="grid-col-4">
                    <div class="card section-card">
                        <div class="card-header-custom">
                            <i class="bi bi-bar-chart-steps"></i> Top Library Sections
                        </div>
                        <div class="card-body">
                            <ul class="section-list">
                                <li class="section-item">Fiction <span class="section-badge section-badge-primary">5,678 <i class="bi bi-arrow-up-short"></i> +12%</span></li>
                                <li class="section-item">Non-Fiction <span class="section-badge section-badge-secondary">2,345 <i class="bi bi-arrow-down-short"></i> -2%</span></li>
                                <li class="section-item">Reference <span class="section-badge section-badge-info">3,456 <i class="bi bi-arrow-up-short"></i> +6%</span></li>
                                <li class="section-item">E-Resources <span class="section-badge section-badge-success">1,132 <i class="bi bi-arrow-up-short"></i> +3%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="grid-col-4">
                    <div class="card profile-card">
                        <div class="card-header-custom">
                            <i class="bi bi-person-badge"></i> Librarian Profile
                        </div>
                        <div class="card-body text-center">
                            <div class="profile-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h5 class="profile-name"><?php echo htmlspecialchars($username); ?></h5>
                            <p class="profile-details"><?php echo htmlspecialchars($user_role); ?> · <?php echo htmlspecialchars($user_email); ?></p>
                            <hr class="profile-divider">
                            <div class="profile-stats">
                                <div class="profile-stat">
                                    <small>Today's Checkouts</small>
                                    <h6 class="stat-success">148 <i class="bi bi-arrow-up-short"></i> +7%</h6>
                                </div>
                                <div class="profile-stat">
                                    <small>Pending Returns</small>
                                    <h6 class="stat-warning">23 books</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid-col-4">
                    <div class="card engagement-card">
                        <div class="card-header-custom">
                            <i class="bi bi-graph-up"></i> Library Engagement
                        </div>
                        <div class="card-body">
                            <div class="engagement-item">
                                <div class="engagement-label"> New registrations <span>57%</span></div>
                                <div class="progress-bar-custom"><div class="progress-fill progress-fill-success" style="width:57%"></div></div>
                            </div>
                            <div class="engagement-item">
                                <div class="engagement-label"> Active Borrowers <span>32%</span></div>
                                <div class="progress-bar-custom"><div class="progress-fill progress-fill-info" style="width:32%"></div></div>
                            </div>
                            <div class="engagement-item">
                                <div class="engagement-label"> Newsletter Subs <span>31%</span></div>
                                <div class="progress-bar-custom"><div class="progress-fill progress-fill-warning" style="width:31%"></div></div>
                            </div>
                            <small class="engagement-note"><i class="bi bi-envelope"></i> We'll send email when new conversion occurs</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart and Statistics Row -->
            <div class="chart-row">
                <div class="chart-col">
                    <div class="card chart-card">
                        <div class="card-header-custom">
                            <i class="bi bi-people-fill"></i> Total Visitors (Monthly Library Footfall)
                        </div>
                        <div class="card-body">
                            <canvas id="visitorsLineChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="stats-col">
                    <div class="card session-card">
                        <div class="card-header-custom">
                            <i class="bi bi-clock-history"></i> Average Session Duration
                        </div>
                        <div class="card-body text-center">
                            <div class="session-value">55</div>
                            <p class="session-label">minutes average session</p>
                            <hr class="session-divider">
                            <div class="session-stats">
                                <div class="session-stat">
                                    <i class="bi bi-people"></i>
                                    <h5 class="mb-0">3,500</h5>
                                    <small>Total visitors</small>
                                </div>
                                <div class="session-stat">
                                    <i class="bi bi-person-plus"></i>
                                    <h5 class="mb-0">2,100</h5>
                                    <small>New visitors</small>
                                </div>
                            </div>
                            <div class="session-location">
                                <i class="bi bi-geo-alt-fill"></i> Top city: Melbourne, AUS
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- World Map Reference Row -->
            <div class="world-map-row">
                <div class="card world-map-card">
                    <div class="card-body">
                        <div class="world-map-content">
                            <div>
                                <i class="bi bi-pin-map-fill"></i>
                                <strong>Global Reach:</strong> Melbourne AUS, London UK, New York US, Tokyo JP
                            </div>
                            <div>
                                <span class="badge-global">Total visitors: 12.4k</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions-row">
                <div class="card actions-card">
                    <div class="card-header-custom">
                        <i class="bi bi-lightning-charge"></i> Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="actions-buttons">
                            <a href="books.php" class="action-btn action-btn-primary">
                                <i class="bi bi-journal-plus"></i> Add New Book
                            </a>
                            <a href="members.php" class="action-btn action-btn-success">
                                <i class="bi bi-person-plus"></i> Register Member
                            </a>
                            <a href="transactions.php" class="action-btn action-btn-info">
                                <i class="bi bi-arrow-left-right"></i> New Transaction
                            </a>
                            <a href="#" class="action-btn action-btn-secondary" id="generateReportBtn">
                                <i class="bi bi-printer"></i> Generate Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <span>© 2025 SmartLib System — Integrated Library Management | v3.0 | Secure Session</span>
            </div>
        </footer>
    </main>
</div>

<!-- ============================================ -->
<!-- PROFILE MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <h5 class="modal-title text-white" id="profileModalLabel">
                    <i class="bi bi-person-circle"></i> My Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column - Avatar -->
                    <div class="col-md-4 text-center border-end">
                        <div class="profile-avatar-large">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h5 class="mt-3"><?php echo htmlspecialchars($username); ?></h5>
                        <p class="text-muted small"><?php echo htmlspecialchars($user_role); ?></p>
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-outline-primary btn-sm" id="changeAvatarBtn">
                                <i class="bi bi-camera"></i> Change Avatar
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="uploadPhotoBtn">
                                <i class="bi bi-upload"></i> Upload Photo
                            </button>
                        </div>
                    </div>
                    
                    <!-- Right Column - Profile Info -->
                    <div class="col-md-8">
                        <h6 class="mb-3"><i class="bi bi-info-circle"></i> Personal Information</h6>
                        <form id="profileForm">
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-4 text-muted">Full Name</div>
                                    <div class="col-8 fw-semibold" id="displayName"><?php echo htmlspecialchars($username); ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-4 text-muted">Email Address</div>
                                    <div class="col-8 fw-semibold" id="displayEmail"><?php echo htmlspecialchars($user_email); ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-4 text-muted">User Role</div>
                                    <div class="col-8"><span class="badge bg-primary"><?php echo htmlspecialchars($user_role); ?></span></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-4 text-muted">Member Since</div>
                                    <div class="col-8">January 15, 2025</div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-4 text-muted">Last Login</div>
                                    <div class="col-8"><?php echo date('F j, Y g:i A'); ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-4 text-muted">Account Status</div>
                                    <div class="col-8"><span class="badge bg-success">Active</span></div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm" id="editProfileBtn">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </button>
                            <button class="btn btn-danger btn-sm ms-2" id="changePasswordBtn">
                                <i class="bi bi-key"></i> Change Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal (Inner) -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileFormSubmit">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editFullName" value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="editEmail" value="<?php echo htmlspecialchars($user_email); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number (Optional)</label>
                        <input type="tel" class="form-control" id="editPhone" placeholder="+60 XX XXX XXXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" rows="3" id="editBio" placeholder="Tell us about yourself..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-key"></i> Change Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required>
                        <div class="password-strength mt-1" id="pwStrength"></div>
                        <small class="text-muted">Minimum 8 characters with uppercase, lowercase, and number</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                        <div class="small" id="pwMatch"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- SETTINGS MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <h5 class="modal-title text-white" id="settingsModalLabel">
                    <i class="bi bi-gear-fill"></i> Settings
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-4" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                            <i class="bi bi-sliders2"></i> Preferences
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notificationsSettings" type="button" role="tab">
                            <i class="bi bi-bell"></i> Notifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                            <i class="bi bi-shield-lock"></i> Security
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                            <i class="bi bi-activity"></i> Activity
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Preferences Tab -->
                    <div class="tab-pane fade show active" id="preferences" role="tabpanel">
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-display"></i> Display Settings
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Dark Mode</strong>
                                    <div class="text-muted small">Switch between light and dark theme</div>
                                </div>
                                <div class="form-check form-switch form-switch-large">
                                    <input class="form-check-input" type="checkbox" id="darkModeSetting" role="switch">
                                </div>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Compact View</strong>
                                    <div class="text-muted small">Reduce spacing for more content</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="compactViewSetting">
                                </div>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Animations</strong>
                                    <div class="text-muted small">Enable smooth animations and transitions</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="animationsSetting" checked>
                                </div>
                            </div>
                        </div>
                        
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-globe"></i> Language & Region
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Language</strong>
                                    <div class="text-muted small">Select your preferred language</div>
                                </div>
                                <select class="form-select w-auto" id="languageSetting">
                                    <option value="en">English (US)</option>
                                    <option value="ms">Bahasa Malaysia</option>
                                    <option value="zh">中文</option>
                                    <option value="ta">தமிழ்</option>
                                </select>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Time Zone</strong>
                                    <div class="text-muted small">Kuala Lumpur (GMT+8)</div>
                                </div>
                                <span class="badge bg-secondary">Auto-detected</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notifications Settings Tab -->
                    <div class="tab-pane fade" id="notificationsSettings" role="tabpanel">
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-envelope"></i> Email Notifications
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>New Book Alerts</strong>
                                    <div class="text-muted small">Get notified when new books are added</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="newBookAlerts" checked>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Due Date Reminders</strong>
                                    <div class="text-muted small">Receive reminders before books are due</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="dueDateReminders" checked>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Monthly Reports</strong>
                                    <div class="text-muted small">Receive monthly library statistics</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="monthlyReports">
                                </div>
                            </div>
                        </div>
                        
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-bell"></i> Push Notifications
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Browser Notifications</strong>
                                    <div class="text-muted small">Show desktop notifications</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Sound Alerts</strong>
                                    <div class="text-muted small">Play sound for important notifications</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="soundAlerts">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-shield-check"></i> Security Settings
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Two-Factor Authentication</strong>
                                    <div class="text-muted small">Add an extra layer of security</div>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" id="enable2FA">
                                    <i class="bi bi-shield-plus"></i> Enable 2FA
                                </button>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Session Management</strong>
                                    <div class="text-muted small">Active sessions on other devices</div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm" id="logoutAllDevices">
                                    <i class="bi bi-box-arrow-right"></i> Logout All Devices
                                </button>
                            </div>
                        </div>
                        
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-database"></i> Data Management
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Export Data</strong>
                                    <div class="text-muted small">Download your personal data</div>
                                </div>
                                <button class="btn btn-outline-info btn-sm" id="exportData">
                                    <i class="bi bi-download"></i> Export
                                </button>
                            </div>
                            <div class="setting-item">
                                <div>
                                    <strong>Delete Account</strong>
                                    <div class="text-muted small text-danger">Permanently delete your account</div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm" id="deleteAccount">
                                    <i class="bi bi-trash"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="settings-group">
                            <div class="settings-header">
                                <i class="bi bi-clock-history"></i> Recent Activity
                            </div>
                            <div id="activityLog">
                                <div class="activity-log-item">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-box-arrow-in-right text-success"></i> Logged in</span>
                                        <small class="text-muted"><?php echo date('g:i A'); ?></small>
                                    </div>
                                    <small class="text-muted">IP: 192.168.1.1</small>
                                </div>
                                <div class="activity-log-item">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-book text-primary"></i> Viewed "Atomic Habits"</span>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                                <div class="activity-log-item">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-person-plus text-info"></i> Added new member</span>
                                        <small class="text-muted">Yesterday</small>
                                    </div>
                                </div>
                                <div class="activity-log-item">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-arrow-return-left text-warning"></i> Processed return</span>
                                        <small class="text-muted">Yesterday</small>
                                    </div>
                                </div>
                                <div class="activity-log-item">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-graph-up text-success"></i> Generated monthly report</span>
                                        <small class="text-muted">Mar 30, 2025</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSettingsBtn">
                    <i class="bi bi-save"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <h5 class="modal-title text-white" id="notificationsModalLabel">
                    <i class="bi bi-bell-fill"></i> Notifications
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <i class="bi bi-envelope"></i> <span id="unreadCount"><?php echo $notifications_count; ?></span> unread notifications
                    </div>
                    <button class="btn btn-sm btn-link text-primary mark-all-read" id="markAllReadBtn">
                        <i class="bi bi-check2-all"></i> Mark all as read
                    </button>
                </div>
                <div class="notifications-list" id="notificationsList">
                    <?php foreach($notifications as $index => $notification): ?>
                    <div class="notification-item p-3 border-bottom <?php echo !$notification['read'] ? 'notification-unread' : ''; ?>" data-notification-id="<?php echo $index; ?>">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bi <?php echo $notification['icon']; ?> fs-4 me-3" style="color: <?php 
                                    echo $notification['type'] == 'success' ? '#28a745' : 
                                        ($notification['type'] == 'warning' ? '#ffc107' : 
                                        ($notification['type'] == 'danger' ? '#dc3545' : '#667eea')); 
                                ?>;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($notification['title']); ?>
                                            <?php if(!$notification['read']): ?>
                                            <span class="notification-dot"></span>
                                            <?php endif; ?>
                                        </h6>
                                        <p class="mb-1 small"><?php echo htmlspecialchars($notification['message']); ?></p>
                                        <small class="text-muted"><i class="bi bi-clock"></i> <?php echo $notification['time']; ?></small>
                                    </div>
                                    <?php if(!$notification['read']): ?>
                                    <button class="btn btn-sm btn-outline-primary mark-read-btn" data-id="<?php echo $index; ?>">
                                        <i class="bi bi-check"></i> Mark read
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="viewAllNotifications">
                    <i class="bi bi-bell"></i> View All
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Report Preview Modal -->
<div class="modal fade" id="reportPreviewModal" tabindex="-1" aria-labelledby="reportPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <h5 class="modal-title text-white" id="reportPreviewModalLabel">
                    <i class="bi bi-file-text-fill"></i> Library Report Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reportPreviewContent">
                <!-- Report content will be loaded here -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Generating report...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="downloadReportBtn">
                    <i class="bi bi-download"></i> Download Report (PDF)
                </button>
                <button type="button" class="btn btn-primary" id="printReportBtn">
                    <i class="bi bi-printer"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass PHP variables to JavaScript
    const reportData = <?php echo json_encode($report_data); ?>;
    
    // Initialize Line Chart
    const ctx = document.getElementById('visitorsLineChart')?.getContext('2d');
    if(ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{ 
                    label: 'Library Visitors', 
                    data: [85, 92, 108, 115, 120, 132], 
                    borderColor: '#667eea', 
                    backgroundColor: 'rgba(102,126,234,0.05)', 
                    fill: true, 
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    // Dark Mode Toggle
    const toggleSwitch = document.getElementById('darkModeSwitch');
    const currentTheme = localStorage.getItem('libTheme') || 'light';
    document.body.setAttribute('data-bs-theme', currentTheme);
    if(toggleSwitch) {
        if(currentTheme === 'dark') toggleSwitch.checked = true;
        toggleSwitch.addEventListener('change', (e) => {
            const newTheme = e.target.checked ? 'dark' : 'light';
            document.body.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('libTheme', newTheme);
        });
    }
    
    // ============================================
    // REPORT GENERATION FUNCTIONALITY
    // ============================================
    
    let reportPreviewModal;
    
    function generateReportHTML() {
        const date = new Date();
        const formattedDate = date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        const formattedTime = date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        return `
            <div class="report-container" id="reportToPrint">
                <div class="report-header text-center mb-4">
                    <div class="report-logo">
                        <i class="bi bi-book-half" style="font-size: 48px; color: #667eea;"></i>
                    </div>
                    <h2 class="mt-2">SmartLib Library Management System</h2>
                    <h5>Library Statistics Report</h5>
                    <p class="text-muted">Generated on: ${formattedDate} at ${formattedTime}</p>
                    <hr>
                </div>
                
                <div class="report-summary mb-4">
                    <h4><i class="bi bi-bar-chart-steps"></i> Executive Summary</h4>
                    <p>This report provides a comprehensive overview of library operations and statistics for the current period. The library continues to show positive growth in key metrics including total books, active members, and circulation statistics.</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <div class="report-stat-icon">📚</div>
                            <div class="report-stat-value">${reportData.total_books}</div>
                            <div class="report-stat-label">Total Books</div>
                            <small class="text-success">↑ 5.2% from last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <div class="report-stat-icon">👥</div>
                            <div class="report-stat-value">${reportData.active_members}</div>
                            <div class="report-stat-label">Active Members</div>
                            <small class="text-success">↑ 12% from last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <div class="report-stat-icon">📖</div>
                            <div class="report-stat-value">${reportData.books_borrowed}</div>
                            <div class="report-stat-label">Books Borrowed</div>
                            <small class="text-danger">↓ 3% from last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <div class="report-stat-icon">⏰</div>
                            <div class="report-stat-value">${reportData.pending_returns}</div>
                            <div class="report-stat-label">Pending Returns</div>
                            <small class="text-warning">Due within 7 days</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="report-section">
                            <h5><i class="bi bi-pie-chart"></i> Library Sections Distribution</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Section</th><th>Books Count</th><th>Growth</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Fiction</td><td>5,678</td><td class="text-success">+12%</td></tr>
                                    <tr><td>Non-Fiction</td><td>2,345</td><td class="text-danger">-2%</td></tr>
                                    <tr><td>Reference</td><td>3,456</td><td class="text-success">+6%</td></tr>
                                    <tr><td>E-Resources</td><td>1,132</td><td class="text-success">+3%</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="report-section">
                            <h5><i class="bi bi-people"></i> Member Statistics</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Category</th><th>Count</th><th>Percentage</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Premium Members</td><td>245</td><td>32%</td></tr>
                                    <tr><td>Regular Members</td><td>412</td><td>54%</td></tr>
                                    <tr><td>Student Members</td><td>108</td><td>14%</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="report-section">
                            <h5><i class="bi bi-graph-up"></i> Monthly Visitor Trends</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Month</th><th>Visitors</th><th>Change</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>February</td><td>85</td><td class="text-success">+8%</td></tr>
                                    <tr><td>March</td><td>92</td><td class="text-success">+5%</td></tr>
                                    <tr><td>April</td><td>108</td><td class="text-success">+15%</td></tr>
                                    <tr><td>May</td><td>115</td><td class="text-success">+6%</td></tr>
                                    <tr><td>June</td><td>120</td><td class="text-success">+4%</td></tr>
                                    <tr><td>July</td><td>132</td><td class="text-success">+10%</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="report-footer mt-4 pt-3 text-center text-muted">
                    <hr>
                    <small>Generated by: ${reportData.generated_by} | System ID: LIB-${Date.now()}</small><br>
                    <small>© 2025 SmartLib System — Confidential Library Report</small>
                </div>
            </div>
        `;
    }
    
    function showReportPreview() {
        const modalElement = document.getElementById('reportPreviewModal');
        reportPreviewModal = new bootstrap.Modal(modalElement);
        
        const contentDiv = document.getElementById('reportPreviewContent');
        contentDiv.innerHTML = generateReportHTML();
        
        reportPreviewModal.show();
    }
    
    function downloadReport() {
        const reportContent = document.getElementById('reportPreviewContent').innerHTML;
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>SmartLib Library Report</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
                <style>
                    body {
                        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        padding: 40px;
                        background: white;
                    }
                    .report-stat-card {
                        background: #f8f9fa;
                        border-radius: 12px;
                        padding: 20px;
                        text-align: center;
                        margin-bottom: 20px;
                        border: 1px solid #e9ecef;
                    }
                    .report-stat-icon {
                        font-size: 32px;
                        margin-bottom: 10px;
                    }
                    .report-stat-value {
                        font-size: 28px;
                        font-weight: bold;
                        color: #667eea;
                    }
                    .report-stat-label {
                        color: #6c757d;
                        font-size: 14px;
                    }
                    .report-section {
                        background: #f8f9fa;
                        border-radius: 12px;
                        padding: 20px;
                        margin-bottom: 20px;
                        border: 1px solid #e9ecef;
                    }
                    @media print {
                        body {
                            padding: 20px;
                        }
                        .report-stat-card, .report-section {
                            break-inside: avoid;
                            page-break-inside: avoid;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    ${reportContent}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 1000);
                    };
                <\/script>
            </body>
            </html>
        `);
        
        printWindow.document.close();
    }
    
    function printReport() {
        const reportContent = document.getElementById('reportPreviewContent').innerHTML;
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>SmartLib Library Report - Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
                <style>
                    body {
                        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        padding: 20px;
                        background: white;
                    }
                    .report-stat-card {
                        background: #f8f9fa;
                        border-radius: 12px;
                        padding: 20px;
                        text-align: center;
                        margin-bottom: 20px;
                        border: 1px solid #e9ecef;
                    }
                    .report-stat-icon {
                        font-size: 32px;
                        margin-bottom: 10px;
                    }
                    .report-stat-value {
                        font-size: 28px;
                        font-weight: bold;
                        color: #667eea;
                    }
                    .report-stat-label {
                        color: #6c757d;
                        font-size: 14px;
                    }
                    .report-section {
                        background: #f8f9fa;
                        border-radius: 12px;
                        padding: 20px;
                        margin-bottom: 20px;
                        border: 1px solid #e9ecef;
                    }
                    @media print {
                        body {
                            padding: 0;
                        }
                        .report-stat-card, .report-section {
                            break-inside: avoid;
                            page-break-inside: avoid;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    ${reportContent}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                    };
                <\/script>
            </body>
            </html>
        `);
        
        printWindow.document.close();
    }
    
    // Attach event listener to Generate Report button
    document.getElementById('generateReportBtn')?.addEventListener('click', (e) => {
        e.preventDefault();
        showReportPreview();
        showToast('Generating library report...', 'info');
    });
    
    document.getElementById('downloadReportBtn')?.addEventListener('click', () => {
        downloadReport();
    });
    
    document.getElementById('printReportBtn')?.addEventListener('click', () => {
        printReport();
    });
    
    // ============================================
    // PROFILE MODAL FUNCTIONALITY
    // ============================================
    
    // Edit Profile Button
    document.getElementById('editProfileBtn')?.addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        const editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        editModal.show();
    });
    
    // Edit Profile Form Submit
    document.getElementById('editProfileFormSubmit')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const newName = document.getElementById('editFullName').value;
        const newEmail = document.getElementById('editEmail').value;
        
        document.getElementById('displayName').textContent = newName;
        document.getElementById('displayEmail').textContent = newEmail;
        const userNameElements = document.querySelectorAll('.dropdown-user-name, .profile-name, .user-name');
        userNameElements.forEach(el => {
            if (el) el.textContent = newName;
        });
        
        bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
        const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
        profileModal.show();
        
        showToast('Profile updated successfully!', 'success');
    });
    
    // Change Password Button
    document.getElementById('changePasswordBtn')?.addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        const pwModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        pwModal.show();
    });
    
    // Change Password Form
    document.getElementById('changePasswordForm')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const newPw = document.getElementById('newPassword').value;
        const confirmPw = document.getElementById('confirmPassword').value;
        
        if (newPw !== confirmPw) {
            showToast('Passwords do not match!', 'danger');
            return;
        }
        
        if (newPw.length < 8) {
            showToast('Password must be at least 8 characters!', 'danger');
            return;
        }
        
        showToast('Password changed successfully!', 'success');
        bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
        document.getElementById('changePasswordForm').reset();
    });
    
    // Password Strength Meter
    document.getElementById('newPassword')?.addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('pwStrength');
        let strength = 0;
        let strengthText = '';
        let strengthClass = '';
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        
        if (strength === 0 || strength === 1) {
            strengthText = 'Weak';
            strengthClass = 'strength-weak';
        } else if (strength === 2) {
            strengthText = 'Medium';
            strengthClass = 'strength-medium';
        } else {
            strengthText = 'Strong';
            strengthClass = 'strength-strong';
        }
        
        if (password.length > 0) {
            strengthDiv.innerHTML = `<div class="password-strength ${strengthClass}"></div><small class="text-muted">Password strength: ${strengthText}</small>`;
        } else {
            strengthDiv.innerHTML = '';
        }
    });
    
    // Confirm Password Match
    document.getElementById('confirmPassword')?.addEventListener('input', function() {
        const password = document.getElementById('newPassword').value;
        const confirm = this.value;
        const matchDiv = document.getElementById('pwMatch');
        
        if (confirm.length === 0) {
            matchDiv.innerHTML = '';
        } else if (password === confirm) {
            matchDiv.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Passwords match</span>';
        } else {
            matchDiv.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Passwords do not match</span>';
        }
    });
    
    // Avatar/Photo buttons
    document.getElementById('changeAvatarBtn')?.addEventListener('click', () => {
        showToast('Avatar customization coming soon!', 'info');
    });
    
    document.getElementById('uploadPhotoBtn')?.addEventListener('click', () => {
        showToast('Photo upload feature coming soon!', 'info');
    });
    
    // ============================================
    // SETTINGS MODAL FUNCTIONALITY
    // ============================================
    
    // Sync dark mode setting
    if (document.getElementById('darkModeSetting')) {
        document.getElementById('darkModeSetting').checked = currentTheme === 'dark';
        document.getElementById('darkModeSetting').addEventListener('change', (e) => {
            const newTheme = e.target.checked ? 'dark' : 'light';
            document.body.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('libTheme', newTheme);
            if (toggleSwitch) toggleSwitch.checked = e.target.checked;
        });
    }
    
    // Save Settings
    document.getElementById('saveSettingsBtn')?.addEventListener('click', () => {
        showToast('Settings saved successfully!', 'success');
        bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
    });
    
    // Security Buttons
    document.getElementById('enable2FA')?.addEventListener('click', () => {
        showToast('2FA setup would be configured here', 'info');
    });
    
    document.getElementById('logoutAllDevices')?.addEventListener('click', () => {
        if (confirm('Are you sure you want to logout from all devices?')) {
            showToast('Logged out from all other devices', 'success');
        }
    });
    
    document.getElementById('exportData')?.addEventListener('click', () => {
        showToast('Data export would start downloading', 'info');
    });
    
    document.getElementById('deleteAccount')?.addEventListener('click', () => {
        if (confirm('WARNING: This action is permanent! Are you sure you want to delete your account?')) {
            showToast('Account deletion request submitted', 'warning');
        }
    });
    
    // ============================================
    // NOTIFICATIONS FUNCTIONALITY
    // ============================================
    
    let unreadCount = <?php echo $notifications_count; ?>;
    
    function updateNotificationCounts() {
        const welcomeSpan = document.getElementById('welcomeNotificationCount');
        const unreadSpan = document.getElementById('unreadCount');
        const badge = document.getElementById('notificationCountBadge');
        
        if (welcomeSpan) welcomeSpan.textContent = unreadCount;
        if (unreadSpan) unreadSpan.textContent = unreadCount;
        
        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount === 0 ? 'none' : 'inline-block';
        }
        
        const viewUpdatesBtn = document.querySelector('.btn-update .notification-badge');
        if (viewUpdatesBtn) {
            if (unreadCount === 0) {
                viewUpdatesBtn.style.display = 'none';
            } else {
                viewUpdatesBtn.style.display = 'flex';
                viewUpdatesBtn.textContent = unreadCount;
            }
        }
    }
    
    function markAsRead(notificationId) {
        const notification = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
        if (notification && notification.classList.contains('notification-unread')) {
            notification.classList.remove('notification-unread');
            const markBtn = notification.querySelector('.mark-read-btn');
            if (markBtn) markBtn.remove();
            unreadCount--;
            updateNotificationCounts();
            showToast('Notification marked as read', 'success');
        }
    }
    
    function markAllAsRead() {
        const unreadNotifications = document.querySelectorAll('.notification-item.notification-unread');
        unreadNotifications.forEach(notification => {
            notification.classList.remove('notification-unread');
            const markBtn = notification.querySelector('.mark-read-btn');
            if (markBtn) markBtn.remove();
        });
        unreadCount = 0;
        updateNotificationCounts();
        showToast('All notifications marked as read', 'success');
    }
    
    function showToast(message, type = 'info') {
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : type === 'warning' ? 'warning' : 'info'} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'danger' ? 'exclamation-triangle-fill' : type === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        toastContainer.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 3000 });
        toast.show();
        
        toastEl.addEventListener('hidden.bs.toast', () => {
            toastEl.remove();
        });
    }
    
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = btn.getAttribute('data-id');
            markAsRead(id);
        });
    });
    
    document.getElementById('markAllReadBtn')?.addEventListener('click', () => {
        markAllAsRead();
    });
    
    document.getElementById('viewAllNotifications')?.addEventListener('click', (e) => {
        e.preventDefault();
        showToast('Opening full notification center...', 'info');
    });
    
    // Auto-refresh dashboard every 5 minutes
    setTimeout(() => {
        location.reload();
    }, 300000);
    
    // Show initial welcome toast
    setTimeout(() => {
        showToast(`Welcome back, <?php echo htmlspecialchars($username); ?>! You have ${unreadCount} new updates.`, 'success');
    }, 1000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>