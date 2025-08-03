<?php
// Enforce session and DB connection
require_once '../../../includes/db.php';

require_once '../../../includes/session-handler.php';

requireRole('admin');

// Handle POST request only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $corporateId = $_POST['corporate_id'] ?? null;
    $department = $_POST['department'] ?? null;

    if (!$corporateId || !$department) {
        // Redirect with error state if required data is missing
        header("Location: ../pending-corporates.php?status=error&message=Missing required information.");
        exit;
    }

    try {
        // Update the corporate user's department and activate their account
        $stmt = $conn->prepare("UPDATE users SET status = 'active', department = :department WHERE id = :id AND role = 'corporate'");
        $stmt->execute([
            ':department' => $department,
            ':id' => $corporateId
        ]);

        // Redirect back with a success toast
        header("Location: ../pending-corporates.php?status=success&message=Corporate+approved+and+assigned+to+$department");
        exit;

    } catch (PDOException $e) {
        // Log error appropriately in production
        header("Location: ../pending-corporates.php?status=error&message=Database+error+occurred.");
        exit;
    }
}

// Invalid request fallback
header("Location: ../pending-corporates.php");
exit;