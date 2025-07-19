<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic Page Metadata -->
  <meta charset="UTF-8">
  <title>AI Support Desk | Welcome</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Search Engine Optimization Metadata -->
  <meta name="description" content="AI-powered ticketing platform for fast, responsive support. Smart summaries, real-time admin tools, and simple user experience.">
  <meta property="og:title" content="AI Support Desk | Smart Ticketing Platform">
  <meta property="og:description" content="Streamline your customer support with Cohere-powered AI. Get started today.">
  <meta property="og:image" content="assets/images/helpdesk.svg">
  <meta name="robots" content="index, follow">

  <!-- Tailwind CSS Integration for Styling -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Custom CSS File -->
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- Favicon Icon -->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

<!-- Primary Header Section -->
<header class="bg-white shadow-sm sticky top-0 z-50 px-4 py-3">
  <div class="max-w-6xl mx-auto flex items-center justify-between">
    
    <!-- Branding: Logo and Tagline -->
    <div class="flex flex-col leading-tight">
      <h1 class="text-xl font-extrabold text-blue-700 tracking-tight">AI Support Desk</h1>
      <p class="text-xs text-gray-500 mt-0.5">Powered by Cohere AI</p>
    </div>

    <!-- Top-Level Navigation: Login/Register -->
    <div class="flex items-center space-x-6 text-sm font-medium">
      <a href="login.php" class="text-gray-700 hover:text-blue-600 transition">Login</a>
      <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow-sm transition">
        Register
      </a>
    </div>
  </div>
</header>

<!-- Hero Section: Value Proposition and CTA -->
<section class="bg-gradient-to-br from-blue-50 to-white py-24 fade-up">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 items-center gap-12">
    
    <!-- Hero Copy and CTA Button -->
    <div class="md:max-w-lg">
      <h2 class="text-4xl font-extrabold text-blue-900 leading-tight mb-4">Your Smart Support Partner</h2>
      <p class="text-lg text-gray-600 mb-6">
        Streamline support with AI-powered ticketing. Fast, responsive, and user-friendly for everyone.
      </p>
      <a href="register.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold transition">
        Get Started
      </a>
    </div>

    <!-- Illustration for Visual Engagement -->
    <div class="hidden md:block backdrop-blur-md bg-white/30 rounded-lg p-4 shadow">
      <img src="assets/images/helpdesk.svg" alt="AI Helpdesk Illustration" class="w-full max-w-md mx-auto drop-shadow-sm" onerror="this.style.display='none'">
    </div>
  </div>
</section>

<!-- Features Section: Platform Highlights -->
<section class="bg-white py-20 fade-up">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-12">Why Choose AI Support Desk?</h3>
    <div class="grid md:grid-cols-3 gap-10">

      <!-- Feature Block 1: Ticket Submission -->
      <div class="bg-blue-50 p-6 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-lg font-semibold mb-2">Easy Ticket Submission</h4>
        <p class="text-gray-600 text-sm">Quickly report issues using a streamlined form with built-in categories and suggestions.</p>
      </div>

      <!-- Feature Block 2: Smart Summaries -->
      <div class="bg-green-50 p-6 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-lg font-semibold mb-2">AI Summarized Insights</h4>
        <p class="text-gray-600 text-sm">Each ticket gets a smart summary powered by Cohere AI for faster admin resolution.</p>
      </div>

      <!-- Feature Block 3: Admin Panel -->
      <div class="bg-purple-50 p-6 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-lg font-semibold mb-2">Responsive Admin Panel</h4>
        <p class="text-gray-600 text-sm">Admins can easily manage, track, and respond to user concerns in real-time.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonial Section -->
<section class="bg-gray-50 py-14 fade-up">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h4 class="text-xl md:text-2xl font-bold text-blue-800 mb-4">What Users Say</h4>
    <blockquote class="text-gray-600 italic text-base leading-relaxed">
      “AI Support Desk helped us resolve 80% of tickets faster. The AI summaries are a game-changer.”
    </blockquote>
    <p class="mt-3 font-semibold text-gray-700">– Maria D., Support Manager</p>
  </div>
</section>

<!-- Call-to-Action Section -->
<section class="bg-blue-50 py-14 fade-up">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h4 class="text-2xl md:text-3xl font-bold text-blue-800 mb-4">Start Resolving Support Requests Smarter</h4>
    <p class="text-gray-600 mb-6">Whether you're a user or an admin, AI Support Desk makes support fast, clear, and effortless.</p>
    <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium shadow transition">
      Create Your Free Account
    </a>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white border-t text-sm text-gray-500 py-6 text-center mt-auto">
  <!-- Dynamic Year Rendering via PHP -->
  &copy; <?= date('Y') ?> AI Support Desk. Built with 💙 by Rogienald.
</footer>

</body>
</html>