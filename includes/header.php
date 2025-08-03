<?php
require_once __DIR__ . '/session-handler.php';
require_once __DIR__ . '/db.php'; // Make sure your DB connection is available

$role = $_SESSION['role'] ?? null;
$status = $_SESSION['status'] ?? null;
$isLoggedIn = isUserLoggedIn();
$currentPage = basename($_SERVER['PHP_SELF']);

function getDashboardLink($role) {
    return match($role) {
        'admin' => '/pages/admin/dashboard.php',
        'corporate' => '/pages/corporate/dashboard.php',
        'user' => '/pages/user/dashboard.php',
        default => '/index.php',
    };
}

function isActive($page) {
    global $currentPage;
    return $currentPage === $page ? 'text-blue-700 font-semibold' : 'text-gray-700';
}

// ✅ Get pending corporates count if admin
$pendingCount = 0;
if ($isLoggedIn && $role === 'admin') {
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'corporate' AND status = 'pending'");
        $stmt->execute();
        $pendingCount = (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error fetching pending corporates: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AI Support Desk | Welcome</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="AI-powered ticketing platform for fast, responsive support.">
  <meta property="og:title" content="AI Support Desk | Smart Ticketing Platform">
  <meta property="og:description" content="Streamline your customer support with Cohere-powered AI.">
  <meta property="og:image" content="/assets/images/helpdesk.svg">
  <meta name="robots" content="index, follow">

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">

<header class="bg-white shadow-sm sticky top-0 z-50 px-4 py-3">
  <div class="max-w-6xl mx-auto flex items-center justify-between">

    <div class="flex flex-col leading-tight">
      <h1 class="text-xl font-extrabold text-blue-700 tracking-tight">AI Support Desk</h1>
      <p class="text-xs text-gray-500 mt-0.5">Powered by Cohere AI</p>
    </div>

    <?php if ($isLoggedIn && $status === 'active'): ?>
      <div class="flex items-center space-x-4 md:hidden">
        <?php if ($role === 'user'): ?>
          <button class="relative">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">!</span>
          </button>
        <?php endif; ?>

<button onclick="toggleUserMenu()" class="relative focus:outline-none">
  <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
       viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
  </svg>
  <?php if ($role === 'admin' && $pendingCount > 0): ?>
    <span class="absolute top-[-6px] right-[-6px] bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow">
      <?= $pendingCount ?>
    </span>
  <?php endif; ?>
</button>

        </button>
      </div>

      <!-- 💻 Desktop Nav -->
      <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
        <?php if ($role === 'user'): ?>
          <a href="dashboard.php" class="<?= isActive('dashboard.php') ?> hover:text-blue-600">Dashboard</a>
          <a href="submit-ticket.php" class="<?= isActive('submit-ticket.php') ?> hover:text-blue-600">Submit Ticket</a>
          <a href="my-tickets.php" class="<?= isActive('my-tickets.php') ?> hover:text-blue-600">My Tickets</a>
          <a href="profile.php" class="<?= isActive('profile.php') ?> hover:text-blue-600">Profile</a>

        <?php elseif ($role === 'corporate'): ?>
          <a href="dashboard.php" class="<?= isActive('dashboard.php') ?> hover:text-blue-600">Dashboard</a>
          <a href="assigned-tickets.php" class="<?= isActive('assigned-tickets.php') ?> hover:text-blue-600">Assigned</a>
          <a href="responded-tickets.php" class="<?= isActive('responded-tickets.php') ?> hover:text-blue-600">Responded</a>
          <a href="resolved-tickets.php" class="<?= isActive('resolved-tickets.php') ?> hover:text-blue-600">Resolved</a>
          <a href="profile.php" class="<?= isActive('profile.php') ?> hover:text-blue-600">Profile</a>

        <?php elseif ($role === 'admin'): ?>
          <a href="dashboard.php" class="<?= isActive('dashboard.php') ?> hover:text-blue-600">Dashboard</a>
          <a href="manage-users.php" class="<?= isActive('manage-users.php') ?> hover:text-blue-600">Manage Users</a>
      <a href="pending-corporates.php" class="relative pr-6 <?= isActive('pending-corporates.php') ?> hover:text-blue-600">
  Pending Accounts
  <?php if ($pendingCount > 0): ?>
    <span class="absolute top-[-0.4rem] right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 shadow">
      <?= $pendingCount ?>
    </span>
  <?php endif; ?>
</a>
          <a href="settings.php" class="<?= isActive('settings.php') ?> hover:text-blue-600">Settings</a>
        <?php endif; ?>

        <a href="/ai-support-desk/pages/handler/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-md shadow-sm transition">
          Logout
        </a>
      </nav>

      <!-- 📱 Mobile Dropdown -->
    <div id="userMobileMenu"
     class="md:hidden absolute top-16 right-4 bg-white border rounded-lg shadow-lg p-4 space-y-3 min-w-[200px] w-fit max-w-[90vw] hidden">
        <?php if ($role === 'user'): ?>
          <a href="dashboard.php" class="block <?= isActive('dashboard.php') ?> hover:text-blue-600">Dashboard</a>
          <a href="submit-ticket.php" class="block <?= isActive('submit-ticket.php') ?> hover:text-blue-600">Submit Ticket</a>
          <a href="my-tickets.php" class="block <?= isActive('my-tickets.php') ?> hover:text-blue-600">My Tickets</a>
          <a href="profile.php" class="block <?= isActive('profile.php') ?> hover:text-blue-600">Profile</a>

        <?php elseif ($role === 'corporate'): ?>
          <a href="dashboard.php" class="block <?= isActive('dashboard.php') ?> hover:text-blue-600">Dashboard</a>
          <a href="assigned-tickets.php" class="block <?= isActive('assigned-tickets.php') ?> hover:text-blue-600">Assigned</a>
          <a href="responded-tickets.php" class="block <?= isActive('responded-tickets.php') ?> hover:text-blue-600">Responded</a>
          <a href="resolved-tickets.php" class="block <?= isActive('resolved-tickets.php') ?> hover:text-blue-600">Resolved</a>
          <a href="profile.php" class="block <?= isActive('profile.php') ?> hover:text-blue-600">Profile</a>

        <?php elseif ($role === 'admin'): ?>
          <a href="dashboard.php" class="block <?= isActive('dashboard.php') ?> hover:text-blue-600">Dashboard</a>
          <a href="manage-users.php" class="block <?= isActive('manage-users.php') ?> hover:text-blue-600">Manage Users</a>
<a href="pending-corporates.php" class="relative block pr-8 <?= isActive('pending-corporates.php') ?> hover:text-blue-600">
  Pending Accounts
  <?php if ($pendingCount > 0): ?>
    <span class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 shadow">
      <?= $pendingCount ?>
    </span>
  <?php endif; ?>
</a>
          <a href="settings.php" class="block <?= isActive('settings.php') ?> hover:text-blue-600">Settings</a>
        <?php endif; ?>

        <a href="/ai-support-desk/pages/handler/logout.php" class="block text-red-600 hover:text-red-800">Logout</a>
      </div>

    <?php else: ?>
      <nav class="flex items-center space-x-6 text-sm font-medium">
        <a href="/pages/login.php" class="text-gray-700 hover:text-blue-600 transition">Login</a>
        <a onclick="toggleModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow-sm transition">
          Register
        </a>
      </nav>
    <?php endif; ?>
  </div>
</header>

<script>
function toggleUserMenu() {
  document.getElementById('userMobileMenu').classList.toggle('hidden');
}
</script>

<?php if ($isLoggedIn && $role === 'user'): ?>
  <script src="/ai-support-desk/assets/js/subject-suggestion.js"></script>
<?php endif; ?>