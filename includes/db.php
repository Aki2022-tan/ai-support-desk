<?php
// includes/db.php

// Load database configuration and connection function from centralized config file
require_once __DIR__ . '/config.php';

try {
    // Attempt to establish a secure PDO connection using the configuration settings
    // This abstraction allows consistent and reusable database access throughout the application
    $conn = getPDOConnection(); // Defined in config.php

} catch (PDOException $e) {
    /**
     * If the connection fails, gracefully terminate the script and display the error.
     * In production environments, consider logging the error instead of revealing it to users.
     */
    die("Database connection failed: " . $e->getMessage());
}