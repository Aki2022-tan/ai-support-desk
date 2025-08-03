<?php
require_once '../../../includes/db.php'; // Make sure this file contains your DB connection ($conn)
require_once '../../../includes/session-handler.php';

requireRole('admin');

// Only respond to GET request for now
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT id, full_name, email, role, department, status, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}