<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('admin');
?>

<section class="p-6">
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manage Users</h1>
    <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
      + Add New User
    </button>
  </div>

  <!-- User List -->
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <?php for ($i = 1; $i <= 6; $i++): ?>
      <div class="bg-white border rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-semibold text-gray-700">User <?= $i ?></h2>
            <p class="text-sm text-gray-500">user<?= $i ?>@domain.com</p>
            <p class="text-sm text-gray-500">Role: IT Associate</p>
          </div>
          <div class="text-right">
            <button class="text-blue-500 hover:underline text-sm">Edit</button><br>
            <button class="text-red-500 hover:underline text-sm mt-1">Remove</button>
          </div>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</section>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 hidden flex items-center justify-center">
  <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
    <h2 class="text-xl font-bold mb-4 text-gray-700">Add New User</h2>
    <form>
      <div class="mb-3">
        <label class="block mb-1 text-sm font-medium text-gray-600">Full Name</label>
        <input type="text" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring focus:outline-none" required>
      </div>
      <div class="mb-3">
        <label class="block mb-1 text-sm font-medium text-gray-600">Email</label>
        <input type="email" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring focus:outline-none" required>
      </div>
      <div class="mb-3">
        <label class="block mb-1 text-sm font-medium text-gray-600">Role</label>
        <select class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring focus:outline-none" required>
          <option value="">Select Role</option>
          <option value="Admin">Admin</option>
          <option value="IT Manager">IT Manager</option>
          <option value="IT Associate">IT Associate</option>
          <option value="Warehouse Manager">Warehouse Manager</option>
          <option value="Warehouse Associate">Warehouse Associate</option>
          <option value="HR">HR</option>
        </select>
      </div>
      <div class="mt-4 flex justify-end space-x-2">
        <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Create</button>
      </div>
    </form>
  </div>
</div>
