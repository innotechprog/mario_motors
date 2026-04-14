<?php
include "../assets/classes/connect_db_class.php";
include "../assets/classes/images_class.php";
$database = new Database();
$db = $database->connect();
$image = new Image($db);


$image_id = $_GET['id'];
$image->setImageId($image_id);
$image->delete();
?>