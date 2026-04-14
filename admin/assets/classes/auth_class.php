<?php
class Auth {
    private $db; // Database connection (optional, if you need to query the database)

    public function __construct($db = null) {
        // Start session only if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = $db; // Optional: Pass a database connection
    }

    /**
     * Check if the user is logged in.
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    /**
     * Redirect the user to a specific page.
     */
    public function redirect($url) {
        echo '<script>window.location.href = "' . $url . '";</script>';
        exit();
    }

    /**
     * Log in the user.
     */
    public function login($user_id, $username, $role = '') {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
    }

    /**
     * Log out the user.
     */
    public function logout() {
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        $this->redirect('login.php'); // Redirect to the login page
    }

    /**
     * Protect a page by requiring login.
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login.php');
        }
    }

    /**
     * Protect a page by requiring a specific role.
     */
    public function requireRole($role) {
        $this->requireLogin(); // Ensure the user is logged in
        if (!$this->hasRole($role)) {
            $this->redirect('unauthorized.php');
        }
    }
}
?>