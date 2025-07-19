<?php
// Include the common client header which contains navigation and head metadata
include('../includes/client-header.php');
?>

<!-- Main Content Container -->
<main class="flex-grow px-4 py-6">
  <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-lg">

    <!-- Header Section: Displays page title and description -->
    <div class="mb-6">
      <h2 class="text-3xl font-extrabold text-blue-800 tracking-tight">Submitted Tickets</h2>
      <p class="text-sm text-gray-500 mt-1">Track your support requests and view responses from the admin team.</p>
    </div>

    <!-- Tab Navigation: Allows toggling between ongoing and resolved tickets -->
    <div class="flex gap-6 border-b border-gray-200 pb-2 mb-4 text-sm font-medium">
      <button id="btnOngoing" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1 flex items-center gap-2">
        <i class="fas fa-tools"></i>
        <span>Ongoing</span>
      </button>
      <span class="text-gray-300">|</span>
      <button id="btnResolved" class="text-gray-500 hover:text-blue-600 hover:border-blue-600 pb-1 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>Resolved</span>
      </button>
    </div>

    <!-- Search Form: Enables users to filter tickets by keyword -->
    <form id="searchForm" class="mb-6">
      <div class="flex items-center space-x-2">
        <div class="relative w-full">
          <!-- Input Field for Search Query -->
          <input type="text" id="searchInput" placeholder=" Search tickets..."
            class="w-full border border-gray-300 rounded-full pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 shadow-sm">
          <!-- Decorative Search Icon Positioned Inside the Input -->
          <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
               stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <!-- Submit Button for Search -->
        <button type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm transition">Search</button>
      </div>
    </form>

    <!-- Container for Ongoing Tickets (dynamically populated via AJAX) -->
    <div id="ongoingTickets"></div>

    <!-- Container for Resolved Tickets (hidden by default, shown via tab interaction) -->
    <div id="resolvedTickets" class="hidden"></div>

    <!-- Loading Spinner: Displayed while AJAX content is being fetched -->
    <div id="loadingSpinner" class="text-center mt-6 hidden">
      <svg class="animate-spin h-6 w-6 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
           viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
      </svg>
    </div>

  </div>
</main>

<!-- Footer Section: Displays static copyright -->
<footer class="text-center text-sm text-gray-400 py-6">
  &copy; <?= date('Y') ?> AI Support Desk. Built with 💙 by Rogienald.
</footer>

<!-- External JavaScript: Handles ticket loading, tab switching, infinite scroll, and spinner logic -->
<script src="../assets/js/load-tickets.js"></script>
</body>
</html>