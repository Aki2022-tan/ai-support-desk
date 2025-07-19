<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

require_once __DIR__ . '/../includes/db.php';


// Headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="tickets_export.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Ticket ID', 'User', 'Email', 'Subject', 'Status', 'Date']);

// PDO Data fetch
$stmt = $conn->query("SELECT id, name, email, subject, status, created_at FROM support_tickets");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  fputcsv($output, $row);
}

fclose($output);
exit;