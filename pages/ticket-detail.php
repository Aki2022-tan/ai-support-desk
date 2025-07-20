<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
include('../includes/client-header.php');

// Restrict access to authenticated users only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

// Display success flash message if available
if (isset($_SESSION['success'])) {
  echo '<div class="bg-green-100 text-green-800 border border-green-300 rounded px-4 py-2 mx-auto mt-4 max-w-2xl shadow">'
     . htmlspecialchars($_SESSION['success']) .
     '</div>';
  unset($_SESSION['success']);
}

$ticket_id = intval($_GET['ticket_id'] ?? 0);
$user_id   = $_SESSION['user_id'];

// Fetch the ticket belonging to the logged-in user
$stmt = $conn->prepare("SELECT * FROM support_tickets WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $ticket_id, ':user_id' => $user_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
  die('<div class="text-center mt-20 text-red-600 font-semibold">Ticket not found or access denied.</div>');
}

// Define status badge styles
$statusColors = [
  'Pending'     => 'bg-yellow-100 text-yellow-800',
  'In Progress' => 'bg-blue-100 text-blue-800',
  'Resolved'    => 'bg-green-100 text-green-800',
  'Closed'      => 'bg-gray-100 text-gray-700',
];
$badge = $statusColors[$ticket['status']] ?? 'bg-gray-100 text-gray-600';
?>

<main class="max-w-2xl mx-auto mt-8 px-4 py-8 bg-white rounded-2xl shadow-lg border border-gray-100 transition-all">
  <h2 class="text-2xl font-semibold text-blue-700 mb-1 tracking-tight"><?= htmlspecialchars($ticket['subject']) ?></h2>
  <p class="text-xs text-gray-500 mb-3">Submitted on <?= date('M d, Y h:i A', strtotime($ticket['created_at'])) ?></p>

  <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $badge ?>">
    <?= htmlspecialchars($ticket['status']) ?>
  </span>

  <div class="mt-5 text-gray-800 text-sm leading-relaxed whitespace-pre-line border-t pt-4">
    <?= nl2br(htmlspecialchars($ticket['message'])) ?>
  </div>

  <?php if (!empty($ticket['ai_summary'])): ?>
    <div class="mt-6 text-sm bg-gray-50 border border-gray-200 rounded-lg p-4">
      <p class="font-semibold text-gray-700 mb-1">AI Summary</p>
      <p class="text-gray-600"><?= htmlspecialchars($ticket['ai_summary']) ?></p>
    </div>
  <?php endif; ?>

  <?php if (!empty($ticket['admin_response'])): ?>
    <div class="mt-6 bg-green-50 border border-green-200 text-green-900 text-sm p-4 rounded-lg shadow-sm">
      <p class="font-semibold mb-1">Admin Response</p>
      <?= nl2br(htmlspecialchars($ticket['admin_response'])) ?>
    </div>
  <?php else: ?>
    <p class="mt-6 text-sm italic text-gray-400">Waiting for admin response...</p>
  <?php endif; ?>

  <?php if (!empty($ticket['language'])): ?>
    <div class="text-xs text-gray-400 mt-4 flex items-center gap-1">
      Language: <span class="text-gray-600"><?= htmlspecialchars($ticket['language']) ?></span> |
      <button class="text-blue-500 underline hover:text-blue-700 transition" onclick="alert('<?= htmlspecialchars($ticket['translated_msg']) ?>')">View Translation</button>
    </div>
  <?php endif; ?>

  <?php if (!empty($ticket['ai_priority'])): ?>
    <p class="text-xs mt-2">
      Priority:
      <span class="font-bold text-<?= $ticket['ai_priority'] === 'High' ? 'red' : ($ticket['ai_priority'] === 'Medium' ? 'yellow' : 'green') ?>-600">
        <?= htmlspecialchars($ticket['ai_priority']) ?>
      </span>
    </p>
  <?php endif; ?>

  <?php
    $stmt = $conn->prepare("SELECT * FROM ticket_attachments WHERE ticket_id = :ticket_id");
    $stmt->execute([':ticket_id' => $ticket_id]);
    $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($attachments):
  ?>
    <div class="mt-6">
      <p class="text-sm font-semibold text-gray-700 mb-2">Attachments:</p>
      <ul class="list-disc list-inside text-blue-600 text-sm space-y-1">
        <?php foreach ($attachments as $file): ?>
          <li>
            <a href="<?= htmlspecialchars($file['file_path']) ?>" target="_blank" class="hover:underline">
              <?= htmlspecialchars($file['file_name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</main>

<footer class="text-center text-sm text-gray-400 py-6 mt-10">
  &copy; <?= date('Y') ?> AI Support Desk. Built with dedication by Rogienald.
</footer>

</body>
</html>