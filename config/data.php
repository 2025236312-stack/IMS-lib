<?php
// config/data.php - Library Data Configuration

// Books data with proper structure
$books = [
    ['id' => 'BK0001', 'title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'category' => 'Fiction', 'status' => 'Available', 'copies' => 5],
    ['id' => 'BK0002', 'title' => '1984', 'author' => 'George Orwell', 'category' => 'Fiction', 'status' => 'Borrowed', 'copies' => 3],
    ['id' => 'BK0003', 'title' => 'Atomic Habits', 'author' => 'James Clear', 'category' => 'Self-Help', 'status' => 'Available', 'copies' => 7],
];

// Members data
$members = [
    ['id' => 'M001', 'name' => 'John Smith', 'email' => 'john@example.com', 'membership' => 'Premium', 'borrowed' => 3],
    ['id' => 'M002', 'name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'membership' => 'Standard', 'borrowed' => 2],
    ['id' => 'M003', 'name' => 'Michael Brown', 'email' => 'michael@example.com', 'membership' => 'Student', 'borrowed' => 1],
];

// Transactions data
$transactions = [
    ['description' => 'Book "The Great Gatsby" borrowed by John Smith', 'status' => 'Active', 'badge' => 'primary'],
    ['description' => 'Book "1984" returned by Sarah Johnson', 'status' => 'Returned', 'badge' => 'success'],
    ['description' => 'Book "Atomic Habits" borrowed by Michael Brown', 'status' => 'Pending', 'badge' => 'warning'],
];
?>