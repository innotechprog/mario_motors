<?php
include "../assets/classes/connect_db_class.php";
include "../assets/classes/cars_class.php";
include "../assets/classes/images_class.php";
$database = new Database();
$db = $database->connect();
$image = new Image($db);
$car = new Car($db);

$car_id = $_GET['id'];
$car->setCarId($car_id);
$image->deleteCarImage($car_id);

$image->setCarId($car_id);
$image->deleteByCarId();

$car->delete();
?>