<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('corporate');
?>

<script>
  function toggleNotes() {
    const notes = document.getElementById('internalNotes');
    notes.classList.toggle('hidden');
  }

  function startSLACountdown() {
    const timer = document.getElementById('slaTimer');
    let seconds = 3600;

    setInterval(() => {
      const mins = Math.floor(seconds / 60);
      const secs = seconds % 60;
      timer.textContent = `${mins}m ${secs < 10 ? '0' : ''}${secs}s`;

      if (seconds < 600) {
        timer.classList.remove('text-green-600');
        timer.classList.add('text-red-600');
      }

      if (seconds > 0) seconds--;
    }, 1000);
  }

  document.addEventListener("DOMContentLoaded", startSLACountdown);
</script>

<div class="max-w-6xl mx-auto px-4 py-6">
  <!-- Ticket Header -->
  <h1 class="text-2xl font-bold mb-4">Ticket #TCK12345 – Can't Access Dashboard</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="md:col-span-2 bg-white p-6 rounded-xl shadow">
      
      <!-- Thread Timeline -->
      <div class="space-y-4 mb-6 max-h-[400px] overflow-y-auto pr-2">
        <!-- User Message -->
        <div class="bg-gray-100 p-4 rounded border-l-4 border-gray-300">
          <p class="text-sm text-gray-600 font-semibold">
            Rogelio Santos <span class="text-xs text-gray-400">(User)</span> · Jul 30, 2025
          </p>
          <p class="text-gray-800 mt-1">I’m unable to access the dashboard even after logging in successfully.</p>
        </div>

        <!-- Corporate Message -->
        <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-400">
          <p class="text-sm text-blue-700 font-semibold">
            Angelo Dela Cruz <span class="text-xs text-gray-500">(Corporate)</span> · Jul 31, 2025
          </p>
          <p class="text-gray-800 mt-1">We are checking the access control settings and will get back to you shortly.</p>
        </div>

        <!-- AI Suggested -->
        <div class="bg-yellow-50 p-4 rounded border-l-4 border-yellow-400">
          <p class="text-sm text-yellow-700 font-semibold">
            AI Assistant <span class="text-xs text-gray-500">(Suggested)</span> · Jul 31, 2025
          </p>
          <p class="text-gray-800 mt-1">Try clearing your browser cache and logging in again.</p>
        </div>
      </div>

      <!-- Response Form -->
      <form class="space-y-4">
        <!-- AI Tone Switcher -->
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-gray-700">AI Tone:</label>
          <select class="border border-gray-300 rounded text-sm px-2 py-1">
            <option value="professional">Professional</option>
            <option value="empathetic">Empathetic</option>
            <option value="concise">Concise</option>
            <option value="detailed">Detailed</option>
          </select>
        </div>

        <!-- Rich Text Input -->
        <label class="block text-sm font-medium text-gray-700">AI-Suggested Response (Editable)</label>
        <div class="border border-gray-300 rounded-lg p-3 bg-yellow-50">
          <div
            contenteditable="true"
            class="min-h-[100px] outline-none text-sm"
            placeholder="Type your response here...">
            Hi Rogelio, we are checking the access control settings and will get back to you shortly.
          </div>
        </div>

        <!-- File Upload -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Attach File (Optional)</label>
          <input
            type="file"
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-2"
          />
        </div>

        <!-- Tags -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
          <input
            type="text"
            placeholder="e.g., login, urgent, dashboard"
            class="w-full border border-gray-300 rounded p-2 text-sm"
          />
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Send Response
        </button>
      </form>

      <!-- Internal Notes -->
      <div class="mt-6">
        <button
          type="button"
          onclick="toggleNotes()"
          class="text-sm text-blue-600 hover:underline">
          + Add Internal Notes (Private)
        </button>
        <div id="internalNotes" class="hidden mt-2">
          <textarea
            class="w-full border border-yellow-400 rounded p-2 text-sm"
            rows="3"
            placeholder="Add internal notes for your team only..."></textarea>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="bg-white p-6 rounded-xl shadow space-y-4 text-sm">
      <div><strong>Status:</strong> <span class="text-yellow-600">Open</span></div>
      <div><strong>Priority:</strong> <span class="text-red-600">Urgent</span></div>
      <div><strong>Department:</strong> IT Support</div>
      <div><strong>Submitted By:</strong> rogelio.santos@company.com</div>
      <div><strong>Submitted On:</strong> Jul 30, 2025</div>
      <div><strong>Due By:</strong> Aug 2, 2025</div>

      <!-- SLA Timer -->
      <div class="flex items-center gap-2">
        <strong>SLA:</strong>
        <span id="slaTimer" class="text-green-600 font-semibold">Loading...</span>
      </div>

      <hr class="my-2" />

      <!-- Status Dropdown -->
      <div>
        <label class="block font-medium text-gray-700 mb-1">Change Status</label>
        <select class="w-full border border-gray-300 rounded p-2">
          <option>Open</option>
          <option>In Progress</option>
          <option>Resolved</option>
          <option>Closed</option>
        </select>
      </div>
    </div>
  </div>
</div>