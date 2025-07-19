<?php
/**
 * Establishes and returns a secure PDO connection to the MySQL database.
 * This function encapsulates the connection logic to centralize database access management.
 *
 * @return PDO The PDO connection instance configured with error handling and UTF-8 support.
 */
function getPDOConnection() {
    // Database connection parameters
    $host = 'localhost';           // Host where the MySQL server is running
    $db   = 'ai_support_desk';     // The name of the database being accessed
    $user = 'root';                // The username for database access
    $pass = '';                    // Password for the database user (commonly blank in local setups like KSWEB)
    $charset = 'utf8mb4';          // Character set supporting full UTF-8 including emojis and special symbols

    // Construct the Data Source Name (DSN) string required for PDO
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // Define PDO connection options for robust error handling and performance
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements for security
    ];

    // Return a new PDO instance with the configured parameters and options
    return new PDO($dsn, $user, $pass, $options);
}

/**
 * Cohere AI API Key
 * This variable stores the API key used to authenticate with Cohere's NLP platform.
 * Ensure this key is kept confidential and not exposed in public repositories.
 */
$cohere_api_key = '3l6xbkbUwq2wyrbMMMeLPoDpXesAb3WQ5suCPbog';
?>