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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta settings for proper character encoding and responsive behavior -->
  <meta charset="UTF-8">
  <title>Submit Ticket | AI Support Desk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Include Tailwind CSS for utility-first styling -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Include Alpine.js for lightweight interactivity -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Import Font Awesome for optional iconography support (can be removed if not needed) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<!-- Base body styling for layout and background -->
<body class="bg-gray-50 min-h-screen flex flex-col">

<!-- Responsive Navigation Bar using Alpine.js for toggling visibility on mobile -->
<nav class="bg-white shadow-sm sticky top-0 z-50 px-4 py-3" x-data="{ open: false }">
  <div class="max-w-6xl mx-auto flex items-center justify-between">
    <!-- Branding and tagline -->
    <div class="flex flex-col">
      <h1 class="text-xl font-bold text-blue-700">AI Support Desk</h1>
      <span class="text-xs text-gray-500">Powered by Cohere AI</span>
    </div>

    <!-- Right-side control group including notification, navigation, and logout -->
    <div class="flex items-center space-x-3 sm:space-x-6">

      <!-- Placeholder button for notifications (badge hardcoded to '1') -->
      <button type="button" class="relative group">
        <svg class="w-6 h-6 text-blue-600 hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 00-5-5.9V5a2 2 0 10-4 0v.1A6 6 0 004 11v3a2 2 0 01-.6 1.4L2 17h5m7 0v1a3 3 0 11-6 0v-1h6z" />
        </svg>
        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-semibold">1</span>
      </button>

      <!-- Desktop-only navigation links -->
      <div class="hidden sm:flex items-center space-x-4 text-sm font-medium">
        <a href="my-tickets.php" class="text-gray-700 hover:text-blue-600">My Tickets</a>
        <a href="profile.php" class="text-gray-700 hover:text-blue-600">Profile</a>
        <a href="submit-ticket.php" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md">Submit Ticket</a>
        <a href="../logout.php" class="text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md">Logout</a>
      </div>

      <!-- Mobile hamburger toggle for smaller viewports -->
      <button @click="open = !open" class="sm:hidden text-gray-700 hover:text-blue-700">
        <!-- Show hamburger icon when collapsed -->
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>

        <!-- Show close icon when expanded -->
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile-friendly dropdown menu revealed when 'open' is true -->
  <div x-show="open" x-transition class="sm:hidden mt-3 px-4 space-y-2 text-sm" x-cloak>
    <a href="my-tickets.php" class="block hover:text-blue-600">My Tickets</a>
    <a href="profile.php" class="block hover:text-blue-600">Profile</a>
    <a href="submit-ticket.php" class="block bg-blue-600 text-white text-center py-2 rounded-md hover:bg-blue-700">Submit Ticket</a>
    <a href="../logout.php" class="block text-center text-red-600 border border-red-200 bg-red-50 py-2 rounded-md hover:bg-red-100">Logout</a>
  </div>
</nav>