<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('user');

// Simulated data — replace with actual DB query results
$tickets = [
  [
    'id' => 1041,
    'subject' => 'Can’t access HR portal',
    'status' => 'Open',
    'department' => 'HR Department',
    'submitted' => 'July 28, 2025',
  ],
  [
    'id' => 1038,
    'subject' => 'Email not syncing',
    'status' => 'Awaiting Staff',
    'department' => 'IT Department',
    'submitted' => 'July 27, 2025',
  ],
  [
    'id' => 1022,
    'subject' => 'Printer issue resolved',
    'status' => 'Resolved',
    'department' => 'IT Department',
    'submitted' => 'July 25, 2025',
  ],
];
?>

<div class="p-6">
  <!-- Header -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">My Tickets</h1>
      <p class="text-sm text-gray-600 mt-1">Search and filter your submitted tickets.</p>
    </div>

    <!-- Filters and Search -->
    <div class="flex flex-wrap gap-2 items-center">
      <input id="ticketSearch" type="text" placeholder="Search by subject..."
        class="border border-gray-300 rounded-md px-3 py-2 text-sm w-48 focus:ring focus:outline-none" />

      <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring focus:outline-none">
        <option value="">All Statuses</option>
        <option value="Open">Open</option>
        <option value="Awaiting Staff">Awaiting Staff</option>
        <option value="Resolved">Resolved</option>
      </select>

      <select id="deptFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring focus:outline-none">
        <option value="">All Departments</option>
        <option value="HR Department">HR Department</option>
        <option value="IT Department">IT Department</option>
      </select>
    </div>
  </div>

  <!-- Tickets Grid -->
  <?php if (empty($tickets)): ?>
    <div class="text-center text-gray-600 bg-white border rounded-xl p-6 shadow-sm">
      <p class="text-lg font-semibold">No tickets found.</p>
      <p class="text-sm mt-2">Need help? <a href="submit-ticket.php" class="text-blue-600 hover:underline">Submit one now</a>.</p>
    </div>
  <?php else: ?>
    <div id="ticketGrid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($tickets as $ticket): ?>
        <a href="ticket-detail.php?id=<?= $ticket['id'] ?>" class="block group ticket-card"
           data-status="<?= $ticket['status'] ?>"
           data-department="<?= $ticket['department'] ?>"
           data-subject="<?= strtolower($ticket['subject']) ?>">
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200 h-full">
            <div class="flex justify-between items-start mb-1">
              <h2 class="font-semibold text-gray-800 text-sm group-hover:underline">
                [#<?= htmlspecialchars($ticket['id']) ?>] <?= htmlspecialchars($ticket['subject']) ?>
              </h2>
              <?php
                $statusClass = match ($ticket['status']) {
                  'Open' => 'bg-yellow-50 text-yellow-700',
                  'Awaiting Staff' => 'bg-blue-50 text-blue-700',
                  'Resolved' => 'bg-green-50 text-green-700',
                  default => 'bg-gray-100 text-gray-700'
                };
              ?>
              <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $statusClass ?>">
                <?= htmlspecialchars($ticket['status']) ?>
              </span>
            </div>

            <div class="text-xs text-gray-500 mt-2 mb-4 space-y-1">
              <p><strong>Department:</strong> <?= htmlspecialchars($ticket['department']) ?></p>
              <p><strong><?= $ticket['status'] === 'Resolved' ? 'Resolved' : 'Submitted' ?> On:</strong> <?= htmlspecialchars($ticket['submitted']) ?></p>
            </div>

            <div class="flex justify-between items-center">
              <span class="inline-block bg-blue-600 text-white text-xs px-4 py-1.5 rounded-md font-medium hover:bg-blue-700 transition">
                View Conversation
              </span>
              <?php if ($ticket['status'] === 'Resolved'): ?>
                <form action="../../handlers/reopen-ticket.php" method="POST" onClick="event.stopPropagation();">
                  <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                  <button type="submit"
                    class="text-xs text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded transition">
                    Reopen
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
  const searchInput = document.getElementById('ticketSearch');
  const statusFilter = document.getElementById('statusFilter');
  const deptFilter = document.getElementById('deptFilter');
  const cards = document.querySelectorAll('.ticket-card');

  function filterTickets() {
    const keyword = searchInput.value.toLowerCase();
    const status = statusFilter.value;
    const dept = deptFilter.value;

    cards.forEach(card => {
      const matchSubject = card.dataset.subject.includes(keyword);
      const matchStatus = !status || card.dataset.status === status;
      const matchDept = !dept || card.dataset.department === dept;

      card.style.display = (matchSubject && matchStatus && matchDept) ? 'block' : 'none';
    });
  }

  searchInput.addEventListener('input', filterTickets);
  statusFilter.addEventListener('change', filterTickets);
  deptFilter.addEventListener('change', filterTickets);
</script>