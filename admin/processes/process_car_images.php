<?php
require_once '../assets/classes/connect_db_class.php';
require_once '../assets/classes/images_class.php';

$database = new Database();
$db = $database->connect();
$image = new Image($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Get the car ID
    $car_id = intval($_POST['car_id']);

    // Step 2: Process uploaded images
    if (!empty($_FILES['images'])) {
        // Loop through uploaded files
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            // Validate file type/size
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['images']['type'][$key];
            $file_size = $_FILES['images']['size'][$key];

            if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) { // Max 5MB
                // Generate unique filename
                $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $filename = uniqid() . ".$ext";
                $upload_dir = "uploads/cars/$car_id/";

                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Move uploaded file
                $target_path = $upload_dir . $filename;
                if (move_uploaded_file($tmp_name, $target_path)) {
                    // Save image metadata to database
                    $image->setCarId($car_id);
                    $image->setImageUrl($target_path);
                    $image->setIsPrimary($key === 0); // Mark first image as primary
                    $image->create();
                }
            }
        }
    }

    echo "Images uploaded successfully!";
} else {
    echo "Invalid request method.";
}
?>