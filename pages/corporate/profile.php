<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';

requireRole('corporate');

// Example: Simulated data (replace with actual DB fetch logic)
$agent = [
  'full_name' => 'John Ramirez',
  'designation' => 'IT Support Specialist',
  'department' => 'IT Department',
  'username' => 'jramirez',
  'email' => 'john.ramirez@example.com',
  'employee_id' => 'EMP-1024',
  'date_joined' => 'March 15, 2024',
  'last_login' => 'July 30, 2025 09:21 AM',
  'tickets_assigned' => 48,
  'tickets_resolved' => 44,
  'avg_resolution_time' => '3.5 hrs',
  'last_password_change' => 'June 10, 2025',
  'last_login_ip' => '192.168.1.24'
];
?>

<div class="p-6 space-y-8 max-w-4xl mx-auto">
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">My Profile</h1>
    <p class="text-sm text-gray-600">View and manage your corporate agent profile.</p>
  </div>

  <!-- Section: Personal Info -->
  <div class="bg-white shadow rounded-xl p-5 space-y-4 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800">Personal Information</h2>
    <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
      <div><strong>Full Name:</strong> <?= $agent['full_name'] ?></div>
      <div><strong>Designation:</strong> <?= $agent['designation'] ?></div>
      <div><strong>Department:</strong> <?= $agent['department'] ?></div>
      <div><strong>Email Address:</strong> <?= $agent['email'] ?></div>
      <div><strong>Employee ID:</strong> <?= $agent['employee_id'] ?></div>
      <div><strong>Date Joined:</strong> <?= $agent['date_joined'] ?></div>
    </div>
  </div>

  <!-- Section: Role Summary -->
  <div class="bg-white shadow rounded-xl p-5 space-y-3 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800">Professional Bio & Role Summary</h2>
    <p class="text-sm text-gray-700">
      As a corporate agent assigned to the <?= $agent['department'] ?>, you are responsible for reviewing, responding, and resolving user-submitted tickets in a timely and professional manner. Please ensure high-priority tickets are handled promptly, and adhere to the internal SLA policies set by the system administrator.
    </p>
  </div>

  <!-- Section: Account Summary -->
  <div class="bg-white shadow rounded-xl p-5 space-y-4 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800">Account Details & Statistics</h2>
    <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
      <div><strong>Username:</strong> <?= $agent['username'] ?></div>
      <div><strong>Last Login:</strong> <?= $agent['last_login'] ?></div>
      <div><strong>Tickets Assigned:</strong> <?= $agent['tickets_assigned'] ?></div>
      <div><strong>Tickets Resolved:</strong> <?= $agent['tickets_resolved'] ?></div>
      <div><strong>Avg. Resolution Time:</strong> <?= $agent['avg_resolution_time'] ?></div>
    </div>
  </div>

  <!-- Section: Security Info -->
  <div class="bg-white shadow rounded-xl p-5 space-y-4 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800">Security & Login Info</h2>
    <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
      <div><strong>Last Password Change:</strong> <?= $agent['last_password_change'] ?></div>
      <div><strong>Last Login IP:</strong> <?= $agent['last_login_ip'] ?></div>
    </div>

    <div class="pt-4">
      <a href="change-password.php" class="inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">
        Change Password
      </a>
    </div>
  </div>

  <!-- Section: Notification Settings (Optional) -->
  <div class="bg-white shadow rounded-xl p-5 space-y-4 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800">Notification Preferences</h2>
    <form action="#" method="post" class="space-y-3 text-sm text-gray-700">
      <label class="flex items-center gap-2">
        <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        Email me when new tickets are assigned
      </label>
      <label class="flex items-center gap-2">
        <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        Notify me about ticket status updates
      </label>
      <label class="flex items-center gap-2">
        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        Announcements and system updates
      </label>
      <div class="pt-2">
        <button type="submit" class="bg-gray-800 text-white text-sm px-4 py-2 rounded hover:bg-gray-700 transition">
          Save Preferences
        </button>
      </div>
    </form>
  </div>
</div>