<?php
require_once __DIR__ . '/../../includes/session-handler.php';

// Destroy session
logout();

// Optional redirect path
$redirect = '../login.php';
$message = 'Logging you out securely...';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logging Out...</title>
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
  <div id="logoutSpinner" class="text-center fade-in">
    <img src="/ai-support-desk/assets/images/spinner.svg"
         alt="Logging out..."
         class="w-20 h-20 sm:w-28 sm:h-28 animate-spin mx-auto" />
    <p class="text-blue-600 mt-4 font-medium animate-pulse"><?= $message ?></p>
  </div>

  <script>
    setTimeout(() => {
      document.getElementById('logoutSpinner').classList.add('fade-out');
    }, 1500);

    setTimeout(() => {
      window.location.href = "<?= $redirect ?>";
    }, 2000);
  </script>
</body>
</html>