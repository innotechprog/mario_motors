<?php
ob_start();

require_once '../assets/classes/connect_db_class.php';
require_once '../assets/classes/users_class.php';

ob_clean();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$newPassword = trim($_POST['new_password'] ?? '');
$confirmNewPassword = trim($_POST['confirm_new_password'] ?? '');

if (! $email || $newPassword === '' || $confirmNewPassword === '') {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields with a valid email.']);
    exit;
}

if (strlen($newPassword) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long.']);
    exit;
}

if ($newPassword !== $confirmNewPassword) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit;
}

try {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $existingUser = $user->getByEmail($email);
    if (! $existingUser) {
        echo json_encode(['status' => 'error', 'message' => 'No user account found for that email address.']);
        exit;
    }

    $user->setUserId($existingUser['user_id']);
    $user->setPasswordHash(password_hash($newPassword, PASSWORD_DEFAULT));

    if ($user->updatePassword()) {
        echo json_encode(['status' => 'success', 'message' => 'Password reset successful. You can now sign in.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to reset password. Please try again.']);
    }
} catch (Throwable $e) {
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again later.']);
}

ob_end_flush();
