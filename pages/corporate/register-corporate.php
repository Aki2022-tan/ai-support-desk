<?php
require_once '../../includes/session-handler.php';

$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Corporate Registration | AI Support Desk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  </style>
</head>
<body class="bg-gradient-to-br from-sky-50 to-slate-100 min-h-screen flex items-center justify-center px-4">

  <main class="bg-white w-full max-w-xl rounded-3xl shadow-2xl px-8 py-10 fade-in">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-blue-700">Create Corporate Account</h1>
      <p class="text-sm text-gray-500 mt-1">Start streamlining your support operations with AI.</p>
    </div>

    <form action="handler/register-corporate-handler.php" method="POST" class="space-y-5">
      <!-- Company Name -->
      <div>
        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
        <div class="flex items-center border <?= isset($errors['company_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="building" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="text" id="company_name" name="company_name" placeholder="e.g. Acme Corporation" required
            value="<?= htmlspecialchars($old['company_name'] ?? '') ?>"
            class="w-full focus:outline-none text-sm" />
        </div>
        <?php if (isset($errors['company_name'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['company_name'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Contact Person -->
      <div>
        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
        <div class="flex items-center border <?= isset($errors['contact_person']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="user" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="text" id="contact_person" name="contact_person" placeholder="e.g. John Doe" required
            value="<?= htmlspecialchars($old['contact_person'] ?? '') ?>"
            class="w-full focus:outline-none text-sm" />
        </div>
        <?php if (isset($errors['contact_person'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['contact_person'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Contact Number -->
      <div>
        <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
        <div class="flex items-center border <?= isset($errors['contact_number']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="phone" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="text" id="contact_number" name="contact_number" placeholder="e.g. +63 912 345 6789" required
            value="<?= htmlspecialchars($old['contact_number'] ?? '') ?>"
            class="w-full focus:outline-none text-sm" />
        </div>
        <?php if (isset($errors['contact_number'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['contact_number'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Address -->
      <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Company Address</label>
        <textarea id="address" name="address" placeholder="e.g. 123 Ayala Ave, Makati, Philippines" required
          class="w-full px-3 py-2 border <?= isset($errors['address']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
        <?php if (isset($errors['address'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['address'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="flex items-center border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="mail" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="email" id="email" name="email" placeholder="e.g. support@acme.com" required
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            class="w-full focus:outline-none text-sm" />
        </div>
        <?php if (isset($errors['email'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['email'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="relative flex items-center border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="lock" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="password" id="password" name="password" placeholder="Enter your password" required
            class="w-full focus:outline-none text-sm pr-10" />
          <button type="button" onclick="toggleVisibility('password', this)"
            class="absolute right-3 text-sm text-gray-500">Show</button>
        </div>
        <?php if (isset($errors['password'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['password'] ?></p>
        <?php else: ?>
          <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters.</p>
        <?php endif; ?>
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <div class="relative flex items-center border <?= isset($errors['confirm_password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="lock" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required
            class="w-full focus:outline-none text-sm pr-10" />
          <button type="button" onclick="toggleVisibility('confirm_password', this)"
            class="absolute right-3 text-sm text-gray-500">Show</button>
        </div>
        <?php if (isset($errors['confirm_password'])): ?>
          <p class="text-sm text-red-600 mt-1"><?= $errors['confirm_password'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Submit -->
      <div>
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold transition">
          Create Corporate Account
        </button>
      </div>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Already have an account?
      <a href="../login.php" class="text-blue-600 font-medium hover:underline">Login here</a>
    </p>
  </main>

  <script>
    function toggleVisibility(id, btn) {
      const input = document.getElementById(id);
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.textContent = isHidden ? 'Hide' : 'Show';
    }

    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
  </script>
</body>
</html>