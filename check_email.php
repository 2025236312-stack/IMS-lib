<?php
// check_email.php - AJAX endpoint to check if email is already registered
session_start();

// Optional: Add rate limiting for this endpoint
header('Content-Type: application/json');

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

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($email)) {
    echo json_encode(['available' => true, 'message' => 'Email is required']);
    exit();
}

// Check if email exists
$email_exists = false;
foreach ($users as $user) {
    if ($user['email'] === $email) {
        $email_exists = true;
        break;
    }
}

// Also check demo accounts
$demo_emails = ['staff@library.com', 'student@library.com', 'guest@library.com'];
if (in_array($email, $demo_emails)) {
    $email_exists = true;
}

echo json_encode([
    'available' => !$email_exists,
    'message' => $email_exists ? 'Email already registered' : 'Email available'
]);
?>