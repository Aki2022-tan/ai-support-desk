<!DOCTYPE html><html lang="en">
<head>
  <!-- Basic Page Metadata -->
  <meta charset="UTF-8">
  <title>AI Support Desk | Welcome</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- SEO Metadata -->  <meta name="description" content="AI-powered ticketing platform for fast, responsive support.">
  <meta property="og:title" content="AI Support Desk | Smart Ticketing Platform">
  <meta property="og:description" content="Streamline your customer support with Cohere-powered AI.">
  <meta property="og:image" content="assets/images/helpdesk.svg">
  <meta name="robots" content="index, follow">  <!-- Tailwind CSS -->  <script src="https://cdn.tailwindcss.com"></script>  <!-- Custom CSS -->  <link rel="stylesheet" href="assets/css/style.css">  <!-- Favicon -->  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head><body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans"><!-- Header --><header class="bg-white/70 backdrop-blur sticky top-0 z-50 px-4 py-3 shadow-sm">
  <div class="max-w-6xl mx-auto flex items-center justify-between">
    <div class="flex flex-col">
      <h1 class="text-2xl font-bold text-blue-700 tracking-tight">AI Support Desk</h1>
      <p class="text-sm text-gray-500">Powered by Cohere AI</p>
    </div>
    <div class="flex items-center space-x-6 text-sm font-medium">
      <a href="login.php" class="text-gray-700 hover:text-blue-600 transition">Login</a>
      <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition">
        Register
      </a>
    </div>
  </div>
</header><!-- Hero --><section class="bg-gradient-to-br from-blue-50 to-white py-24">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 items-center gap-16">
    <!-- Hero Text -->
    <div class="md:max-w-lg">
      <h2 class="text-4xl font-extrabold text-blue-900 mb-4 leading-tight">Your Smart Support Partner</h2>
      <p class="text-lg text-gray-600 mb-6">
        Streamline support with AI-powered ticketing. Fast, responsive, and user-friendly.
      </p>
      <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold transition">
        Get Started
      </a>
    </div>
    <!-- Hero Image -->
    <div class="hidden md:block bg-white/30 backdrop-blur rounded-xl p-4 shadow-lg">
      <img src="assets/images/helpdesk.svg" alt="AI Helpdesk Illustration" class="w-full max-w-md mx-auto" onerror="this.style.display='none'">
    </div>
  </div>
</section><!-- Features --><section class="bg-white py-20">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <h3 class="text-3xl font-bold text-gray-800 mb-12">Why Choose AI Support Desk?</h3>
    <div class="grid md:grid-cols-3 gap-10"><!-- Feature 1 -->
  <div class="bg-white border border-blue-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
    <h4 class="text-lg font-semibold mb-2">Easy Ticket Submission</h4>
    <p class="text-gray-600 text-sm">Quickly report issues using a streamlined form with categories and AI suggestions.</p>
  </div>

  <!-- Feature 2 -->
  <div class="bg-white border border-green-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
    <h4 class="text-lg font-semibold mb-2">AI Summarized Insights</h4>
    <p class="text-gray-600 text-sm">Each ticket is summarized by AI to speed up admin response time.</p>
  </div>

  <!-- Feature 3 -->
  <div class="bg-white border border-purple-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
    <h4 class="text-lg font-semibold mb-2">Responsive Admin Panel</h4>
    <p class="text-gray-600 text-sm">Admins can easily track and resolve issues in real time.</p>
  </div>
</div>

  </div>
</section><!-- Testimonials --><section class="bg-gray-50 py-16">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h4 class="text-2xl font-bold text-blue-800 mb-4">What Users Say</h4>
    <blockquote class="text-gray-600 italic text-base leading-relaxed">
      “AI Support Desk helped us resolve 80% of tickets faster. The AI summaries are a game-changer.”
    </blockquote>
    <p class="mt-3 font-semibold text-gray-700">– Maria D., Support Manager</p>
  </div>
</section><!-- CTA --><section class="bg-blue-50 py-16">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h4 class="text-3xl font-bold text-blue-800 mb-4">Start Resolving Support Requests Smarter</h4>
    <p class="text-gray-600 mb-6">Whether you're a user or an admin, AI Support Desk makes support easy and efficient.</p>
    <a href="register.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium shadow transition">
      Create Your Free Account
    </a>
  </div>
</section><!-- Footer --><footer class="bg-white border-t text-sm text-gray-500 py-6 text-center mt-auto">
  &copy; <?= date('Y') ?> AI Support Desk. Built by Rogienald.
</footer></body>
</html>