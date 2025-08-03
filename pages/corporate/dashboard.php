<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';

requireRole('corporate');

// Get department from session
$departmentName = $_SESSION['department'] ?? 'Your Department';
?>

<div class="p-6 space-y-6">

  <!-- Welcome Banner -->
  <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl p-6 shadow">
    <h1 class="text-2xl font-bold mb-1">Welcome to the <span class="capitalize"><?= htmlspecialchars($departmentName) ?></span> Department</h1>
    <p class="text-sm text-blue-100">This is your dashboard. Track tickets, monitor updates, and manage tasks related to your department.</p>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white p-5 rounded-xl border shadow hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">Your Tickets</h2>
          <p class="text-xl font-bold text-blue-600">[X]</p>
        </div>
        <div class="text-blue-600 bg-blue-100 p-2 rounded-full">
          <i class="fas fa-ticket-alt text-lg"></i>
        </div>
      </div>
    </div>
    <div class="bg-white p-5 rounded-xl border shadow hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">Open Requests</h2>
          <p class="text-xl font-bold text-orange-500">[Y]</p>
        </div>
        <div class="text-orange-500 bg-orange-100 p-2 rounded-full">
          <i class="fas fa-exclamation-circle text-lg"></i>
        </div>
      </div>
    </div>
    <div class="bg-white p-5 rounded-xl border shadow hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">Resolved This Week</h2>
          <p class="text-xl font-bold text-green-500">[Z]</p>
        </div>
        <div class="text-green-500 bg-green-100 p-2 rounded-full">
          <i class="fas fa-check-circle text-lg"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Ticket Search and Table -->
  <div class="bg-white p-5 rounded-xl shadow border">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold text-gray-700">Recent Tickets</h2>
      <input type="text" placeholder="Search tickets..." class="border rounded-md px-3 py-1 text-sm focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-2">Ticket ID</th>
            <th class="px-4 py-2">Subject</th>
            <th class="px-4 py-2">Submitted By</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Date</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2">#TCK1234</td>
            <td class="px-4 py-2">Email not syncing</td>
            <td class="px-4 py-2">jane.doe@company.com</td>
            <td class="px-4 py-2">
              <span class="inline-block text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Pending</span>
            </td>
            <td class="px-4 py-2 text-xs text-gray-500">Jul 30, 2025</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Ticket Chart -->
  <div class="bg-white p-5 rounded-xl shadow border">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Ticket Status Overview</h2>
    <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 italic">
      Chart placeholder (e.g., pie chart - use Chart.js)
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="bg-white p-5 rounded-xl shadow border">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Activity</h2>
    <ul class="space-y-3 text-sm text-gray-600">
      <li>✔️ You closed ticket #TCK1223 <span class="text-xs text-gray-400">- 3h ago</span></li>
      <li>📨 New ticket assigned: #TCK1251 <span class="text-xs text-gray-400">- 6h ago</span></li>
      <li>👥 Department update: John D. added to your group <span class="text-xs text-gray-400">- 1d ago</span></li>
    </ul>
  </div>

  <!-- Department Team Panel -->
  <div class="bg-white p-5 rounded-xl shadow border">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Department Team</h2>
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-sm text-gray-700">
      <li class="flex items-center gap-2"><i class="fas fa-user text-gray-400"></i> Sarah Johnson (Supervisor)</li>
      <li class="flex items-center gap-2"><i class="fas fa-user text-gray-400"></i> Ahmed Khan (Agent)</li>
      <li class="flex items-center gap-2"><i class="fas fa-user text-gray-400"></i> Olivia Lee (Agent)</li>
    </ul>
  </div>

  <!-- Help & Resources -->
  <div class="bg-white p-5 rounded-xl shadow border">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Help & Documentation</h2>
    <p class="text-sm text-gray-600 mb-2">Need help using the dashboard? Visit our resource center for step-by-step guides, video tutorials, and FAQs.</p>
    <a href="/help-center" class="inline-block mt-1 text-blue-600 hover:underline text-sm">
      📘 Go to Help Center
    </a>
  </div>
</div>