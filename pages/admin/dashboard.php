<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('admin');
?>

<section class="min-h-screen bg-gray-50 px-4 py-8">
  <div class="max-w-7xl mx-auto">

    <!-- Dashboard Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
      <p class="text-gray-500">Overview of system activity and departments</p>
    </div>

    <?php
    // ⚠️ Replace with real queries
    $totalTickets = 1432;
    $resolvedTickets = 1345;
    $percentageResolved = $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100) : 0;
    ?>

    <!-- Total Tickets Summary -->
    <div class="bg-white p-6 mb-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-center">
      <h2 class="text-xl font-semibold text-gray-800">🎟️ Ticket Overview</h2>
      <p class="mt-2 sm:mt-0 text-lg text-gray-700">
        Total: <span class="font-bold"><?= $totalTickets ?></span> tickets |
        <span class="text-green-600 font-semibold"><?= $resolvedTickets ?> resolved</span> 
        <span class="text-sm text-gray-500">(<?= $percentageResolved ?>%)</span>
      </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-sm text-gray-500 uppercase">Total Tickets</h2>
        <p class="text-2xl font-semibold text-blue-600 mt-2"><?= $totalTickets ?></p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-sm text-gray-500 uppercase">Active Users</h2>
        <p class="text-2xl font-semibold text-green-600 mt-2">219</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-sm text-gray-500 uppercase">Pending Tickets</h2>
        <p class="text-2xl font-semibold text-red-600 mt-2"><?= $totalTickets - $resolvedTickets ?></p>
      </div>
    </div>

    <!-- Department Overview -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Tickets by Department</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php
        $departments = [
          "Customer Service",
          "Technical Support",
          "Billing & Payments",
          "Sales & Inquiries",
          "Account Management",
          "Product Support",
          "Compliance & Legal"
        ];

        foreach ($departments as $dept):
          $total = rand(40, 150); // Replace with actual count query
          $resolved = rand(0, $total); // Replace with actual resolved query
          $percentage = $total > 0 ? round(($resolved / $total) * 100) : 0;
          $color = $percentage >= 80 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600');
        ?>
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition">
            <h3 class="text-sm font-medium text-gray-600"><?= $dept ?></h3>
            <p class="text-base font-semibold text-gray-900 mt-2">
              Total: <?= $total ?> |
              <span class="text-green-600"><?= $resolved ?> resolved</span>
              <span class="text-sm <?= $color ?>">(<?= $percentage ?>%)</span>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Ticket Activity Trend -->
    <div class="bg-white p-6 mt-10 rounded-2xl shadow-sm border border-gray-100">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">📈 Ticket Activity (Last 7 Days)</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-4 text-center">
        <?php
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($days as $day):
          $activity = rand(10, 50); // Replace with real DB counts
        ?>
          <div class="bg-blue-50 rounded-lg p-3">
            <p class="text-sm text-gray-500"><?= $day ?></p>
            <p class="text-xl font-bold text-blue-700"><?= $activity ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Admin Actions -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
      <a href="/ai-support-desk/pages/admin/ticket-summary.php" class="block bg-indigo-600 hover:bg-indigo-700 text-white text-center py-4 rounded-xl shadow-md font-semibold">
        View Ticket Summary
      </a>
      <a href="/ai-support-desk/pages/admin/manage-users.php" class="block bg-gray-800 hover:bg-gray-900 text-white text-center py-4 rounded-xl shadow-md font-semibold">
        Manage User Roles
      </a>
    </div>

  </div>
</section>