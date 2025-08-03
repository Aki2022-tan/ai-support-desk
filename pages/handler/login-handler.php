<?php
require_once '../../includes/db.php';
require_once '../../includes/session-handler.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

// Basic validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required.";
}
if (empty($password)) {
    $errors[] = "Password is required.";
}

if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    $_SESSION['old_email'] = $email; // ✅ Retain email input
    header("Location: ../login.php");
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid credentials.";
        $_SESSION['old_email'] = $email; // ✅ Retain email input
        header("Location: ../login.php");
        exit;
    }

    if ($user['status'] !== 'active') {
        $_SESSION['error'] = "Account not active. Please contact support.";
        $_SESSION['old_email'] = $email; // ✅ Retain email input
        header("Location: ../login.php");
        exit;
    }

    // Secure session setup
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['status']  = $user['status'];

    // Redirect to loading page
    $role = urlencode($user['role']);
    header("Location: ../auth-loading.php?role={$role}");
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "System error. Please try again later.";
    $_SESSION['old_email'] = $email; // ✅ Retain email input
    header("Location: ../login.php");
    exit;
}