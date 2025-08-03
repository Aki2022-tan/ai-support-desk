<?php
require_once 'includes/db.php';

$name     = 'System Administrator';
$email    = 'admin@gmail.com';
$password = 'admin';
$role     = 'admin';
$status   = 'active';

// Securely hash the password before inserting
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status, created_at)
                            VALUES (?, ?, ?, ?, ?, NOW())");

    $stmt->execute([$name, $email, $hashedPassword, $role, $status]);

    echo "✅ Admin account created successfully.";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}