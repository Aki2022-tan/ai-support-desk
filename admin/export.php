<?php
// Initialize session and ensure only authorized admin users can access export functionality
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

// Load database connection
require_once __DIR__ . '/../includes/db.php';

// Set appropriate headers to indicate a CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="tickets_export.csv"');

// Open the output stream for writing CSV content directly to browser
$output = fopen('php://output', 'w');

// Write CSV column headers
fputcsv($output, ['Ticket ID', 'User', 'Email', 'Subject', 'Status', 'Date']);

// Fetch relevant ticket data using a secure PDO query
$stmt = $conn->query("SELECT id, name, email, subject, status, created_at FROM support_tickets");

// Write each ticket record to the CSV output
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  fputcsv($output, $row);
}

// Finalize output stream and terminate script
fclose($output);
exit;
?>