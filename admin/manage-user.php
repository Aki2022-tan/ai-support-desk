<?php
session_start();
// ✅ Block if not logged in or not admin
include('../admin/includes/header.php'); // session_start + admin role check
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
  <h2 class="text-2xl font-bold text-blue-600 mb-4">👥 All Users</h2>
  <a href="dashboard.php" class="text-sm text-blue-500 underline">← Back to Dashboard</a>
  <table class="min-w-full mt-4 border border-gray-300 text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="border p-2">ID</th>
        <th class="border p-2">Name</th>
        <th class="border p-2">Email</th>
        <th class="border p-2">Role</th>
        <th class="border p-2">Registered</th>
      </tr>
    </thead>
    <tbody>
      <?php
        try {
          $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
          while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
      ?>
      <tr>
        <td class="border px-2 py-1"><?= htmlspecialchars($user['id']) ?></td>
        <td class="border px-2 py-1"><?= htmlspecialchars($user['name']) ?></td>
        <td class="border px-2 py-1"><?= htmlspecialchars($user['email']) ?></td>
        <td class="border px-2 py-1"><?= htmlspecialchars($user['role']) ?></td>
        <td class="border px-2 py-1"><?= htmlspecialchars($user['created_at']) ?></td>
      </tr>
      <?php
          endwhile;
        } catch (PDOException $e) {
          echo '<tr><td colspan="5" class="text-red-500 p-2">Error fetching users: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
        }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>