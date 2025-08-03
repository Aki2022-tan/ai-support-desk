<?php
require_once '../includes/db.php';
require_once '../includes/session-handler.php';

if (isset($_SESSION['user_id'])) {
    session_destroy(); // Force fresh login
    header("Location: login.php");
    exit;
}


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | AI Support Desk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  </style>
</head>
<body class="bg-gradient-to-br from-sky-50 to-slate-100 min-h-screen flex items-center justify-center px-4">

<?php include_once __DIR__ . '/../includes/components/toast.php'; ?>

<!-- Desktop Split Layout -->
<div class="flex w-full max-w-5xl rounded-3xl overflow-hidden shadow-2xl bg-white fade-in">

  <!-- LEFT PANEL (Desktop Only) -->
  <!-- LEFT PANEL (Desktop Only) -->
<div class="hidden sm:flex flex-col justify-center items-center w-1/2 bg-gradient-to-br from-indigo-700 via-blue-600 to-cyan-500 text-white px-6 py-10">
  <div class="text-center">
    <h2 class="text-3xl font-bold mb-4">Welcome to AI Support Desk</h2>
    <p class="text-sm text-white/80 leading-relaxed mb-6">
      Streamline your support with AI-enhanced solutions.<br />
      Secure. Fast. Reliable.
    </p>
    <img src="/ai-support-desk/assets/images/login.svg"
         alt="AI Illustration" class="w-72 h-auto mx-auto" />
  </div>
</div>

  <!-- RIGHT PANEL: Login Form -->
  <main class="w-full sm:w-1/2 px-8 py-10" aria-labelledby="login-heading">
    <div class="text-center mb-6">
      <h1 id="login-heading" class="text-3xl font-bold text-gray-800">AI Support Desk</h1>
      <p class="text-sm text-gray-500 mt-1">Welcome back! Please login to continue.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm text-center mb-4">
        <?= htmlspecialchars($_SESSION['success']); ?>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm text-center mb-4">
        <?= htmlspecialchars($_SESSION['error']); ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

   <form id="loginForm" action="handler/login-handler.php" method="POST" class="space-y-5" aria-describedby="login-description">
      <div id="login-description" class="sr-only">Login form for registered users</div>

      <div class="relative">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="mail" class="w-4 h-4 text-gray-400 mr-2"></i>
     <input type="email" name="email" id="email" required
       class="w-full focus:outline-none text-sm"
       placeholder="you@example.com" autocomplete="email"
       value="<?= htmlspecialchars($_SESSION['old_email'] ?? '') ?>" />
<?php unset($_SESSION['old_email']); ?>
        
        </div>
      </div>

      <div class="relative">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2 focus-within:ring focus-within:ring-blue-500">
          <i data-lucide="lock" class="w-4 h-4 text-gray-400 mr-2"></i>
          <input type="password" name="password" id="password" required
                 class="w-full focus:outline-none text-sm"
                 placeholder="••••••••" autocomplete="current-password" />
        </div>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition flex justify-center items-center gap-2">
        <i data-lucide="log-in" class="w-4 h-4"></i> Login
      </button>

    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Don’t have an account?
      <a href="#" onclick="toggleModal(true)" class="text-blue-600 font-medium hover:underline">Register Here</a>
    </p>
  </main>
</div>

<!-- Registration Modal -->
<!-- Redesigned Registration Modal -->
<div id="registerModal"
     class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-md z-50 flex items-center justify-center hidden"
     onclick="handleOverlayClick(event)">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 sm:p-8 relative fade-in"
       onclick="event.stopPropagation();">
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-semibold text-gray-800">Create Your Account</h2>
      <button onclick="toggleModal(false)" class="text-gray-400 hover:text-red-500 text-2xl font-bold leading-none">&times;</button>
    </div>

    <!-- Modal Description -->
    <p class="text-sm text-gray-500 mb-4">Select your registration type to get started:</p>

    <!-- Action Buttons -->
    <div class="space-y-4">
      <a href="user/register-user.php"
         class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-all shadow-sm">
        <i data-lucide="user" class="w-5 h-5"></i> Register as <span class="font-semibold">User</span>
      </a>
      <a href="corporate/register-corporate.php"
         class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-all shadow-sm">
        <i data-lucide="briefcase" class="w-5 h-5"></i> Register as <span class="font-semibold">Corporate</span>
      </a>
    </div>
  </div>
</div>

<script>
  function toggleModal(show) {
    const modal = document.getElementById('registerModal');
    modal.classList.toggle('hidden', !show);
  }

  function handleOverlayClick(event) {
    if (event.target.id === 'registerModal') toggleModal(false);
  }

  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const form = document.getElementById('loginForm');
    const overlay = document.getElementById('loginOverlay');
    const main = document.querySelector('main');

    form.addEventListener('submit', function (e) {
      // Allow native HTML5 validation to run first
      if (!form.checkValidity()) return;

      // Delay showing the overlay to let browser handle validation display
      setTimeout(() => {
        overlay.classList.remove('hidden');      // show spinner overlay
        main.classList.add('blur-sm');           // blur login form

        // Disable all inputs/buttons AFTER validation
        form.querySelectorAll('input, button').forEach(el => el.disabled = true);
      }, 50);
    });
  });
</script>

</body>
</html>