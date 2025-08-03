<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';

requireRole('corporate');

// Simulated ticket count
$ticketCount = 2;
?>

<div class="p-6">
  <!-- Header -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
    <div class="flex items-center gap-3">
      <h1 class="text-2xl font-bold text-gray-800">Responded Tickets</h1>
      <span class="inline-flex items-center justify-center text-sm font-medium bg-blue-100 text-blue-800 rounded-full px-2 py-0.5 h-6">
        <?= $ticketCount ?> ticket<?= $ticketCount === 1 ? '' : 's' ?>
      </span>
    </div>

    <!-- Filter & Sort -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
      <div>
        <label for="urgencyFilter" class="text-sm font-medium text-gray-700 mr-2">Sort by:</label>
        <select id="urgencyFilter" class="border rounded-lg px-3 py-1.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500">
          <option value="recent">Most Recent</option>
          <option value="oldest">Oldest</option>
          <option value="high">High Urgency</option>
          <option value="low">Low Urgency</option>
        </select>
      </div>
    </div>
  </div>

  <p class="text-sm text-gray-600 mb-6">Tickets awaiting user confirmation after a staff response.</p>

  <!-- Ticket Cards Grid -->
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

    <!-- Ticket Card -->
    <a href="ticket-detail.php?id=1024" class="block bg-white border border-gray-200 hover:border-blue-500 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
      <div class="p-4 space-y-3 hover:bg-gray-50 transition-colors duration-200 rounded-2xl h-full">
        <div class="flex justify-between items-start">
          <div class="space-y-0.5">
            <h2 class="text-lg font-semibold text-gray-800 group-hover:text-blue-700 transition">
              <i class="fa-solid fa-ticket-alt mr-1 text-blue-500"></i> [#1024] Access Issue - Payroll System
            </h2>
            <p class="text-sm text-gray-600">You responded 2 days ago. Awaiting user confirmation.</p>
          </div>
          <span class="text-xs font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
            Awaiting User
          </span>
        </div>

        <hr class="my-2 border-t border-dashed border-gray-200" />

        <div class="flex flex-wrap justify-between text-xs text-gray-500 gap-y-1 pt-1">
          <div><i class="fa-solid fa-building text-gray-400 mr-1"></i>IT Department</div>
          <div><i class="fa-solid fa-calendar-day text-gray-400 mr-1"></i>July 29, 2025</div>
          <div class="text-red-600 font-medium"><i class="fa-solid fa-bolt mr-1"></i>High Urgency</div>
        </div>
      </div>
    </a>

    <!-- Another Ticket Card -->
    <a href="ticket-detail.php?id=1031" class="block bg-white border border-gray-200 hover:border-blue-500 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
      <div class="p-4 space-y-3 hover:bg-gray-50 transition-colors duration-200 rounded-2xl h-full">
        <div class="flex justify-between items-start">
          <div class="space-y-0.5">
            <h2 class="text-lg font-semibold text-gray-800 group-hover:text-blue-700 transition">
              <i class="fa-solid fa-ticket-alt mr-1 text-blue-500"></i> [#1031] VPN Connectivity Troubles
            </h2>
            <p class="text-sm text-gray-600">You responded yesterday. Waiting for user feedback.</p>
          </div>
          <span class="text-xs font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
            Awaiting User
          </span>
        </div>

        <hr class="my-2 border-t border-dashed border-gray-200" />

        <div class="flex flex-wrap justify-between text-xs text-gray-500 gap-y-1 pt-1">
          <div><i class="fa-solid fa-building text-gray-400 mr-1"></i>Network Ops</div>
          <div><i class="fa-solid fa-calendar-day text-gray-400 mr-1"></i>July 30, 2025</div>
          <div class="text-orange-500 font-medium"><i class="fa-solid fa-exclamation mr-1"></i>Medium Urgency</div>
        </div>
      </div>
    </a>

  </div>
</div>