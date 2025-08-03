<?php
/**
 * Session Handler Module
 * 
 * This file provides centralized session initialization and access control utilities 
 * for all user roles (Admin, Corporate, User) within the AI Support Desk system.
 * It ensures secure session usage, prevents unauthorized access, and promotes
 * modular reuse across protected pages.
 */

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is logged in (for any role).
 * This function can be used for general session protection.
 *
 * @return bool True if logged in, false otherwise.
 */
function isUserLoggedIn(): bool {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']) && isset($_SESSION['status']);
}

/**
 * Enforce session access for a specific role.
 * Redirects to the login page if the session is invalid or role mismatched.
 *
 * @param string $expectedRole The required role to access the page (admin, user, corporate)
 * @param string $redirectPath Optional redirection path (defaults to root login)
 */
function requireRole(string $expectedRole, string $redirectPath = '/../index.php'): void {
    if (!isUserLoggedIn() || $_SESSION['role'] !== $expectedRole || $_SESSION['status'] !== 'active') {
        header("Location: $redirectPath");
        exit;
    }
}

/**
 * Destroy all session data and log the user out.
 * Redirects to the specified logout landing page.
 *
 * @param string $redirectPath Destination after logout (e.g., homepage or login)
 */function logout() {
    session_unset();
    session_destroy();
}

?>