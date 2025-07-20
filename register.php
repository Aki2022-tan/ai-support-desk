<?php
session_start();
require_once __DIR__ . '/includes/db.php'; // Establish database connection

$error = null;
$success = null;

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirmPassword = $_POST['confirm_password'] ?? '';

  if ($name && $email && $password && $confirmPassword) {
    if ($password !== $confirmPassword) {
      $error = "Passwords do not match.";
    } else {
      try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
          $error = "An account with this email already exists.";
        } else {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')");
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':email', $email);
          $stmt->bindParam(':password', $hashedPassword);
          $stmt->execute();

          $success = "Account created successfully. You can now log in.";
        }
      } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
      }
    }
  } else {
    $error = "Please fill in all fields.";
  }
}
?><!DOCTYPE html><html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - AI Support Desk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.5s ease-out;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-white via-blue-50 to-white min-h-screen flex flex-col font-sans"><!-- Main container --><div class="flex-grow flex items-center justify-center px-4 py-10">
  <div class="flex flex-col md:flex-row items-center gap-10 max-w-5xl w-full"><!-- Registration form section -->
<div class="bg-white shadow-lg rounded-2xl px-8 py-10 w-full max-w-md border border-gray-100 animate-fade-in">
  <div class="text-center mb-6">
    <div class="text-4xl mb-1">📝</div>
    <h2 class="text-2xl font-extrabold text-gray-800">Create Account</h2>
    <p class="text-sm text-gray-500 mt-1">Join the support portal</p>
  </div>

  <!-- Success or Error Message -->
  <?php if ($error): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php elseif ($success): ?>
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <!-- Registration Form -->
  <form method="POST" class="space-y-5" autocomplete="off">
    <div>
      <label for="name" class="block text-sm font-medium text-gray-600">Full Name</label>
      <input type="text" name="name" id="name" required
             class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>

    <div>
      <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
      <input type="email" name="email" id="email" required
             class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
      <input type="password" name="password" id="password" required
             class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>

    <div>
      <label for="confirm_password" class="block text-sm font-medium text-gray-600">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required
             class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition shadow-sm">
      Register
    </button>
  </form>

  <!-- Navigation -->
  <p class="mt-6 text-sm text-center text-gray-500">
    Already have an account?
    <a href="index.php" class="text-blue-500 font-medium hover:underline">Login here</a>
  </p>

  <p class="mt-3 text-sm text-center">
    <a href="index.php" class="text-gray-400 hover:text-blue-400 hover:underline">← Back to Home</a>
  </p>
</div>

<!-- Visual section -->
<div class="hidden md:block max-w-sm animate-fade-in">
  <img src="assets/images/register.svg" alt="Registration Illustration" class="w-full drop-shadow-md">
  <p class="text-center mt-4 text-sm text-gray-500 italic">“Start your AI-powered support journey.”</p>
</div>

  </div>
</div><!-- Footer --><footer class="text-sm text-center text-gray-400 py-6">
  &copy; <?= date('Y') ?> AI Support Desk. Designed with care by Rogienald.
</footer>
</body>
</html>