<?php
require_once __DIR__ . '/../assets/classes/connect_db_class.php';
require_once __DIR__ . '/../assets/classes/parts_class.php';

$database = new Database();
$db = $database->connect();
$part = new Part($db);

echo $part->ensureTables() ? "Parts tables ready\n" : "Failed to create parts tables\n";
