<?php
include('../admin/includes/header.php'); // session_start + admin role check

// ✅ Validate ticket ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<p class='text-red-600 text-center mt-10'>❌ Invalid ticket ID.</p>";
  exit();
}

$ticket_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM support_tickets WHERE id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
  echo "<p class='text-red-600 text-center mt-10'>❌ Ticket not found.</p>";
  exit();
}

// ✅ Handle admin response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_response'])) {
  $response = trim($_POST['admin_response']);
  $updateStmt = $conn->prepare("UPDATE support_tickets SET admin_response = ?, status = 'resolved' WHERE id = ?");
  $updateStmt->execute([$response, $ticket_id]);
  header("Location: manage-tickets.php?replied=1");
  exit();
}
?>

<!-- The rest of the HTML below remains unchanged -->

<section class="max-w-3xl mx-auto mt-10 px-4 py-8 bg-white rounded-lg shadow">
  <h1 class="text-xl sm:text-2xl font-bold text-blue-700 mb-6">✉️ Respond to Ticket #<?= $ticket['id'] ?></h1>

  <div class="mb-6 border-b pb-4 space-y-1">
    <p><strong>User:</strong> <?= htmlspecialchars($ticket['name']) ?> (<?= htmlspecialchars($ticket['email']) ?>)</p>
    <p><strong>Submitted:</strong> <?= date('M d, Y h:i A', strtotime($ticket['created_at'])) ?></p>
    <p><strong>Status:</strong>
      <span class="text-sm px-2 py-1 rounded 
        <?= $ticket['status'] == 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' ?>">
        <?= ucfirst($ticket['status']) ?>
      </span>
    </p>
  </div>

  <div class="mb-4">
    <h2 class="font-semibold mb-1">📝 Subject:</h2>
    <p class="bg-gray-50 p-3 rounded border"> <?= htmlspecialchars($ticket['subject']) ?> </p>
  </div>

  <div class="mb-4">
    <h2 class="font-semibold mb-1">💬 Message:</h2>
    <p class="bg-gray-50 p-3 rounded border whitespace-pre-line"> <?= htmlspecialchars($ticket['message']) ?> </p>
  </div>

  <?php if (!empty($ticket['ai_response'])): ?>
    <div class="mb-4">
      <h2 class="font-semibold mb-1">🤖 AI Suggested Reply:</h2>
      <p class="bg-green-50 border border-green-200 p-3 rounded text-sm italic">
        <?= htmlspecialchars($ticket['ai_response']) ?>
      </p>
    </div>
  <?php endif; ?>

  <!-- ✅ Admin Response Form -->
  <form method="POST" class="space-y-4 mt-6">
    <div>
      <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-1">
        ✍️ Your Response (edit AI reply or write your own)
      </label>
      <textarea name="admin_response" id="admin_response" rows="6" required
        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring focus:ring-blue-200"><?= htmlspecialchars($ticket['admin_response'] ?: $ticket['ai_response']) ?></textarea>
    </div>

    <div class="flex justify-end">
      <button type="submit"
        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 font-semibold text-sm">
        ✅ Submit Response
      </button>
    </div>
  </form>
</section>