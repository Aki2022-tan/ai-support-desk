<?php
// pages/corporate/handler/register-corporate-handler.php

require_once '../../../includes/db.php';                    // Database connection
require_once '../../../includes/session-handler.php';      // Session management

// Initialize inputs and error collection
$companyName = $contactPerson = $contactNumber = $address = $email = $password = $confirmPassword = '';
$errors = [];

// Sanitize input
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

// Handle only POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $companyName     = clean_input($_POST['company_name'] ?? '');
    $contactPerson   = clean_input($_POST['contact_person'] ?? '');
    $contactNumber   = clean_input($_POST['contact_number'] ?? '');
    $address         = clean_input($_POST['address'] ?? '');
    $email           = clean_input($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // --- Validation Flow ---

    // 1. Validate Company Name
    if (empty($companyName)) {
        $errors['company_name'] = "Company name is required.";
    } elseif (strlen($companyName) < 3) {
        $errors['company_name'] = "Company name must be at least 3 characters.";
    }

    // 2. Validate Contact Person
    if (empty($contactPerson)) {
        $errors['contact_person'] = "Contact person is required.";
    } elseif (strlen($contactPerson) < 3) {
        $errors['contact_person'] = "Contact person must be at least 3 characters.";
    }

    // 3. Validate Address
    if (empty($address)) {
        $errors['address'] = "Company address is required.";
    } elseif (strlen($address) < 3) {
        $errors['address'] = "Address must be at least 3 characters.";
    }

    // 4. Validate Contact Number
    if (empty($contactNumber)) {
        $errors['contact_number'] = "Contact number is required.";
    } elseif (!preg_match("/^[0-9]{6,15}$/", $contactNumber)) {
        $errors['contact_number'] = "Contact number must be numeric and between 6 to 15 digits.";
    }

    // 5. Validate Email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = "Email is already registered.";
        }
    }

    // 6. Validate Password
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

    // --- If No Errors, Register ---
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Default role is 'corporate', default status is 'pending'
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status, company_name, contact_person, contact_number, address)
                                    VALUES (?, ?, ?, 'corporate', 'pending', ?, ?, ?, ?)");
            $stmt->execute([
                $contactPerson,
                $email,
                $hashedPassword,
                $companyName,
                $contactPerson,
                $contactNumber,
                $address
            ]);
              //Use reusable toast session for success
               $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Corporate account registration submitted. Awaiting for admin approval.'
        ];

        header("Location: ../../login.php");
        exit;

          
        } catch (PDOException $e) {
           // ✅ Use reusable toast session for internal failure
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'An internal error occurred. Please try again later.'
        ];
            header("Location: ../register-corporate.php");
            exit;
        }
    } else {
        // Persist input and errors in session
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = [
            'company_name'    => $companyName,
            'contact_person'  => $contactPerson,
            'contact_number'  => $contactNumber,
            'address'         => $address,
            'email'           => $email,
        ];
        header("Location: ../register-corporate.php");
        exit;
    }
} else {
    header("Location: ../register-corporate.php");
    exit;
}
?>