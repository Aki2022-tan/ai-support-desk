<?php
include('../admin/includes/header.php'); // Includes session_start + admin role check

// Handle ticket status update securely
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $new_status = trim($_POST['status']);

    if (!empty($ticket_id) && !empty($new_status)) {
        $stmt = $conn->prepare("UPDATE support_tickets SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $new_status);
        $stmt->bindParam(':id', $ticket_id);
        $stmt->execute();
    }
}

$search = $_GET['search'] ?? '';
$filter_status = $_GET['status'] ?? '';
$where = "1=1"; 
$params = [];

if (!empty($search)) {
    $where .= " AND (subject LIKE :s1 OR email LIKE :s2 OR message LIKE :s3)";
    $params['s1'] = '%' . $search . '%'; 
    $params['s2'] = '%' . $search . '%'; 
    $params['s3'] = '%' . $search . '%'; 
}

if (!empty($filter_status)) {
    $where .= " AND status = :status";
    $params['status'] = $filter_status; 
}

$sql = "SELECT * FROM support_tickets WHERE $where ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="px-4 sm:px-6 py-6 max-w-7xl mx-auto space-y-6">
  <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Manage Support Tickets</h1>

  <form method="GET" class="space-y-3 sm:space-y-0 sm:flex sm:items-end sm:gap-4">
    <div class="w-full sm:w-1/3">
      <label class="block mb-1 text-sm font-medium text-gray-700">Search</label>
      <input type="text" name="search" placeholder="Subject, Email or Message"
        value="<?= htmlspecialchars($search) ?>"
        class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
    </div>
    <div class="w-full sm:w-1/4">
      <label class="block mb-1 text-sm font-medium text-gray-700">Status</label>
      <select name="status"
        class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All Status</option>
        <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="resolved" <?= $filter_status == 'resolved' ? 'selected' : '' ?>>Resolved</option>
      </select>
    </div>
    <div class="w-full sm:w-auto">
      <label class="block mb-1 text-sm opacity-0">Filter</label>
      <button type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
        Filter
      </button>
    </div>
  </form>

  <div class="hidden sm:block overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
    <table class="min-w-full text-sm text-left whitespace-nowrap">
      <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
        <tr>
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3">User</th>
          <th class="px-4 py-3">Subject</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Actions</th>
          <th class="px-4 py-3">Created</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-100">
        <?php if (count($tickets) === 0): ?>
          <tr><td colspan="6" class="text-center py-6 text-gray-500">No tickets found.</td></tr>
        <?php endif; ?>
        <?php foreach ($tickets as $row): ?>
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3"><?= $row['id'] ?></td>
            <td class="px-4 py-3">
              <div class="font-medium text-gray-800"><?= htmlspecialchars($row['name']) ?></div>
              <div class="text-xs text-gray-500"><?= htmlspecialchars($row['email']) ?></div>
            </td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['subject']) ?></td>
            <td class="px-4 py-3">
              <span class="inline-block px-2 py-1 text-xs rounded-full font-semibold
                <?= $row['status'] === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex gap-2 items-center">
                <form method="POST" class="flex gap-2 items-center">
                  <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
                  <select name="status" class="border rounded px-2 py-1 text-xs">
                    <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="resolved" <?= $row['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                  </select>
                  <button name="update_status" class="text-blue-600 hover:underline text-xs font-medium">Update</button>
                </form>
                <a href="respond-ticket.php?id=<?= $row['id'] ?>"
                  class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1 px-3 rounded-md shadow">
                  Reply
                </a>
              </div>
            </td>
            <td class="px-4 py-3 text-xs text-gray-500">
              <?= date('M d, Y H:i', strtotime($row['created_at'])) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="sm:hidden space-y-4">
    <?php if (count($tickets) === 0): ?>
      <div class="text-center text-gray-500">No tickets found.</div>
    <?php endif; ?>
    <?php foreach ($tickets as $row): ?>
      <div class="bg-white border rounded-xl p-4 shadow-sm">
        <div class="text-sm text-gray-600 mb-2">
          <strong>ID #<?= $row['id'] ?></strong> — <?= date('M d, Y H:i', strtotime($row['created_at'])) ?>
        </div>
        <div class="mb-2">
          <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($row['name']) ?></p>
          <p class="text-xs text-gray-500"><?= htmlspecialchars($row['email']) ?></p>
        </div>
        <p class="text-sm mb-2 font-semibold text-gray-700"><?= htmlspecialchars($row['subject']) ?></p>
        <p class="mb-2">
          <span class="inline-block px-2 py-1 text-xs rounded-full font-semibold
            <?= $row['status'] === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' ?>">
            <?= ucfirst($row['status']) ?>
          </span>
        </p>
        <div class="flex flex-col gap-2 mt-2">
          <form method="POST" class="flex gap-2 items-center">
            <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
            <select name="status" class="border rounded px-2 py-1 text-xs flex-1">
              <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="resolved" <?= $row['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
            </select>
            <button name="update_status"
              class="text-blue-600 hover:underline text-xs font-medium">Update</button>
          </form>
          <a href="respond-ticket.php?id=<?= $row['id'] ?>"
            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1 px-3 rounded-md shadow text-center">
            Reply
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
