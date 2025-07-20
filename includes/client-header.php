<?php
session_start();

// Enforce access control: Redirect non-authenticated users or non-user roles to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

// Include database connection for executing SQL operations
require_once __DIR__ . '/../includes/db.php';

// Prepare a secure SQL statement to fetch ticket data for the currently logged-in user
// NOTE: Variables $ticket_id and $user_id must be defined before this point in the actual flow
$stmt = $conn->prepare("SELECT * FROM support_tickets WHERE id = ? AND user_id = ?");
$stmt->execute([$ticket_id, $user_id]);
?><!DOCTYPE html><html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Ticket | AI Support Desk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- Tailwind CSS -->  <script src="https://cdn.tailwindcss.com"></script>  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head><body class="bg-gray-50 text-gray-800 antialiased"><!-- Navigation Bar --><nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ open: false }">
  <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
    <!-- Branding -->
    <div class="flex flex-col">
      <h1 class="text-xl font-semibold text-blue-700">AI Support Desk</h1>
      <span class="text-sm text-gray-500">Powered by Cohere AI</span>
    </div><!-- Right-side controls -->
<div class="flex items-center space-x-3 sm:space-x-6">
  <!-- Notifications -->
  <button type="button" class="relative">
    <svg class="w-6 h-6 text-blue-600 hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 00-5-5.9V5a2 2 0 10-4 0v.1A6 6 0 004 11v3a2 2 0 01-.6 1.4L2 17h5m7 0v1a3 3 0 11-6 0v-1h6z" />
    </svg>
    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-semibold">1</span>
  </button>

  <!-- Desktop Nav Links -->
  <div class="hidden sm:flex items-center space-x-4 text-sm">
    <a href="my-tickets.php" class="text-gray-700 hover:text-blue-600 transition">My Tickets</a>
    <a href="profile.php" class="text-gray-700 hover:text-blue-600 transition">Profile</a>
    <a href="submit-ticket.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Submit Ticket</a>
    <a href="../logout.php" class="px-4 py-2 border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Logout</a>
  </div>

  <!-- Hamburger -->
  <button @click="open = !open" class="sm:hidden focus:outline-none">
    <svg x-show="!open" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
    <svg x-show="open" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
</div>

  </div>  <!-- Mobile Menu -->  <div x-show="open" x-cloak x-transition class="sm:hidden px-4 pb-4 space-y-2 text-sm">
    <a href="my-tickets.php" class="block hover:text-blue-600">My Tickets</a>
    <a href="profile.php" class="block hover:text-blue-600">Profile</a>
    <a href="submit-ticket.php" class="block text-white text-center bg-blue-600 py-2 rounded-md hover:bg-blue-700">Submit Ticket</a>
    <a href="../logout.php" class="block text-center text-red-600 border border-red-200 bg-red-50 py-2 rounded-md hover:bg-red-100">Logout</a>
  </div>
</nav><!-- Page content will be injected below -->