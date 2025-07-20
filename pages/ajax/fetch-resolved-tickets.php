<?php
// Initialize the session to access user credentials
session_start();

// Establish database connectivity
require_once __DIR__ . '/../../includes/db.php';

// Restrict access to authenticated users only
if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  exit('Unauthorized');
}

// Retrieve the current user's ID from the session
$user_id = $_SESSION['user_id'];

// Extract optional query parameters
$search = trim($_GET['search'] ?? '');
$page = max((int)($_GET['page'] ?? 1), 1);
$limit = 5;
$offset = ($page - 1) * $limit;

// SQL base query to fetch only resolved or closed tickets
$where = "WHERE user_id = :user_id AND status IN ('Resolved', 'Closed')";
$params = ['user_id' => $user_id];

// Append search filter conditions if applicable
if ($search !== '') {
  $where .= " AND (subject LIKE :search1 OR status LIKE :search2)";
  $params['search1'] = '%' . $search . '%';
  $params['search2'] = '%' . $search . '%';
}

// Final SQL query with ordering and pagination
$sql = "SELECT * FROM support_tickets $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

// Bind pagination values
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Bind dynamic parameters securely
foreach ($params as $key => $val) {
  $stmt->bindValue(':' . ltrim($key, ':'), $val);
}

// Execute query and fetch matching records
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define badge color codes for status display
$statusColors = [
  'Resolved' => 'bg-green-100 text-green-800',
  'Closed' => 'bg-gray-100 text-gray-700',
];

// Display message if no tickets are found
if (empty($tickets) && $page === 1) {
  echo $search !== ''
    ? "<p class='text-sm text-gray-500 mt-6 italic'>No resolved tickets found for '<strong>" . htmlspecialchars($search) . "</strong>'.</p>"
    : "<p class='text-sm text-gray-500 mt-6 italic'>You have no resolved tickets yet.</p>";
  exit;
}

// Loop through resolved tickets and render them
foreach ($tickets as $ticket) {
  $ticketId = $ticket['id'];
  $subject = htmlspecialchars($ticket['subject']);
  $createdAt = date('M d, Y h:i A', strtotime($ticket['created_at']));
  $status = htmlspecialchars($ticket['status']);
  $summary = htmlspecialchars($ticket['ai_summary'] ?? '');
  $adminResponse = htmlspecialchars($ticket['admin_response'] ?? '');
  $badgeClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-600';

  echo "
    <a 
      href='ticket-detail.php?ticket_id={$ticketId}'
      onclick=\"localStorage.setItem('scroll-position', window.scrollY); localStorage.setItem('active-tab', 'resolved');\"
      class='block border border-gray-200 mb-4 p-5 bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-200'>

      <!-- Ticket subject and timestamp -->
      <div class='flex justify-between items-start'>
        <div>
          <h3 class='text-base font-semibold text-blue-700 mb-1'>{$subject}</h3>
          <span class='text-xs text-gray-500'>{$createdAt}</span>
        </div>
        <span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>{$status}</span>
      </div>";

  // Conditionally render summary
  if (!empty($summary)) {
    echo "
      <div class='mt-3 text-sm text-gray-700'>
        <span class='font-semibold text-gray-800 block mb-1'>Summary:</span>
        {$summary}
      </div>";
  }

  // Conditionally render admin response
  if (!empty($adminResponse)) {
    echo "
      <div class='mt-3 text-sm bg-green-50 border border-green-200 text-green-800 p-3 rounded-md'>
        <span class='font-semibold block mb-1'>Admin Response:</span>
        " . nl2br($adminResponse) . "
      </div>";
  }

  echo "</a>";
}
?>