<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('user');

// Simulated counts — replace these with actual DB queries later
$totalOpen = 2;
$totalAwaiting = 1;
$totalResolved = 3;
?>

<div class="p-6">

  <!-- Welcome Banner -->
  <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl p-6 shadow mb-6">
    <h1 class="text-2xl font-bold mb-1">Welcome to Your Support Dashboard</h1>
    <p class="text-sm text-blue-100">Submit tickets, track your support requests, and stay updated with the latest responses.</p>
  </div>

  <!-- Ticket Summary Cards (no color borders) -->
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="text-lg font-semibold text-gray-800">Open Tickets</h2>
      <p class="text-3xl font-bold text-yellow-600 mt-1"><?= $totalOpen ?></p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="text-lg font-semibold text-gray-800">Awaiting Staff Reply</h2>
      <p class="text-3xl font-bold text-blue-600 mt-1"><?= $totalAwaiting ?></p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="text-lg font-semibold text-gray-800">Resolved Tickets</h2>
      <p class="text-3xl font-bold text-green-600 mt-1"><?= $totalResolved ?></p>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Quick Actions</h2>
    <div class="flex flex-wrap gap-4">
      <a href="submit-ticket.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">Submit New Ticket</a>
      <a href="my-tickets.php" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">View My Tickets</a>
      <a href="profile.php" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Update Profile</a>
    </div>
  </div>

  <!-- Recent Tickets -->
  <div>
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Recent Activity</h2>
    <div class="space-y-3">
      <div class="bg-white border border-gray-200 rounded-xl shadow p-4">
        <div class="flex justify-between items-center mb-1">
          <h3 class="font-semibold text-gray-800 text-sm">[#1041] Can’t access HR portal</h3>
          <span class="text-xs font-medium bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Open</span>
        </div>
        <p class="text-xs text-gray-500">Submitted on July 28, 2025</p>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow p-4">
        <div class="flex justify-between items-center mb-1">
          <h3 class="font-semibold text-gray-800 text-sm">[#1038] Email not syncing</h3>
          <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Awaiting Staff</span>
        </div>
        <p class="text-xs text-gray-500">Last updated July 27, 2025</p>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow p-4">
        <div class="flex justify-between items-center mb-1">
          <h3 class="font-semibold text-gray-800 text-sm">[#1022] Printer issue resolved</h3>
          <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Resolved</span>
        </div>
        <p class="text-xs text-gray-500">Resolved on July 25, 2025</p>
      </div>
    </div>
  </div>
</div>