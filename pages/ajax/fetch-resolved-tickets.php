<?php
// Start the session to access user-specific session variables
session_start();

// Load the database connection handler
require_once __DIR__ . '/../../includes/db.php';

// Security: Deny access if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
  http_response_code(403); // Return HTTP 403 Forbidden
  exit('Unauthorized');    // Halt further script execution
}

// Retrieve the authenticated user ID from session
$user_id = $_SESSION['user_id'];

// Capture optional search and pagination parameters from the request
$search = trim($_GET['search'] ?? '');               // Keyword to filter by subject or status
$page = max((int)($_GET['page'] ?? 1), 1);            // Current pagination page (minimum is 1)
$limit = 5;                                           // Number of records to fetch per page
$offset = ($page - 1) * $limit;                       // Compute offset for SQL LIMIT clause

// Define base SQL WHERE clause for resolved or closed tickets owned by the user
$where = "WHERE user_id = :user_id AND status IN ('Resolved', 'Closed')";
$params = ['user_id' => $user_id]; // Parameter array for prepared statement

// Enhance WHERE clause to include search filtering on subject and status fields
if ($search !== '') {
  $where .= " AND (subject LIKE :search1 OR status LIKE :search2)";
  $params['search1'] = '%' . $search . '%'; // Bind wildcard pattern for subject
  $params['search2'] = '%' . $search . '%'; // Bind wildcard pattern for status
}

// Final SQL query with ordering and pagination applied
$sql = "SELECT * FROM support_tickets $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

// Bind pagination limits as integers to ensure proper query execution
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Bind dynamic filtering parameters using the prepared statement
foreach ($params as $key => $val) {
  $stmt->bindValue(':' . ltrim($key, ':'), $val);
}

// Execute the SQL statement
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch resulting records as associative arrays

// Define corresponding UI badge color classes for resolved and closed ticket statuses
$statusColors = [
  'Resolved' => 'bg-green-100 text-green-800',
  'Closed' => 'bg-gray-100 text-gray-700',
];

// Handle scenario when there are no matching tickets on the first page
if (empty($tickets) && $page === 1) {
  echo $search !== ''
    ? "<p class='text-sm text-gray-500 mt-6'>🔍 No resolved tickets found for '<strong>" . htmlspecialchars($search) . "</strong>'.</p>"
    : "<p class='text-sm text-gray-500 mt-6'>📭 You have no resolved tickets yet.</p>";
  exit;
}

// Iterate over all resolved/closed tickets and render them as clickable card blocks
foreach ($tickets as $row) {
  // Assign badge class based on the ticket's status or fallback to default styling
  $badge = $statusColors[$row['status']] ?? 'bg-gray-100 text-gray-600';

  // Render ticket item block with title, timestamp, and status badge
  echo "
    <a 
      href='ticket-detail.php?ticket_id={$row['id']}'
      onclick=\"localStorage.setItem('scroll-position', window.scrollY); localStorage.setItem('active-tab', 'resolved');\"
      class='block border border-gray-200 mb-4 p-4 rounded-xl hover:shadow-md hover:border-blue-200 transition-all duration-200'>

      <div class='flex justify-between items-start'>
        <div>
          <div class='font-semibold text-blue-700 text-lg'>" . htmlspecialchars($row['subject']) . "</div>
          <div class='text-sm text-gray-500'>" . date('M d, Y h:i A', strtotime($row['created_at'])) . "</div>
        </div>
        <span class='inline-block px-3 py-1 rounded-full text-xs font-semibold {$badge}'>" . htmlspecialchars($row['status']) . "</span>
      </div>";

  // Display AI-generated ticket summary if present
  if (!empty($row['ai_summary'])) {
    echo "<p class='text-sm mt-2 text-gray-700'>📝 <strong>Summary:</strong> " . htmlspecialchars($row['ai_summary']) . "</p>";
  }

  // Display administrator’s response if it exists
  if (!empty($row['admin_response'])) {
    echo "<div class='bg-green-50 border border-green-200 text-green-800 text-sm p-3 mt-2 rounded-md'>
            <strong>👨‍💼 Admin Response:</strong><br>" . nl2br(htmlspecialchars($row['admin_response'])) . "</div>";
  }

  // Close the outer card anchor tag
  echo "</a>";
}
?>