<?php
// pages/user/handler/register-user-handler.php

require_once '../../../includes/db.php';                    // Database connection
require_once '../../../includes/session-handler.php';      // Session management


// Initialize variables and error flags
$firstName = $lastName = $email = $password = $confirmPassword = '';
$errors = [];

// Utility function to sanitize input
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and assign inputs
    $firstName = clean_input($_POST['first_name'] ?? '');
    $lastName = clean_input($_POST['last_name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // --- Input Validation ---
    
if (empty($firstName)) {
    $errors['first_name'] = "First name is required.";
} elseif (!preg_match("/^[a-zA-Z ]{2,}$/", $firstName) || preg_match("/^\s*$/", $firstName)) {
    $errors['first_name'] = "First name must be at least 2 letters and contain only alphabetic characters and spaces.";
}

if (empty($lastName)) {
    $errors['last_name'] = "Last name is required.";
} elseif (!preg_match("/^[a-zA-Z ]{2,}$/", $lastName) || preg_match("/^\s*$/", $lastName)) {
    $errors['last_name'] = "Last name must be at least 2 letters and contain only alphabetic characters and spaces.";
}

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        // Duplicate email check using $conn
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = "Email is already registered.";
        }
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    }

    if (empty($confirmPassword)) {
        $errors['confirm_password'] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

// If no validation errors, proceed to insert
if (empty($errors)) {
    try {
        $fullName = $firstName . ' ' . $lastName;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use $conn instead of $pdo
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullName, $email, $hashedPassword]);

        //Use reusable toast session for success
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'User account successfully created. Please log in.'
        ];

        header("Location: ../../login.php");
        exit;
    } catch (PDOException $e) {
        // ✅ Use reusable toast session for internal failure
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'An internal error occurred. Please try again later.'
        ];

        header("Location: ../register-user.php");
        exit;
    }
} else {
    // Pass form data and errors back to form for user feedback
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = [
        'first_name' => $firstName,
        'last_name'  => $lastName,
        'email'      => $email,
    ];
    header("Location: ../register-user.php");
    exit;
}
} else {
    // Redirect to form if script accessed directly
    header("Location: ../register-user.php");
    exit;
}
?>