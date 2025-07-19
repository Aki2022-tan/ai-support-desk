<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

require_once __DIR__ . '/../../includes/db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" href="../../assets/images/favicon.png"> <!-- optional -->
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  
  <!-- 🔵 Sticky Header -->
  <header class="bg-white sticky top-0 z-10 shadow-md px-6 py-4 flex items-center justify-between">
    <h1 class="text-lg sm:text-2xl font-bold text-blue-700">📊 Admin Dashboard</h1>
    <div class="text-sm text-gray-600">
      Welcome, <strong><?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin' ?></strong> |
      <a href="../logout.php" class="text-red-600 hover:underline">Logout</a>
    </div>
  </header>