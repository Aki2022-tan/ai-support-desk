<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AI Support Desk | Welcome</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- 🌐 Meta Tags for SEO -->
  <meta name="description" content="AI-powered ticketing platform for fast, responsive support. Smart summaries, real-time admin tools, and simple user experience.">
  <meta property="og:title" content="AI Support Desk | Smart Ticketing Platform">
  <meta property="og:description" content="Streamline your customer support with Cohere-powered AI. Get started today.">
  <meta property="og:image" content="assets/images/helpdesk.svg">
  <meta name="robots" content="index, follow">

  <!-- ✅ Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- 🧠 Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- 🌟 Favicon -->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

<!-- 🔷 Header -->
<header class="bg-white shadow-sm sticky top-0 z-50 px-4 py-3">
  <div class="max-w-6xl mx-auto flex items-center justify-between">
    <!-- Branding -->
    <div class="flex flex-col leading-tight">
      <h1 class="text-xl font-extrabold text-blue-700 tracking-tight">AI Support Desk</h1>
      <p class="text-xs text-gray-500 mt-0.5">Powered by Cohere AI</p>
    </div>

    <!-- Navigation Links -->
    <div class="flex items-center space-x-6 text-sm font-medium">
      <a href="pages/login.php" class="text-gray-700 hover:text-blue-600 transition">Login</a>
      <a onclick="toggleModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow-sm transition">
        Register
      </a>
    </div>
  </div>
</header>

<!-- 🎯 Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-white py-24 fade-up">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 items-center gap-12">
    <div class="md:max-w-lg">
      <h2 class="text-4xl font-extrabold text-blue-900 leading-tight mb-4">Your Smart Support Partner</h2>
      <p class="text-lg text-gray-600 mb-6">
        Streamline support with AI-powered ticketing. Fast, responsive, and user-friendly for everyone.
      </p><!-- Call-to-Action Button to Open Modal -->
<button onclick="toggleModal(true)"
        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold transition">
  Get Started
</button>
  
    </div>
    <div class="hidden md:block backdrop-blur-md bg-white/30 rounded-lg p-4 shadow">
      <img src="assets/images/helpdesk.svg" alt="AI Helpdesk Illustration" class="w-full max-w-md mx-auto drop-shadow-sm" onerror="this.style.display='none'">
    </div>
  </div>
</section>

<!-- 💡 Features -->
<section class="bg-white py-20 fade-up">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-12">✨ Why Choose AI Support Desk?</h3>
    <div class="grid md:grid-cols-3 gap-10">
      <div class="bg-blue-50 p-6 rounded-lg shadow hover:shadow-md transition">
        <div class="text-blue-600 text-4xl mb-4">📨</div>
        <h4 class="text-lg font-semibold mb-2">Easy Ticket Submission</h4>
        <p class="text-gray-600 text-sm">Quickly report issues using a streamlined form with built-in categories and suggestions.</p>
      </div>
      <div class="bg-green-50 p-6 rounded-lg shadow hover:shadow-md transition">
        <div class="text-green-600 text-4xl mb-4">📊</div>
        <h4 class="text-lg font-semibold mb-2">AI Summarized Insights</h4>
        <p class="text-gray-600 text-sm">Each ticket gets a smart summary powered by Cohere AI for faster admin resolution.</p>
      </div>
      <div class="bg-purple-50 p-6 rounded-lg shadow hover:shadow-md transition">
        <div class="text-purple-600 text-4xl mb-4">🛠️</div>
        <h4 class="text-lg font-semibold mb-2">Responsive Admin Panel</h4>
        <p class="text-gray-600 text-sm">Admins can easily manage, track, and respond to user concerns in real-time.</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ CTA Section -->
<section class="bg-blue-50 py-14 fade-up">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h4 class="text-2xl md:text-3xl font-bold text-blue-800 mb-4">Start Resolving Support Requests Smarter</h4>
    <p class="text-gray-600 mb-6">Whether you're a user or an admin, AI Support Desk makes support fast, clear, and effortless.</p>
    <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium shadow transition">Create Your Free Account</a>
  </div>
</section>

<!-- 👣 Footer -->
<footer class="bg-white border-t text-sm text-gray-500 py-6 text-center mt-auto">
  &copy; <?= date('Y') ?> AI Support Desk. Built with 💙 by Rogienald.
</footer>

<!-- ======================================
     MODAL: Register as User or Corporate
     Triggered from "Get Started" CTA
     ====================================== -->
<!-- Registration Modal Overlay -->
<!-- Lucide Icon CDN -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Registration Modal -->
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

<!-- ========================
     MODAL CONTROL SCRIPT
     ======================== -->
<script>
  // Controls modal visibility
  function toggleModal(show) {
    const modal = document.getElementById('registerModal');
    modal.classList.toggle('hidden', !show);
  }

  // Closes modal on overlay click (but not inner content)
  function handleOverlayClick(event) {
    if (event.target.id === 'registerModal') {
      toggleModal(false);
    }
  }

  // Initialize Lucide icons after page load
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
  });
</script>
</body>
</html>