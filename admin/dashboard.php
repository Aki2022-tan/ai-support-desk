<?php
include('../admin/includes/header.php');

// Fetch dashboard stats
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalTickets = $conn->query("SELECT COUNT(*) FROM support_tickets")->fetchColumn();
$pendingTickets = $conn->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'pending'")->fetchColumn();
$resolvedTickets = $conn->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'resolved'")->fetchColumn();
?>

<main class="flex-grow p-4 sm:p-6 bg-gray-50">
  <!-- Overview Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col gap-1 text-center">
      <span class="text-sm text-gray-500">Total Users</span>
      <span class="text-2xl font-semibold text-blue-600"><?= $totalUsers ?></span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col gap-1 text-center">
      <span class="text-sm text-gray-500">Total Tickets</span>
      <span class="text-2xl font-semibold text-yellow-500"><?= $totalTickets ?></span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col gap-1 text-center">
      <span class="text-sm text-gray-500">Pending Tickets</span>
      <span class="text-2xl font-semibold text-red-500"><?= $pendingTickets ?></span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col gap-1 text-center">
      <span class="text-sm text-gray-500">Resolved Tickets</span>
      <span class="text-2xl font-semibold text-green-500"><?= $resolvedTickets ?></span>
    </div>
  </section>

  <!-- Chart Visualization -->
  <section class="bg-white rounded-2xl shadow-sm p-6 mb-8">
    <h2 class="text-lg font-medium text-gray-700 mb-4">Ticket Distribution</h2>
    <canvas id="ticketChart" height="120"></canvas>
  </section>

  <!-- Management Shortcuts -->
  <section class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <a href="manage-user.php" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-4 rounded-xl font-medium transition-shadow shadow-sm">
      Manage Users
    </a>
    <a href="manage-tickets.php" class="bg-green-600 hover:bg-green-700 text-white text-center py-4 rounded-xl font-medium transition-shadow shadow-sm">
      Manage Tickets
    </a>
  </section>

  <!-- Export Action -->
  <div class="text-right">
    <a href="export.php" class="inline-block bg-gray-800 hover:bg-black text-white px-5 py-2 text-sm rounded-xl transition">
      Export Tickets as CSV
    </a>
  </div>
</main>

<!-- Footer -->
<footer class="text-center text-sm text-gray-400 py-6">
  &copy; <?= date('Y') ?> AI Support Desk — Admin Panel
</footer>

<!-- Chart.js -->
<script>
  const ctx = document.getElementById('ticketChart').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Pending', 'Resolved'],
      datasets: [{
        data: [<?= $pendingTickets ?>, <?= $resolvedTickets ?>],
        backgroundColor: ['#f87171', '#34d399'],
        borderWidth: 1
      }]
    },
    options: {
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            color: '#4B5563',
            font: {
              size: 12,
              weight: '500'
            }
          }
        }
      }
    }
  });
</script>
</body>
</html>