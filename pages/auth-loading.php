<?php
require_once '../includes/session-handler.php';

if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

switch ($_SESSION['role']) {
    case 'admin':
        $redirect = 'admin/dashboard.php';
        $message = 'Redirecting to Admin Panel...';
        break;
    case 'corporate':
        $redirect = 'corporate/dashboard.php';
        $message = 'Redirecting to Corporate Panel...';
        break;
    case 'user':
        $redirect = 'user/dashboard.php';
        $message = 'Redirecting to Your Support Desk...';
        break;
    default:
        $redirect = 'login.php';
        $message = 'Redirecting...';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loading Dashboard...</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .fade-in {
      animation: fadeIn 0.5s ease-out;
    }
    .fade-out {
      animation: fadeOut 0.5s ease-in forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
      from { opacity: 1; transform: translateY(0); }
      to   { opacity: 0; transform: translateY(-10px); }
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-white">
  <div id="spinnerContainer" class="text-center fade-in">
    <img src="/ai-support-desk/assets/images/spinner.svg"
         alt="Loading..."
         class="w-20 h-20 sm:w-28 sm:h-28 animate-spin mx-auto" />
    <p class="text-blue-600 mt-4 font-medium animate-pulse"><?= $message ?></p>
  </div>

  <script>
    setTimeout(() => {
      document.getElementById('spinnerContainer').classList.add('fade-out');
    }, 1500); // Start fade-out just before redirect

    setTimeout(() => {
      window.location.href = "<?= $redirect ?>";
    }, 2000); // Redirect after fade-out
  </script>
</body>
</html>