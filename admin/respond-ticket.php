<?php
include('../admin/includes/header.php'); // session_start + admin role check

// ✅ Validate ticket ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<p class='text-red-600 text-center mt-10'>Invalid ticket ID.</p>";
  exit();
}

$ticket_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM support_tickets WHERE id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
  echo "<p class='text-red-600 text-center mt-10'>Ticket not found.</p>";
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Respond to Ticket</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <section class="bg-white rounded-2xl shadow p-6 sm:p-8">
      <h1 class="text-2xl sm:text-3xl font-semibold text-blue-700 mb-6">
        Respond to Ticket #<?= $ticket['id'] ?>
      </h1>

      <div class="space-y-3 border-b pb-5 mb-6">
        <p><span class="font-medium text-gray-700">User:</span> <?= htmlspecialchars($ticket['name']) ?> (<?= htmlspecialchars($ticket['email']) ?>)</p>
        <p><span class="font-medium text-gray-700">Submitted:</span> <?= date('M d, Y h:i A', strtotime($ticket['created_at'])) ?></p>
        <p>
          <span class="font-medium text-gray-700">Status:</span>
          <span class="inline-block text-xs font-medium px-2 py-1 rounded 
            <?= $ticket['status'] === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' ?>">
            <?= ucfirst($ticket['status']) ?>
          </span>
        </p>
      </div>

      <div class="mb-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-1">Subject</h2>
        <div class="bg-gray-50 border border-gray-200 rounded p-3">
          <?= htmlspecialchars($ticket['subject']) ?>
        </div>
      </div>

      <div class="mb-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-1">Message</h2>
        <div class="bg-gray-50 border border-gray-200 rounded p-3 whitespace-pre-line">
          <?= htmlspecialchars($ticket['message']) ?>
        </div>
      </div>

      <?php if (!empty($ticket['ai_response'])): ?>
        <div class="mb-5">
          <h2 class="text-sm font-semibold text-gray-700 mb-1">AI Suggested Reply</h2>
          <div class="bg-green-50 border border-green-200 text-sm italic rounded p-3">
            <?= htmlspecialchars($ticket['ai_response']) ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- ✅ Admin Response Form -->
      <form method="POST" class="space-y-5 mt-8">
        <div>
          <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">
            Your Response
          </label>
          <textarea name="admin_response" id="admin_response" rows="6" required
            class="w-full rounded-lg border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <?= htmlspecialchars($ticket['admin_response'] ?: $ticket['ai_response']) ?>
          </textarea>
        </div>

        <div class="flex justify-end">
          <button type="submit"
            class="inline-flex items-center justify-center bg-blue-600 text-white text-sm font-semibold px-5 py-2 rounded-lg hover:bg-blue-700 transition">
            Submit Response
          </button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>