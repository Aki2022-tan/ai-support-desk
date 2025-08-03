<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';

requireRole('corporate');
?>

<div class="p-6">
  <h1 class="text-2xl font-bold text-gray-800 mb-4">Assigned Tickets</h1>

  <!-- Filter & Search Bar -->
  <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <div>
      <label for="urgency" class="block text-sm font-medium text-gray-700 mb-1">Filter by Urgency:</label>
      <select id="urgency" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option>All</option>
        <option>Low</option>
        <option>Medium</option>
        <option>High</option>
        <option>Critical</option>
      </select>
    </div>

    <div class="w-full md:w-1/3">
      <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Ticket:</label>
      <input type="text" id="search" placeholder="Search by subject, ID, or user" class="w-full border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
  </div>

  <!-- Tickets Table -->
  <div class="bg-white shadow rounded-xl border overflow-x-auto">
    <table class="min-w-full text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 font-medium">
        <tr>
          <th class="px-4 py-3">Ticket ID</th>
          <th class="px-4 py-3">Subject</th>
          <th class="px-4 py-3">Urgency</th>
          <th class="px-4 py-3">Submitted By</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Date Assigned</th>
          <th class="px-4 py-3">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <!-- Sample row -->
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-3 font-medium">#TCK2025</td>
          <td class="px-4 py-3">Printer Not Working</td>
          <td class="px-4 py-3">
            <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">High</span>
          </td>
          <td class="px-4 py-3">john.doe@example.com</td>
          <td class="px-4 py-3">
            <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-1 rounded-full">In Progress</span>
          </td>
          <td class="px-4 py-3 text-xs text-gray-500">Jul 28, 2025</td>
          <td class="px-4 py-3">
            <a href="ticket-detail.php" class="text-blue-600 hover:underline text-sm">View</a>
          </td>
        </tr>
        <!-- Add more rows dynamically -->
      </tbody>
    </table>
  </div>
</div>