<?php
session_start(); // Initialize session handling

require_once __DIR__ . '/includes/db.php'; // Include the database connection script

// Redirect users who are already logged in
if (isset($_SESSION['user_id'])) {
  header("Location: pages/submit-ticket.php");
  exit();
}

$error = null; // Initialize error message variable

// Handle form submission via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize and fetch form inputs
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password_raw = $_POST['password'] ?? '';

  // Proceed only if all required fields are filled
  if ($name && $email && $password_raw) {
    $password = password_hash($password_raw, PASSWORD_DEFAULT); // Hash the password securely

    // Verify if the email is already registered in the database
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
      // Email already exists in the system
      $error = "Email is already registered.";
    } else {
      // If email is not taken, proceed to register the new user
      $role = 'user'; // Default role for registered users
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
      $success = $stmt->execute([$name, $email, $password, $role]);

      if ($success) {
        // On successful registration, regenerate session and store user data
        session_regenerate_id(true);
        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;

        // Redirect to the ticket submission page
        header("Location: pages/submit-ticket.php");
        exit();
      } else {
        // Handle unexpected database insertion failure
        $error = "Failed to register. Please try again.";
      }
    }
  } else {
    // Inform user to complete all fields
    $error = "Please fill in all fields.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - AI Support Desk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Fade-in animation used during form load */
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.5s ease-out;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-gray-100 min-h-screen flex flex-col font-sans">

<!-- Page header with branding -->
<header class="w-full text-center py-6">
  <a href="index.php" class="text-blue-700 text-2xl font-extrabold">AI Support Desk</a>
  <p class="text-sm text-gray-500 mt-1">Create an account to start submitting tickets</p>
</header>

<!-- Main registration form wrapper -->
<div class="flex-grow flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row items-center gap-10 max-w-4xl w-full">

    <!-- Registration Form Card -->
    <div class="bg-white shadow-xl rounded-xl px-8 py-10 w-full max-w-md border border-blue-100 animate-fade-in">
      <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">Register</h2>

      <!-- Display error message, if any -->
      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 transition-all">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <!-- Registration form -->
      <form method="POST" class="space-y-5" autocomplete="off">
        <!-- Full Name input field -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-600">Full Name</label>
          <input type="text" name="name" id="name" required
                 class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>

        <!-- Email input field -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
          <input type="email" name="email" id="email" required
                 class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>

        <!-- Password input field -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
          <input type="password" name="password" id="password" required
                 class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200"
                 autocomplete="new-password">
        </div>

        <!-- Submit button -->
        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition shadow-sm">
          Create Account
        </button>
      </form>

      <!-- Alternate navigation for existing users -->
      <p class="mt-6 text-sm text-center text-gray-500">
        Already have an account?
        <a href="login.php" class="text-blue-500 font-medium hover:underline">Login here</a>
      </p>

      <!-- Navigation back to homepage -->
      <p class="mt-3 text-sm text-center">
        <a href="index.php" class="text-gray-400 hover:text-blue-400 hover:underline">← Back to Home</a>
      </p>
    </div>

    <!-- Visual illustration section (shown on medium and larger devices) -->
    <div class="hidden md:block max-w-sm">
      <img src="assets/images/helpdesk.svg" alt="Register Illustration" class="w-full">
    </div>

  </div>
</div>

<!-- Page footer -->
<footer class="text-sm text-center text-gray-400 py-6 mt-12">
  &copy; <?= date('Y') ?> AI Support Desk. Built with love by Rogienald.
</footer>

</body>
</html>