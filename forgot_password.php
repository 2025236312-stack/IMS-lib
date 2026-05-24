<?php
// forgot_password.php - Password Reset Handler
session_start();

// CSRF Protection - Verify token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: index.php?fp_error=csrf");
    exit();
}

// File path for user data
$users_file = 'config/users.json';

// Load existing users
if (file_exists($users_file)) {
    $users = json_decode(file_get_contents($users_file), true);
    if (!is_array($users)) {
        $users = [];
    }
} else {
    $users = [];
}

// Get form data
$email = trim($_POST['email'] ?? '');
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
$error = null;
$user_found = false;
$user_index = null;

// Find user by email
foreach ($users as $index => $user) {
    if ($user['email'] === $email) {
        $user_found = true;
        $user_index = $index;
        break;
    }
}

if (!$user_found) {
    $error = 'email_not_found';
} elseif (strlen($new_password) < 8) {
    $error = 'short_password';
} elseif ($new_password !== $confirm_password) {
    $error = 'pass_mismatch';
}

// If no errors, update password
if ($error === null) {
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update user password
    $users[$user_index]['password'] = $hashed_password;
    $users[$user_index]['updated_at'] = date('Y-m-d H:i:s');
    
    // Save to JSON file
    file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
    
    // Password reset successful
    header("Location: index.php?fp_success=true");
    exit();
} else {
    // Password reset failed
    header("Location: index.php?fp_error=" . $error);
    exit();
}
?>