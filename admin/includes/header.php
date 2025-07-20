<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

require_once __DIR__ . '/../../includes/db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="icon" href="../../assets/images/favicon.png"> <!-- optional -->
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  

<!-- Alpine.js for mobile menu -->
<header class="bg-white shadow-md sticky top-0 z-50 px-4 sm:px-6 py-4" x-data="{ open: false }">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    
    <!-- Branding -->
    <div class="flex items-center space-x-4">
      <h1 class="text-xl sm:text-2xl font-semibold text-blue-700">Admin Dashboard</h1>
    </div>

    <!-- Desktop Navigation -->
    <nav class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-600">
      <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
      <a href="tickets.php" class="hover:text-blue-600">Tickets</a>
      <a href="users.php" class="hover:text-blue-600">Users</a>
      <a href="reports.php" class="hover:text-blue-600">Reports</a>
      <a href="settings.php" class="hover:text-blue-600">Settings</a>
    </nav>

    <!-- Controls -->
    <div class="hidden md:flex items-center space-x-4">
      <!-- Notification -->
      <button type="button" class="relative group">
        <svg class="w-6 h-6 text-gray-500 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 00-5-5.9V5a2 2 0 10-4 0v.1A6 6 0 004 11v3a2 2 0 01-.6 1.4L2 17h5m7 0v1a3 3 0 11-6 0v-1h6z" />
        </svg>
        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-semibold">3</span>
      </button>

      <!-- Admin Welcome -->
      <div class="text-sm text-gray-700">
        Welcome, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></strong>
      </div>
      <a href="../logout.php" class="text-sm text-red-600 hover:underline">Logout</a>
    </div>

    <!-- Hamburger (mobile only) -->
    <button @click="open = !open" class="md:hidden focus:outline-none">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path :class="{'hidden': open, 'block': !open }" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
        <path :class="{'block': open, 'hidden': !open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div x-show="open" x-transition class="md:hidden mt-3 px-2 space-y-2 text-sm text-gray-700">
    <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-gray-100">Dashboard</a>
    <a href="tickets.php" class="block py-2 px-4 rounded hover:bg-gray-100">Tickets</a>
    <a href="users.php" class="block py-2 px-4 rounded hover:bg-gray-100">Users</a>
    <a href="reports.php" class="block py-2 px-4 rounded hover:bg-gray-100">Reports</a>
    <a href="settings.php" class="block py-2 px-4 rounded hover:bg-gray-100">Settings</a>
    <div class="border-t border-gray-200 my-2"></div>
    <div class="flex items-center justify-between px-4 py-2">
      <span class="text-gray-700">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
      <a href="../logout.php" class="text-red-600 hover:underline">Logout</a>
    </div>
  </div>
</header>