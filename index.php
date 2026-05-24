<?php
// index.php - Enhanced Login Page with Registration, Forgot Password & User Roles
// Advanced Features: CSRF Protection, Remember Me, Password Strength Meter, Rate Limiting, Activity Tracking

session_start();

// Check for remember me cookie
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    // Verify token (you'd check against database)
    // For demo, redirect to auto-login handler
    header('Location: auto_login.php');
    exit();
}
// CSRF Protection - Generate token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Rate limiting for failed attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Security Headers for enhanced protection
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// If already logged in and session is valid, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['last_activity'])) {
    // Session timeout check (30 minutes)
    if (time() - $_SESSION['last_activity'] > 1800) {
        session_destroy();
        session_start();
    } else {
        $_SESSION['last_activity'] = time();
        header("Location: dashboard.php");
        exit();
    }
}

// Store registration success message
$reg_success = isset($_GET['reg_success']) ? htmlspecialchars($_GET['reg_success']) : '';
$fp_success = isset($_GET['fp_success']) ? htmlspecialchars($_GET['fp_success']) : '';

// Check if user came from logout
$logout_msg = isset($_GET['logout']) ? 'You have been successfully logged out.' : '';

// Generate unique session ID for security
if (session_status() === PHP_SESSION_ACTIVE) {
    if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_regenerate_id(true);
        session_destroy();
        header("Location: index.php");
        exit();
    }
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="LibraFlow - Advanced Library Management System with Secure Authentication">
    <meta name="theme-color" content="#667eea">
    <title>SmartLib - Library Management </title>
    
    <!-- Bootstrap 5.3 with Integrity -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Google Fonts - Enhanced typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Saira+Stencil:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS - EXTERNAL (ALL CSS MOVED HERE) -->
    <link rel="stylesheet" href="css/stylee.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body id="bg-vanta" data-csrf-token="<?php echo $_SESSION['csrf_token']; ?>">

    <!-- Toast Notification Container -->
    <div class="toast-notification" id="liveToast" style="display: none;">
        <div class="toast bg-success text-white" role="alert">
            <div class="toast-body" id="toastMessage">
                <i class="bi bi-check-circle-fill me-2"></i> 
            </div>
        </div>
    </div>

    <!-- Main Container with AOS animations -->
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card login-card shadow-lg border-0" data-aos="zoom-in" data-aos-duration="800" data-aos-once="true">
            <div class="card-body p-3 p-md-4">
                <!-- Logo/Brand Header - Compact -->
                <div class="text-center mb-3">
                    <div class="d-inline-block bg-gradient-primary p-2 rounded-circle mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="bi bi-book-half text-white logo-icon" style="font-size: 1.75rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-family: 'Saira Stencil', cursive;">SmartLib</h3>
                    <p class="text-muted small">Library Management System</p>
                    <?php if ($logout_msg): ?>
                        <div class="alert alert-info alert-dismissible fade show mt-2 py-2 small" role="alert">
                            <i class="bi bi-info-circle-fill"></i> <?php echo $logout_msg; ?>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab Navigation - Compact -->
                <ul class="nav nav-tabs nav-justified mb-3 border-0" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="forgot-tab" data-bs-toggle="tab" data-bs-target="#forgot" type="button" role="tab">
                            <i class="bi bi-key me-1"></i> Reset
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    
                    <!-- LOGIN TAB (Enhanced with Role Selector Dots) -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel">
                        <div class="row align-items-center g-2">
                            <div class="col-md-5 text-center border-end" data-aos="fade-right" data-aos-delay="200">
                                <i class="bi bi-shield-lock" style="font-size: 40px; color: #667eea;"></i>
                                <p class="mt-1 text-muted small">Secure Access</p>
                                
                                <!-- ROLE SELECTOR DOTS - Interactive -->
                                <div class="role-selector mt-2" id="roleSelector">
                                    <div class="role-option" data-role="staff" data-email="staff@library.com" data-password="staff123">
                                        <div class="role-dot staff-dot">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <span class="role-label">Staff</span>
                                    </div>
                                    <div class="role-option" data-role="student" data-email="student@library.com" data-password="student123">
                                        <div class="role-dot student-dot">
                                            <i class="bi bi-mortarboard"></i>
                                        </div>
                                        <span class="role-label">Student</span>
                                    </div>
                                    <div class="role-option" data-role="guest" data-email="guest@library.com" data-password="guest123">
                                        <div class="role-dot guest-dot">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <span class="role-label">Guest</span>
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> Session: 30min
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-7" data-aos="fade-left" data-aos-delay="300">
                                <form method="POST" action="authenticate.php" id="loginForm">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-envelope"></i> Email Address
                                    </label>
                                    <input type="email" name="email" id="login_email" class="form-control form-control-sm mb-2" 
                                           required placeholder="Enter your email" autocomplete="email">
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-lock"></i> Password
                                    </label>
                                    <input type="password" name="password" id="login_password" class="form-control form-control-sm mb-2" 
                                           required placeholder="••••••" autocomplete="current-password">
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                                            <label class="form-check-label small" for="remember_me">
                                                <i class="bi bi-check-square"></i> Remember Me
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-shield-check"></i> Secure
                                        </small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100 py-1 fw-semibold btn-sm" id="loginBtn">
                                        <i class="bi bi-box-arrow-in-right"></i> Login
                                    </button>
                                </form>
                                
                                <!-- Real-time login attempt counter -->
                                <div class="mt-2 small text-center" id="attemptWarning" style="display: none;">
                                    <span class="text-warning">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        Multiple failed attempts!
                                    </span>
                                </div>
                                
                                <?php if(isset($_GET['error'])): ?>
                                    <div class="alert alert-danger mt-2 small py-1 text-center">
                                        <i class="bi bi-exclamation-triangle-fill"></i> 
                                        <?php 
                                            $error_msg = htmlspecialchars($_GET['error']);
                                            if($error_msg == 'csrf') echo "Security token validation failed.";
                                            elseif($error_msg == 'rate_limit') echo "Too many failed attempts. Please wait.";
                                            else echo "Invalid email or password!";
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($reg_success == 'true'): ?>
                                    <div class="alert alert-success mt-2 small py-1 text-center">
                                        <i class="bi bi-check-circle-fill"></i> Registration successful! Please login.
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-2 text-center small text-muted">
                                    <hr class="my-1">
                                    <span class="text-muted">Click on role dots above</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REGISTER TAB (Enhanced with Password Strength Meter) -->
                    <div class="tab-pane fade" id="register" role="tabpanel">
                        <div class="row align-items-center g-2">
                            <div class="col-md-5 text-center border-end" data-aos="fade-right" data-aos-delay="200">
                                <i class="bi bi-person-plus" style="font-size: 40px; color: #28a745;"></i>
                                <p class="mt-1 text-muted small">Join our community</p>
                                <div class="mt-2">
                                    <i class="bi bi-check-circle-fill text-success"></i> Free<br>
                                    <i class="bi bi-book"></i> 10k+ books<br>
                                    <i class="bi bi-headset"></i> 24/7 Support
                                </div>
                            </div>
                            <div class="col-md-7" data-aos="fade-left" data-aos-delay="300">
                                <form method="POST" action="register.php" id="registerForm" novalidate>
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-person"></i> Full Name
                                    </label>
                                    <input type="text" name="fullname" id="reg_name" class="form-control form-control-sm mb-2" 
                                           required placeholder="Full name" pattern="[A-Za-z\s]{2,50}">
                                    <div class="invalid-feedback small" id="nameFeedback"></div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-envelope"></i> Email Address
                                    </label>
                                    <input type="email" name="email" id="reg_email" class="form-control form-control-sm mb-1" 
                                           required placeholder="your@email.com" autocomplete="off">
                                    <div class="small text-muted" id="emailAvailability"></div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-lock"></i> Password
                                    </label>
                                    <input type="password" name="password" id="reg_password" class="form-control form-control-sm mb-1" 
                                           required placeholder="Min 8 chars">
                                    <div class="password-strength" id="passwordStrength"></div>
                                    <div class="password-hint small text-muted" id="passwordHint"></div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-shield-lock"></i> Confirm Password
                                    </label>
                                    <input type="password" name="confirm_password" id="reg_confirm" class="form-control form-control-sm mb-2" 
                                           required placeholder="Confirm password">
                                    <div class="small" id="passwordMatch"></div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-person-badge"></i> Role
                                    </label>
                                    <select name="role" id="reg_role" class="form-select form-select-sm mb-2" required>
                                        <option value="Student">🎓 Student</option>
                                        <option value="Staff">👔 Staff</option>
                                        <option value="Guest">🌐 Guest</option>
                                    </select>
                                    
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="termsCheck" required>
                                        <label class="form-check-label small" for="termsCheck">
                                            I agree to <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms</a>
                                        </label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100 py-1 fw-semibold btn-sm" id="registerBtn" disabled>
                                        <i class="bi bi-person-plus"></i> Create Account
                                    </button>
                                </form>
                                
                                <?php if(isset($_GET['reg_error'])): ?>
                                    <div class="alert alert-danger mt-2 small py-1 text-center">
                                        <i class="bi bi-exclamation-triangle-fill"></i> 
                                        <?php 
                                            $reg_error = $_GET['reg_error'];
                                            if($reg_error == 'pass_mismatch') echo "Passwords do not match!";
                                            elseif($reg_error == 'email_exists') echo "Email already registered!";
                                            elseif($reg_error == 'short_password') echo "Password must be at least 8 characters!";
                                            else echo "Registration failed.";
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- FORGOT PASSWORD TAB (Enhanced) -->
                    <div class="tab-pane fade" id="forgot" role="tabpanel">
                        <div class="row align-items-center g-2">
                            <div class="col-md-5 text-center border-end" data-aos="fade-right" data-aos-delay="200">
                                <i class="bi bi-key" style="font-size: 40px; color: #ffc107;"></i>
                                <p class="mt-1 text-muted small">Reset password</p>
                                <div class="mt-2">
                                    <i class="bi bi-envelope-check"></i> Email reset<br>
                                    <i class="bi bi-shield-check"></i> Secure
                                </div>
                            </div>
                            <div class="col-md-7" data-aos="fade-left" data-aos-delay="300">
                                <form method="POST" action="forgot_password.php" id="forgotForm">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    
                                    <div class="alert alert-info small py-1 mb-2">
                                        <i class="bi bi-info-circle-fill"></i> Enter registered email
                                    </div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-envelope"></i> Email Address
                                    </label>
                                    <input type="email" name="email" id="fp_email" class="form-control form-control-sm mb-2" 
                                           required placeholder="registered@email.com">
                                    <div id="emailCheckResult" class="small mb-1"></div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-lock"></i> New Password
                                    </label>
                                    <input type="password" name="new_password" id="fp_new_password" class="form-control form-control-sm mb-1" 
                                           required placeholder="Min 8 chars">
                                    <div class="password-strength" id="fpPasswordStrength"></div>
                                    
                                    <label class="form-label fw-semibold small">
                                        <i class="bi bi-shield-lock"></i> Confirm Password
                                    </label>
                                    <input type="password" name="confirm_password" id="fp_confirm" class="form-control form-control-sm mb-2" 
                                           required placeholder="Confirm">
                                    <div class="small" id="fpPasswordMatch"></div>
                                    
                                    <button type="submit" class="btn btn-warning w-100 py-1 fw-semibold btn-sm">
                                        <i class="bi bi-arrow-repeat"></i> Reset Password
                                    </button>
                                </form>
                                
                                <?php if($fp_success == 'true'): ?>
                                    <div class="alert alert-success mt-2 small py-1 text-center">
                                        <i class="bi bi-check-circle-fill"></i> Password reset successful!
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(isset($_GET['fp_error'])): ?>
                                    <div class="alert alert-danger mt-2 small py-1 text-center">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        <?php 
                                            $fp_error = $_GET['fp_error'];
                                            if($fp_error == 'email_not_found') echo "Email not found!";
                                            elseif($fp_error == 'pass_mismatch') echo "Passwords do not match!";
                                            else echo "Reset failed.";
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-3 pt-2 border-top">
                    <small class="text-muted">
                        <i class="bi bi-c-circle"></i> 2025 LibraFlow
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h5 class="modal-title small"><i class="bi bi-file-text"></i> Terms</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body small">
                    <p><strong>Library Usage Policy</strong> - Respect materials and return on time.</p>
                    <p><strong>Privacy Policy</strong> - Your data is encrypted and private.</p>
                    <p><strong>Code of Conduct</strong> - Respectful behavior required.</p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
    // Initialize AOS animations
    AOS.init({
        once: true,
        duration: 800
    });

    // Vanta.js Background Configuration
    VANTA.NET({
        el: "#bg-vanta",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0x667eea,
        backgroundColor: 0x0a0a2a
    });

    // ==================== ROLE SELECTOR FUNCTIONALITY ====================
    // Role selector dots - populate email and password fields when clicked
    const roleOptions = document.querySelectorAll('.role-option');
    const emailInput = document.getElementById('login_email');
    const passwordInput = document.getElementById('login_password');
    
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all
            roleOptions.forEach(opt => opt.classList.remove('active'));
            // Add active class to clicked
            this.classList.add('active');
            
            // Get credentials from data attributes
            const email = this.getAttribute('data-email');
            const password = this.getAttribute('data-password');
            
            // Populate form fields
            if (emailInput) emailInput.value = email;
            if (passwordInput) passwordInput.value = password;
            
            // Trigger visual feedback
            emailInput.classList.add('is-valid');
            passwordInput.classList.add('is-valid');
            
            // Show toast notification
            const roleName = this.getAttribute('data-role');
            showToast(`${roleName} credentials loaded!`, 'info');
            
            // Remove validation styling after 2 seconds
            setTimeout(() => {
                emailInput.classList.remove('is-valid');
                passwordInput.classList.remove('is-valid');
            }, 2000);
        });
    });
    
    // ==================== PASSWORD STRENGTH METER ====================
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];
        
        if (password.length >= 8) strength++;
        else feedback.push('• Min 8 characters');
        
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        else feedback.push('• Uppercase & lowercase');
        
        if (password.match(/\d/)) strength++;
        else feedback.push('• Include a number');
        
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        else feedback.push('• Special character');
        
        return { strength, feedback, score: Math.min(3, strength) };
    }
    
    function updatePasswordStrength() {
        const password = document.getElementById('reg_password')?.value || '';
        const strengthDiv = document.getElementById('passwordStrength');
        const hintDiv = document.getElementById('passwordHint');
        
        if (!strengthDiv) return;
        
        if (password.length === 0) {
            strengthDiv.innerHTML = '';
            hintDiv.innerHTML = '';
            return;
        }
        
        const result = checkPasswordStrength(password);
        strengthDiv.innerHTML = '<div class="password-strength ' + 
            (result.score === 0 ? 'strength-weak' : result.score === 1 ? 'strength-weak' : result.score === 2 ? 'strength-medium' : 'strength-strong') + 
            '"></div>';
        
        if (result.score <= 1) {
            hintDiv.innerHTML = '<span class="text-danger small"><i class="bi bi-exclamation-triangle"></i> Weak</span>';
        } else if (result.score === 2) {
            hintDiv.innerHTML = '<span class="text-warning small"><i class="bi bi-shield"></i> Medium</span>';
        } else {
            hintDiv.innerHTML = '<span class="text-success small"><i class="bi bi-shield-check"></i> Strong!</span>';
        }
    }
    
    function checkPasswordMatch() {
        const password = document.getElementById('reg_password')?.value || '';
        const confirm = document.getElementById('reg_confirm')?.value || '';
        const matchDiv = document.getElementById('passwordMatch');
        
        if (confirm.length === 0) {
            matchDiv.innerHTML = '';
            return;
        }
        
        if (password === confirm && password.length > 0) {
            matchDiv.innerHTML = '<span class="text-success small"><i class="bi bi-check-circle-fill"></i> Match</span>';
        } else {
            matchDiv.innerHTML = '<span class="text-danger small"><i class="bi bi-x-circle-fill"></i> No match</span>';
        }
        
        validateRegisterForm();
    }
    
    function checkFpPasswordMatch() {
        const password = document.getElementById('fp_new_password')?.value || '';
        const confirm = document.getElementById('fp_confirm')?.value || '';
        const matchDiv = document.getElementById('fpPasswordMatch');
        
        if (confirm.length === 0) {
            matchDiv.innerHTML = '';
            return;
        }
        
        if (password === confirm && password.length > 0) {
            matchDiv.innerHTML = '<span class="text-success small"><i class="bi bi-check-circle-fill"></i> Match</span>';
        } else {
            matchDiv.innerHTML = '<span class="text-danger small"><i class="bi bi-x-circle-fill"></i> No match</span>';
        }
    }
    
    function updateFpPasswordStrength() {
        const password = document.getElementById('fp_new_password')?.value || '';
        const strengthDiv = document.getElementById('fpPasswordStrength');
        
        if (!strengthDiv) return;
        
        if (password.length === 0) {
            strengthDiv.innerHTML = '';
            return;
        }
        
        const result = checkPasswordStrength(password);
        strengthDiv.innerHTML = '<div class="password-strength ' + 
            (result.score === 0 ? 'strength-weak' : result.score === 1 ? 'strength-weak' : result.score === 2 ? 'strength-medium' : 'strength-strong') + 
            '"></div>';
    }
    
    let emailCheckTimeout;
    function checkEmailAvailability(email) {
        const emailField = document.getElementById('reg_email');
        const resultDiv = document.getElementById('emailAvailability');
        
        if (!email || email.length < 5) {
            resultDiv.innerHTML = '';
            return;
        }
        
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            resultDiv.innerHTML = '<span class="text-danger small"><i class="bi bi-x-circle"></i> Invalid email</span>';
            return;
        }
        
        resultDiv.innerHTML = '<span class="text-info small"><i class="bi bi-hourglass-split"></i> Checking...</span>';
        
        clearTimeout(emailCheckTimeout);
        emailCheckTimeout = setTimeout(() => {
            fetch('check_email.php?email=' + encodeURIComponent(email))
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        resultDiv.innerHTML = '<span class="text-success small"><i class="bi bi-check-circle"></i> Available</span>';
                        emailField.classList.remove('is-invalid');
                        emailField.classList.add('is-valid');
                    } else {
                        resultDiv.innerHTML = '<span class="text-danger small"><i class="bi bi-x-circle"></i> Already registered</span>';
                        emailField.classList.add('is-invalid');
                        emailField.classList.remove('is-valid');
                    }
                    validateRegisterForm();
                })
                .catch(() => {
                    resultDiv.innerHTML = '';
                });
        }, 500);
    }
    
    function validateRegisterForm() {
        const name = document.getElementById('reg_name')?.value || '';
        const email = document.getElementById('reg_email')?.value || '';
        const password = document.getElementById('reg_password')?.value || '';
        const confirm = document.getElementById('reg_confirm')?.value || '';
        const terms = document.getElementById('termsCheck')?.checked || false;
        const registerBtn = document.getElementById('registerBtn');
        
        let isValid = true;
        if (name.length < 2) isValid = false;
        if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) isValid = false;
        if (password.length < 8) isValid = false;
        if (password !== confirm) isValid = false;
        if (!terms) isValid = false;
        
        if (registerBtn) registerBtn.disabled = !isValid;
    }
    
    function validateName() {
        const name = document.getElementById('reg_name')?.value || '';
        const feedback = document.getElementById('nameFeedback');
        
        if (name.length > 0 && name.length < 2) {
            feedback.innerHTML = 'Name must be at least 2 characters';
            feedback.style.display = 'block';
            document.getElementById('reg_name').classList.add('is-invalid');
        } else if (name.length >= 2) {
            feedback.style.display = 'none';
            document.getElementById('reg_name').classList.remove('is-invalid');
            document.getElementById('reg_name').classList.add('is-valid');
        }
        validateRegisterForm();
    }
    
    // Login Form Enhancement - Loading State
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Logging in...';
        btn.disabled = true;
        
        setTimeout(() => {
            if (btn.disabled) {
                btn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Login';
                btn.disabled = false;
            }
        }, 10000);
    });
    
    // Event Listeners
    document.getElementById('reg_password')?.addEventListener('input', updatePasswordStrength);
    document.getElementById('reg_confirm')?.addEventListener('input', checkPasswordMatch);
    document.getElementById('reg_name')?.addEventListener('input', validateName);
    document.getElementById('reg_email')?.addEventListener('input', function(e) {
        checkEmailAvailability(e.target.value);
    });
    document.getElementById('termsCheck')?.addEventListener('change', validateRegisterForm);
    document.getElementById('fp_new_password')?.addEventListener('input', updateFpPasswordStrength);
    document.getElementById('fp_confirm')?.addEventListener('input', checkFpPasswordMatch);
    
    <?php if ($_SESSION['login_attempts'] >= 3): ?>
    document.getElementById('attemptWarning').style.display = 'block';
    <?php endif; ?>
    
    // URL hash handling
    const urlHash = window.location.hash;
    if (urlHash === '#register') {
        const registerTab = document.querySelector('button[data-bs-target="#register"]');
        if (registerTab) bootstrap.Tab.getOrCreateInstance(registerTab).show();
    } else if (urlHash === '#forgot') {
        const forgotTab = document.querySelector('button[data-bs-target="#forgot"]');
        if (forgotTab) bootstrap.Tab.getOrCreateInstance(forgotTab).show();
    }
    
    document.querySelectorAll('#authTab button').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target').substring(1);
            window.history.replaceState(null, null, '#' + target);
        });
    });
    
    function showToast(message, type = 'success') {
        const toast = document.getElementById('liveToast');
        const toastMessage = document.getElementById('toastMessage');
        toastMessage.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'info-circle-fill'} me-2"></i> ${message}`;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 2000);
    }
    
    console.log('LibraFlow v3.0 - Advanced Library System Loaded');
    </script>
</body>
</html>