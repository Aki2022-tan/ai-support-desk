<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';

requireRole('corporate');
?>

<div class="p-6 sm:p-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-2">Resolved Tickets</h1>
  <p class="text-gray-600 mb-6 text-sm">These tickets have been successfully resolved either by user confirmation or automatic closure.</p>

  <!-- Resolved Ticket Grid -->
  <div class="grid gap-6 sm:grid-cols-2">

    <!-- Ticket Card -->
    <div class="rounded-2xl border border-green-200 bg-white shadow-sm hover:shadow-md transition-all p-5">
      <div class="flex justify-between items-start mb-3">
        <div>
          <h2 class="text-lg font-semibold text-green-700">[#1007] Printer not connecting</h2>
          <p class="text-sm text-gray-600 mt-1">Resolved with user confirmation and marked closed.</p>
        </div>
        <span class="text-xs font-medium text-green-800 bg-green-100 px-2 py-1 rounded-full">Resolved</span>
      </div>

      <div class="text-sm text-gray-500 space-y-1 mb-4">
        <div><strong>Assigned Department:</strong> IT Support</div>
        <div><strong>Resolved On:</strong> July 28, 2025</div>
      </div>

      <a href="ticket-detail.php?id=1007" class="inline-block text-sm text-blue-600 hover:text-blue-800 font-medium transition">View Conversation →</a>
    </div>

    <!-- Ticket Card -->
    <div class="rounded-2xl border border-green-200 bg-white shadow-sm hover:shadow-md transition-all p-5">
      <div class="flex justify-between items-start mb-3">
        <div>
          <h2 class="text-lg font-semibold text-green-700">[#1008] Intranet portal access issue</h2>
          <p class="text-sm text-gray-600 mt-1">No user feedback within 3 days. Ticket auto-closed.</p>
        </div>
        <span class="text-xs font-medium text-green-800 bg-green-100 px-2 py-1 rounded-full">Resolved</span>
      </div>

      <div class="text-sm text-gray-500 space-y-1 mb-4">
        <div><strong>Assigned Department:</strong> Network Operations</div>
        <div><strong>Resolved On:</strong> July 29, 2025</div>
      </div>

      <a href="ticket-detail.php?id=1008" class="inline-block text-sm text-blue-600 hover:text-blue-800 font-medium transition">View Conversation →</a>
    </div>

  </div>
</div>