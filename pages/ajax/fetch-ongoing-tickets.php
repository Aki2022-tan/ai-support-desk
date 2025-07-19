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
$search = trim($_GET['search'] ?? ''); // Optional search keyword
$page = max((int)($_GET['page'] ?? 1), 1); // Ensure valid page number
$limit = 5; // Max tickets per page
$offset = ($page - 1) * $limit; // Calculate pagination offset

// Initialize SQL WHERE clause to filter ongoing tickets by user
$where = "WHERE user_id = :user_id AND status NOT IN ('Resolved', 'Closed')";
$params = ['user_id' => $user_id];

// Extend WHERE clause with LIKE filters for subject/status when searching
if ($search !== '') {
  $where .= " AND (subject LIKE :search1 OR status LIKE :search2)";
  $params['search1'] = '%' . $search . '%';
  $params['search2'] = '%' . $search . '%';
}

// Construct final SQL query with ORDER BY and pagination limits
$sql = "SELECT * FROM support_tickets $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

// Bind pagination parameters with explicit integer types
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Bind all dynamic SQL parameters (user ID and optional search terms)
foreach ($params as $key => $val) {
  $stmt->bindValue(':' . ltrim($key, ':'), $val);
}

// Execute the query
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all resulting records

// Define UI-friendly badge classes for known statuses
$statusColors = [
  'Pending' => 'bg-yellow-100 text-yellow-800',
  'In Progress' => 'bg-blue-100 text-blue-800',
];

// If no results found on first page, return a relevant message to user
if (count($rows) === 0 && $page === 1) {
  echo $search !== ''
    ? "<p class='text-sm text-gray-500 mt-6'>🔍 No ongoing tickets found for '<strong>" . htmlspecialchars($search) . "</strong>'.</p>"
    : "<p class='text-sm text-gray-500 mt-6'>📭 You have no ongoing tickets.</p>";
  exit;
}

// Loop through and render each ticket as an HTML card with status badge
foreach ($rows as $row) {
  // Select appropriate CSS badge class or default if not mapped
  $badge = $statusColors[$row['status']] ?? 'bg-gray-100 text-gray-600';

  // Start card block
  echo "
    <a href='ticket-detail.php?ticket_id={$row['id']}' class='block border border-gray-200 mb-4 p-4 rounded-xl hover:shadow-md hover:border-blue-200 transition-all duration-200'>
      <div class='flex justify-between items-start'>
        <div>
          <div class='font-semibold text-blue-700 text-lg'>" . htmlspecialchars($row['subject']) . "</div>
          <div class='text-sm text-gray-500'>" . date('M d, Y h:i A', strtotime($row['created_at'])) . "</div>
        </div>
        <span class='inline-block px-3 py-1 rounded-full text-xs font-semibold {$badge}'>" . htmlspecialchars($row['status']) . "</span>
      </div>";

  // Conditionally display AI-generated summary if available
  if (!empty($row['ai_summary'])) {
    echo "<p class='text-sm mt-2 text-gray-700'>📝 <strong>Summary:</strong> " . htmlspecialchars($row['ai_summary']) . "</p>";
  }

  // Conditionally display admin's response if available
  if (!empty($row['admin_response'])) {
    echo "<div class='bg-green-50 border border-green-200 text-green-800 text-sm p-3 mt-2 rounded-md'>
            <strong>👨‍💼 Admin Response:</strong><br>" . nl2br(htmlspecialchars($row['admin_response'])) . "</div>";
  }

  // Close the card block
  echo "</a>";
}
?>