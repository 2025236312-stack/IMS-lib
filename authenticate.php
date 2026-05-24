<?php
// authenticate.php - Login Authentication Handler
session_start();

// CSRF Protection - Verify token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: index.php?error=csrf");
    exit();
}

// Rate limiting check
$max_attempts = 5;
$lockout_time = 300; // 5 minutes in seconds

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Check if locked out
if ($_SESSION['login_attempts'] >= $max_attempts) {
    $time_passed = time() - $_SESSION['last_attempt_time'];
    if ($time_passed < $lockout_time) {
        header("Location: index.php?error=rate_limit");
        exit();
    } else {
        // Reset attempts after lockout period
        $_SESSION['login_attempts'] = 0;
    }
}

// File path for user data
$users_file = 'config/users.json';

// Load users
if (file_exists($users_file)) {
    $users = json_decode(file_get_contents($users_file), true);
    if (!is_array($users)) {
        $users = [];
    }
} else {
    $users = [];
}

// Get login credentials
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember_me = isset($_POST['remember_me']) ? true : false;

// Find user by email
$authenticated = false;
$user_data = null;

foreach ($users as $user) {
    if ($user['email'] === $email && password_verify($password, $user['password'])) {
        $authenticated = true;
        $user_data = $user;
        break;
    }
}

// Demo accounts for fallback (if no users in JSON)
$demo_accounts = [
    ['email' => 'staff@library.com', 'password' => 'staff123', 'role' => 'Staff', 'name' => 'Staff User'],
    ['email' => 'student@library.com', 'password' => 'student123', 'role' => 'Student', 'name' => 'Student User'],
    ['email' => 'guest@library.com', 'password' => 'guest123', 'role' => 'Guest', 'name' => 'Guest User']
];

if (!$authenticated) {
    foreach ($demo_accounts as $demo) {
        if ($demo['email'] === $email && $demo['password'] === $password) {
            $authenticated = true;
            $user_data = [
                'id' => 'DEMO001',
                'fullname' => $demo['name'],
                'email' => $demo['email'],
                'role' => $demo['role']
            ];
            break;
        }
    }
}

if ($authenticated) {
    // Reset login attempts on successful login
    $_SESSION['login_attempts'] = 0;
    
    // Set session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['user_name'] = $user_data['fullname'];
    $_SESSION['user_email'] = $user_data['email'];
    $_SESSION['user_role'] = $user_data['role'];
    $_SESSION['last_activity'] = time();
    
    // Remember Me functionality (set cookie for 30 days)
    if ($remember_me) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['remember_token'] = $token;
        setcookie('remember_token', $token, time() + (86400 * 30), "/");
    }
    
    header("Location: dashboard.php");
    exit();
} else {
    // Increment failed login attempts
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt_time'] = time();
    
    header("Location: index.php?error=invalid");
    exit();
}
?>

