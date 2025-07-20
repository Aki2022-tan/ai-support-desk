<?php
session_start();
// ✅ Block if not logged in or not admin
include('../admin/includes/header.php'); // session_start + admin role check
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl sm:text-3xl font-semibold text-blue-700 tracking-tight">User Management</h1>
        <p class="text-sm text-gray-500">Overview of all registered users</p>
      </div>
      <a href="dashboard.php" class="text-sm text-blue-600 hover:text-blue-800 underline">← Back to Dashboard</a>
    </div>

    <!-- User Table Container -->
    <div class="bg-white rounded-2xl shadow-md overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wide hidden sm:table-header-group">
          <tr>
            <th class="text-left px-4 py-3 border-b border-gray-200">ID</th>
            <th class="text-left px-4 py-3 border-b border-gray-200">Name</th>
            <th class="text-left px-4 py-3 border-b border-gray-200">Email</th>
            <th class="text-left px-4 py-3 border-b border-gray-200">Role</th>
            <th class="text-left px-4 py-3 border-b border-gray-200">Registered</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php
            try {
              $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
              while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
          ?>
          <!-- Desktop Table Row -->
          <tr class="hidden sm:table-row hover:bg-gray-50 transition">
            <td class="px-4 py-3"><?= htmlspecialchars($user['id']) ?></td>
            <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($user['name']) ?></td>
            <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($user['email']) ?></td>
            <td class="px-4 py-3">
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                <?= $user['role'] === 'admin' 
                  ? 'bg-blue-100 text-blue-700' 
                  : 'bg-gray-100 text-gray-700' ?>">
                <?= htmlspecialchars($user['role']) ?>
              </span>
            </td>
            <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars(date("M d, Y", strtotime($user['created_at']))) ?></td>
          </tr>

          <!-- Mobile Card View -->
          <tr class="sm:hidden border-b border-gray-200">
            <td colspan="5" class="p-4">
              <div class="bg-white rounded-xl p-4 shadow-sm space-y-1 text-sm">
                <div><span class="font-semibold text-gray-700">ID:</span> <?= htmlspecialchars($user['id']) ?></div>
                <div><span class="font-semibold text-gray-700">Name:</span> <?= htmlspecialchars($user['name']) ?></div>
                <div><span class="font-semibold text-gray-700">Email:</span> <?= htmlspecialchars($user['email']) ?></div>
                <div>
                  <span class="font-semibold text-gray-700">Role:</span>
                  <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded 
                    <?= $user['role'] === 'admin' 
                      ? 'bg-blue-100 text-blue-700' 
                      : 'bg-gray-100 text-gray-700' ?>">
                    <?= htmlspecialchars($user['role']) ?>
                  </span>
                </div>
                <div><span class="font-semibold text-gray-700">Registered:</span> <?= htmlspecialchars(date("M d, Y", strtotime($user['created_at']))) ?></div>
              </div>
            </td>
          </tr>
          <?php endwhile;
            } catch (PDOException $e) {
              echo '<tr><td colspan="5" class="text-red-500 px-4 py-3">Error fetching users: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>