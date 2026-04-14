<?php
// Start output buffering to ensure clean JSON response
ob_start();

include "../assets/classes/auth_class.php";
require_once '../assets/classes/users_class.php';
require_once '../assets/classes/connect_db_class.php';

$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$user = new User($db);

// Clear any accidental output before JSON
ob_clean();

// Set JSON header
header('Content-Type: application/json');

function json_response(array $payload, int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get the form data
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validate input fields
        if (empty($email) || empty($password)) {
            json_response(['status' => 'error', 'message' => 'Please fill in all fields.']);
        }

        if (! $db) {
            json_response(['status' => 'error', 'message' => 'Database connection failed. Please try again later.'], 500);
        }

        // Attempt to log in
        $loggedInUser = $user->loginByEmail($email, $password);

        if ($loggedInUser) {
            // Successful login
            $auth->login($loggedInUser['user_id'], $loggedInUser['username'], $loggedInUser['role']);
            json_response(['status' => 'success', 'redirect' => 'user-profile']);
        }

        // Login failed
        json_response(['status' => 'error', 'message' => 'Invalid email or password.']);
    } catch (Throwable $e) {
        error_log('Login error: ' . $e->getMessage());
        json_response(['status' => 'error', 'message' => 'Unable to process login right now. Please try again later.'], 500);
    }
} else {
    // Invalid request method
    json_response(['status' => 'error', 'message' => 'Invalid request method.'], 405);
}

// End output buffering and flush
ob_end_flush();
?>