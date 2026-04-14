<?php
require_once '../assets/classes/connect_db_class.php';
require_once '../assets/classes/auth_class.php';
require_once '../assets/classes/parts_class.php';

$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$auth->requireLogin();

$part = new Part($db);
$part->ensureTables();

$partId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($partId <= 0) {
    exit;
}

$part->deletePartImagesFolder($partId);
$part->setPartId($partId);
$part->delete();
?>
