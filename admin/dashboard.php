<?php
include('../admin/includes/header.php');

// 📊 Stats using PDO
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalTickets = $conn->query("SELECT COUNT(*) FROM support_tickets")->fetchColumn();
$pendingTickets = $conn->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'pending'")->fetchColumn();
$resolvedTickets = $conn->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'resolved'")->fetchColumn();
?>

<!-- 📦 Main Content -->
<main class="flex-grow p-4 sm:p-6">
  <!-- 🔹 Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-gray-500 text-sm">👥 Total Users</p>
      <h2 class="text-2xl font-bold text-blue-700"><?= $totalUsers ?></h2>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-gray-500 text-sm">🎫 Total Tickets</p>
      <h2 class="text-2xl font-bold text-yellow-600"><?= $totalTickets ?></h2>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-gray-500 text-sm">⏳ Pending</p>
      <h2 class="text-2xl font-bold text-red-600"><?= $pendingTickets ?></h2>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-gray-500 text-sm">✅ Resolved</p>
      <h2 class="text-2xl font-bold text-green-600"><?= $resolvedTickets ?></h2>
    </div>
  </div>

  <!-- 📈 Chart -->
  <div class="bg-white p-6 mb-6 rounded-xl shadow">
    <h3 class="text-lg font-bold text-gray-700 mb-2">📊 Tickets by Status</h3>
    <canvas id="ticketChart" height="120"></canvas>
  </div>

  <!-- 🔗 Quick Links -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <a href="manage-user.php" class="block bg-blue-600 hover:bg-blue-700 text-white text-center p-4 rounded-xl font-medium transition">
      👥 Manage Users
    </a>
    <a href="manage-tickets.php" class="block bg-green-600 hover:bg-green-700 text-white text-center p-4 rounded-xl font-medium transition">
      🛠️ Manage Tickets
    </a>
  </div>

  <!-- 📤 Export -->
  <div class="text-right">
    <a href="export.php" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-black text-sm">
      ⬇️ Export Tickets as CSV
    </a>
  </div>
</main>

<!-- 🔻 Footer -->
<footer class="text-center text-sm text-gray-400 py-6">
  &copy; <?= date('Y') ?> AI Support Desk — Admin Panel
</footer>

<!-- 📊 Chart Script -->
<script>
  const ctx = document.getElementById('ticketChart').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Pending', 'Resolved'],
      datasets: [{
        label: 'Tickets',
        data: [<?= $pendingTickets ?>, <?= $resolvedTickets ?>],
        backgroundColor: ['#f87171', '#34d399']
      }]
    }
  });
</script>
</body>
</html>