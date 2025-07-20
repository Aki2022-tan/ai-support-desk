<?php
// Include shared header for user pages (navigation, metadata, styling)
include('../includes/client-header.php');
?>

<!-- Primary content wrapper -->
<main class="flex-grow px-4 py-8 bg-gray-50 min-h-screen">

  <!-- Main ticket container card -->
  <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md transition-all">

    <!-- Title and description for context -->
    <div class="mb-6">
      <h2 class="text-3xl font-bold text-blue-800 tracking-tight">Submitted Tickets</h2>
      <p class="text-sm text-gray-500 mt-1">
        View your current and resolved support requests submitted to our helpdesk system.
      </p>
    </div>

    <!-- Tab Navigation: Switches between Ongoing and Resolved ticket views -->
    <div class="flex gap-6 text-sm font-medium border-b border-gray-200 mb-6">

      <!-- Ongoing tab button (default active) -->
      <button id="btnOngoing"
              class="text-blue-700 font-semibold border-b-2 border-blue-700 pb-2 transition">
        Ongoing
      </button>

      <!-- Visual divider between tabs -->
      <span class="text-gray-300">|</span>

      <!-- Resolved tab button -->
      <button id="btnResolved"
              class="text-gray-500 hover:text-blue-600 hover:border-blue-600 pb-2 transition">
        Resolved
      </button>
    </div>

    <!-- Search Input Form -->
    <form id="searchForm" class="mb-6">

      <div class="flex items-center gap-2">

        <!-- Input Group Wrapper -->
        <div class="relative flex-1">

          <!-- Search Field with Placeholder -->
          <input type="text" id="searchInput"
                 placeholder="Search tickets..."
                 class="w-full rounded-xl border border-gray-300 px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 shadow-sm transition">

          <!-- Search Icon Overlay (for UX clarity) -->
          <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400"
               xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition">
          Search
        </button>
      </div>
    </form>

    <!-- Placeholder for ongoing ticket results (dynamically injected via JS) -->
    <div id="ongoingTickets" class="space-y-4"></div>

    <!-- Placeholder for resolved ticket results (hidden until triggered) -->
    <div id="resolvedTickets" class="space-y-4 hidden"></div>

    <!-- Loading Spinner (hidden by default, visible during AJAX operations) -->
 <div id="loadingSpinner" class="flex justify-center items-center mt-6 hidden">
  <div class="w-5 h-5 rounded-full border-2 border-blue-500 border-t-transparent animate-spin shadow-md"></div>
</div>

  </div>
</main>

<!-- Global site footer -->
<footer class="text-center text-sm text-gray-400 py-6">
  &copy; <?= date('Y') ?> AI Support Desk. Built with precision by Rogienald.
</footer>

<!-- Client-side logic for dynamic data loading, tab switching, etc. -->
<script src="../assets/js/load-tickets.js"></script>
</body>
</html>