<?php
// Start or resume the session to access user authentication data
session_start();

// Include the database connection using PDO
require_once __DIR__ . '/../../includes/db.php';

// Validate user authentication; restrict access to unauthenticated users
if (!isset($_SESSION['user_id'])) {
  http_response_code(403); // Return HTTP 403 Forbidden
  exit('Unauthorized');    // Terminate execution with error message
}

// Retrieve authenticated user's ID from session
$user_id = $_SESSION['user_id'];

// Extract query parameters for filtering and pagination
$search = trim($_GET['search'] ?? '');         // Optional search keyword
$page = max((int)($_GET['page'] ?? 1), 1);     // Ensure valid page number
$limit = 5;                                    // Max tickets per page
$offset = ($page - 1) * $limit;                // Calculate pagination offset

// Initialize SQL WHERE clause to filter ongoing tickets by user
$where = "WHERE user_id = :user_id AND status NOT IN ('Resolved', 'Closed')";
$params = ['user_id' => $user_id];

// Extend WHERE clause with LIKE filters for subject/status if searching
if ($search !== '') {
  $where .= " AND (subject LIKE :search1 OR status LIKE :search2)";
  $params['search1'] = '%' . $search . '%';
  $params['search2'] = '%' . $search . '%';
}

// Construct final SQL query with ORDER BY and pagination
$sql = "SELECT * FROM support_tickets $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

// Bind pagination parameters with explicit integer types
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Bind all dynamic SQL parameters (user ID, search terms)
foreach ($params as $key => $val) {
  $stmt->bindValue(':' . ltrim($key, ':'), $val);
}

// Execute the query
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all matching ticket records

// Define CSS badge classes for known ticket statuses
$statusColors = [
  'Pending' => 'bg-yellow-100 text-yellow-800',
  'In Progress' => 'bg-blue-100 text-blue-800',
];

// Display message if no tickets are found (on page 1 only)
if (count($rows) === 0 && $page === 1) {
  echo $search !== ''
    ? "<p class='text-sm text-gray-500 mt-6 italic'>No ongoing tickets found for '<strong>" . htmlspecialchars($search) . "</strong>'.</p>"
    : "<p class='text-sm text-gray-500 mt-6 italic'>You currently have no ongoing tickets.</p>";
  exit;
}

// Loop through and render each ticket
foreach ($rows as $row) {
  // Extract data and escape output
  $ticketId = $row['id'];
  $subject = htmlspecialchars($row['subject']);
  $createdAt = date('M d, Y h:i A', strtotime($row['created_at']));
  $status = htmlspecialchars($row['status']);
  $summary = htmlspecialchars($row['ai_summary'] ?? '');
  $adminResponse = htmlspecialchars($row['admin_response'] ?? '');

  // Determine badge class based on ticket status
  $badge = $statusColors[$status] ?? 'bg-gray-100 text-gray-600';

  // Render ticket card block
  echo "
    <a href='ticket-detail.php?ticket_id={$ticketId}' class='block mb-4 p-5 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200'>

      <!-- Ticket subject and status -->
      <div class='flex justify-between items-start'>
        <div>
          <h3 class='text-base font-semibold text-blue-700 mb-1'>{$subject}</h3>
          <span class='text-xs text-gray-500'>{$createdAt}</span>
        </div>
        <span class='px-3 py-1 text-xs font-medium rounded-full {$badge}'>{$status}</span>
      </div>";

  // Conditionally render AI-generated summary
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

  // Close card container
  echo "</a>";
}
?>