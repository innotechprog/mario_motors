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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate input fields
    if (empty($email) || empty($password)) {
        // Return error if fields are empty
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
        exit; // Stop further execution
    }

    // Attempt to log in
    $loggedInUser = $user->loginByEmail($email, $password);

    if ($loggedInUser) {
        // Successful login
        $auth->login($loggedInUser['user_id'], $loggedInUser['username'], $loggedInUser['role']);
        echo json_encode(['status' => 'success', 'redirect' => 'user-profile']);
    } else {
        // Login failed
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// End output buffering and flush
ob_end_flush();
?>