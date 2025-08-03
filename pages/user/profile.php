<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('user');

// Simulated user data for demo
$user = [
  'name' => 'John Doe',
  'email' => 'john.doe@example.com',
  'role' => 'User',
  'joined' => 'March 12, 2024',
  'profile_picture' => null, // Optional: can be used for future image upload
];
?>

<div class="max-w-4xl mx-auto p-6 space-y-6">
  <!-- Header -->
  <div class="flex flex-col md:flex-row items-center md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
      <p class="text-sm text-gray-500">View and update your personal account details.</p>
    </div>
    <a href="edit-profile.php"
      class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition">
      ✏️ Edit Profile
    </a>
  </div>

  <!-- Profile Card -->
  <div class="bg-white border rounded-xl shadow-sm p-6 space-y-6">
    <div class="flex flex-col sm:flex-row items-center gap-4">
      <div class="w-24 h-24 flex items-center justify-center bg-gray-100 rounded-full text-3xl text-gray-500">
        👤
      </div>
      <div class="text-center sm:text-left space-y-1">
        <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($user['name']) ?></h2>
        <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
        <p class="text-sm text-gray-400">Role: <span class="font-medium text-gray-600"><?= $user['role'] ?></span></p>
        <p class="text-sm text-gray-400">Joined: <?= $user['joined'] ?></p>
      </div>
    </div>
  </div>

  <!-- Support Info / Tips -->
  <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-900 p-4 rounded-lg shadow-sm text-sm leading-relaxed">
    Need to update your email or report a problem with your account? Please open a support ticket via the <a href="submit-ticket.php" class="text-blue-700 underline">ticket center</a>.
  </div>
</div>