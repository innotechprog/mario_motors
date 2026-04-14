<?php
require_once '../assets/classes/connect_db_class.php';
require_once '../assets/classes/auth_class.php';
require_once '../assets/classes/parts_class.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$auth->requireLogin();

$part = new Part($db);
$part->ensureTables();

$user_id = $_SESSION['user_id'];
$imageId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($imageId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid image id.']);
    exit;
}

$deleted = $part->deleteImageByIdForSeller($imageId, $user_id);

if ($deleted) {
    echo json_encode(['success' => true, 'message' => 'Image deleted successfully.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unable to delete image.']);
exit;
