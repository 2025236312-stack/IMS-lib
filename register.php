<?php
// register.php - User Registration Handler
session_start();

// CSRF Protection - Verify token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: index.php?reg_error=csrf");
    exit();
}

// File path for storing user data (simple JSON file database)
$users_file = 'config/users.json';

// Load existing users or create empty array
if (file_exists($users_file)) {
    $users = json_decode(file_get_contents($users_file), true);
    if (!is_array($users)) {
        $users = [];
    }
} else {
    $users = [];
}

// Get form data
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? 'Student';

// Validation
$error = null;

// Check if email already exists
foreach ($users as $user) {
    if ($user['email'] === $email) {
        $error = 'email_exists';
        break;
    }
}

// Password validation
if (strlen($password) < 8) {
    $error = 'short_password';
} elseif ($password !== $confirm_password) {
    $error = 'pass_mismatch';
}

// If no errors, create new user
if ($error === null) {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Create new user entry
    $new_user = [
        'id' => 'U' . str_pad(count($users) + 1, 4, '0', STR_PAD_LEFT),
        'fullname' => $fullname,
        'email' => $email,
        'password' => $hashed_password,
        'role' => $role,
        'created_at' => date('Y-m-d H:i:s'),
        'status' => 'active'
    ];
    
    $users[] = $new_user;
    
    // Save to JSON file
    file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
    
    // Registration successful
    header("Location: index.php?reg_success=true");
    exit();
} else {
    // Registration failed
    header("Location: index.php?reg_error=" . $error);
    exit();
}
?>