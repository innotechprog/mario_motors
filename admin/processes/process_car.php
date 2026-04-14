<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in response
ini_set('log_errors', 1);

// Include the database connection, auth and Car class
require_once '../assets/classes/connect_db_class.php';
require_once '../assets/classes/auth_class.php';
require_once '../assets/classes/cars_class.php';
require_once '../assets/classes/images_class.php';

// Create a new database connection
$database = new Database();
$db = $database->connect();

// Initialize authentication
$auth = new Auth($db);

// Require the user to be logged in
$auth->requireLogin();

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Create a new Car object
$car = new Car($db);

// Create a new Image object
$image = new Image($db);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        error_log("Starting car creation process...");
        
        // Retrieve and sanitize form data
        $car->setSellerId($user_id); // Use the actual user_id from session
        $car->setYear(intval($_POST['year']));
        $car->setMmCode(htmlspecialchars($_POST['mm_code'] ?? ''));
        
        // Handle make (use custom if "Other" is selected)
        $make = '';
        if (isset($_POST['make']) && $_POST['make'] === 'Other') {
            $make = htmlspecialchars($_POST['custom_make'] ?? '');
            error_log("Using custom make: " . $make);
        } else {
            $make = htmlspecialchars($_POST['make'] ?? '');
            error_log("Using standard make: " . $make);
        }
        $car->setMake($make);
        
        // Handle model (use custom if "Other" is selected or if make is "Other")
        $model = '';
        if (isset($_POST['make']) && $_POST['make'] === 'Other') {
            $model = htmlspecialchars($_POST['custom_model'] ?? '');
        } else if (isset($_POST['model']) && $_POST['model'] === 'Other') {
            $model = htmlspecialchars($_POST['custom_model'] ?? '');
        } else if (isset($_POST['model'])) {
            $model = htmlspecialchars($_POST['model']);
        }
        $car->setModel($model);
        
        // Handle variant (use custom if "Other" is selected, otherwise use selected or existing custom_variant)
        $variant = '';
        if (isset($_POST['variant']) && $_POST['variant'] === 'Other') {
            $variant = htmlspecialchars($_POST['custom_variant_input'] ?? '');
        } else if (isset($_POST['variant']) && !empty($_POST['variant'])) {
            $variant = htmlspecialchars($_POST['variant']);
        }
        $car->setVariant($variant);
        
        $car->setCustomVariant(htmlspecialchars($_POST['custom_variant'] ?? ''));
        $car->setVin(htmlspecialchars($_POST['vin'] ?? ''));
        $car->setMileage(intval($_POST['mileage'] ?? 0));
        $car->setPrice(floatval($_POST['price'] ?? 0));
        $car->setColor(htmlspecialchars($_POST['color'] ?? ''));
        $car->setTransmission(htmlspecialchars($_POST['transmission'] ?? ''));
        $car->setFuelType(htmlspecialchars($_POST['fuel_type'] ?? ''));
        $car->setDescription(htmlspecialchars($_POST['description'] ?? ''));
        $car->setFinanceEligible(htmlspecialchars($_POST['finance_eligible'] ?? 'Yes'));
        $car->setConditionType(htmlspecialchars($_POST['condition_type'] ?? 'Used'));
        $car->setCondition(htmlspecialchars($_POST['condition'] ?? ''));
        $car->setVisibility(htmlspecialchars($_POST['visibility'] ?? 'Yes'));
        $car->setStatus('Available'); // Default status

        error_log("Car data prepared, attempting to create...");
        error_log("Make: " . $make . ", Model: " . $model . ", Variant: " . $variant);

        // Attempt to create the car listing
        if (isset($_POST['edit_car'])) {
            $car_id = $_POST['car_id'];
            $car->setCarId(htmlspecialchars($car_id));
            if ($car->update()) {
                header('Location: ../edit-car?id=' . $car_id);
            }
        } else {
            error_log("Creating new car...");
            $createResult = $car->create();
            error_log("Car create result: " . ($createResult ? "SUCCESS" : "FAILED"));
            
            if ($createResult) {
                error_log("Car created successfully");
                // Get the newly created car ID
                $car_id = $car->getCarId();
                error_log("Car ID: " . $car_id);

                // Process uploaded images
                $images_processed = 0;
                $image_errors = [];
                
                if (!empty($_FILES['images'])) {
                    error_log("Processing " . count($_FILES['images']['tmp_name']) . " images");
                    
                    // Get image order from form data
                    $image_order = [];
                    if (isset($_POST['image_order'])) {
                        $image_order = json_decode($_POST['image_order'], true);
                        error_log("Image order: " . print_r($image_order, true));
                    }
                    
                    // Create unique folder for this car
                    $car_folder = "car_" . $car_id;
                    $base_upload_dir = "../img/cars/";
                    $upload_dir = $base_upload_dir . $car_folder . "/";
                    
                    // Create the main cars directory if it doesn't exist
                    if (!is_dir($base_upload_dir)) {
                        mkdir($base_upload_dir, 0755, true);
                        error_log("Created base directory: $base_upload_dir");
                    }
                    
                    // Create the specific car folder
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                        error_log("Created car folder: $upload_dir");
                    }
                    
                    // Loop through uploaded files in order
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                        if (!empty($tmp_name)) {
                            // Validate file type/size
                            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                            $file_type = $_FILES['images']['type'][$key];
                            $file_size = $_FILES['images']['size'][$key];
                            
                            error_log("Processing image $key: Type=$file_type, Size=$file_size");

                            if (in_array($file_type, $allowed_types) && $file_size <= 10 * 1024 * 1024) { // Max 10MB
                                // Extract the original filename (may have order prefix like "000_image.jpg")
                                $original_filename = $_FILES['images']['name'][$key];
                                
                                // Remove the order prefix if it exists (e.g., "000_" from "000_image.jpg")
                                $clean_filename = preg_replace('/^\d{3}_/', '', $original_filename);
                                
                                // Generate sequential filename based on order: 001.jpg, 002.jpg, etc.
                                $ext = pathinfo($clean_filename, PATHINFO_EXTENSION);
                                $filename = sprintf("%03d", $key + 1) . ".$ext"; // 001.jpg, 002.jpg, etc.
                                $target_path = $upload_dir . $filename;
                                
                                error_log("Attempting to move file to: $target_path (Order: " . ($key + 1) . ")");

                                // Move uploaded file
                                if (move_uploaded_file($tmp_name, $target_path)) {
                                    error_log("File moved successfully");
                                    
                                    // Determine if this is the primary image (first in order)
                                    $is_primary = ($key == 0) ? 1 : 0;
                                    
                    // Save image metadata to database with relative path
                    $image->setCarId($car_id);
                    $image->setImageUrl("admin/img/cars/" . $car_folder . "/" . $filename);
                    $image->setIsPrimary($is_primary);
                                    
                                    if ($image->create()) {
                                        $images_processed++;
                                        error_log("Image $key saved to database successfully as " . $filename);
                                    } else {
                                        $image_errors[] = "Failed to save image " . $clean_filename . " to database";
                                        error_log("Failed to save image $key to database");
                                    }
                                } else {
                                    $image_errors[] = "Failed to upload image " . $clean_filename;
                                    error_log("Failed to move uploaded file for image $key");
                                }
                            } else {
                                $image_errors[] = "Invalid image type or size for " . ($_FILES['images']['name'][$key] ?? $key);
                                error_log("Invalid image type or size for image $key");
                            }
                        }
                    }
                    
                    error_log("Finished processing images. Total uploaded: $images_processed to folder: $upload_dir");
                }
                
                // Return success response
                $message = "Car added successfully!";
                if ($images_processed > 0) {
                    $message .= " $images_processed image(s) uploaded.";
                }
                if (!empty($image_errors)) {
                    $message .= " Some images had errors: " . implode(", ", $image_errors);
                }
                
                // Send car alerts to subscribers
                try {
                    include_once '../../forms/car_alerts.php';
                    if (function_exists('sendNewCarAlerts')) {
                        $alertsSent = sendNewCarAlerts($car_id);
                        if ($alertsSent) {
                            error_log("Car alerts sent successfully for car ID: " . $car_id);
                        } else {
                            error_log("No car alerts sent for car ID: " . $car_id);
                        }
                    }
                } catch (Exception $alertError) {
                    error_log("Error sending car alerts: " . $alertError->getMessage());
                    // Don't fail the car creation if alerts fail
                }
                
                echo json_encode([
                    "success" => true,
                    "message" => $message,
                    "car_id" => $car_id,
                    "images_processed" => $images_processed
                ]);
                exit();
            } else {
                error_log("Car creation failed");
                // Handle database error
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to add car. Database error.",
                ]);
                exit();
            }
        }
    } catch (Exception $e) {
        // Handle any errors that occurred during processing
        error_log("Car creation error: " . $e->getMessage());
        error_log("Error trace: " . $e->getTraceAsString());
        error_log("POST data: " . print_r($_POST, true));
        
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $e->getMessage(),
            "debug" => "Check error logs for details"
        ]);
        exit();
    }
} else {
    // Handle invalid request method
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method.",
    ]);
    exit();
}
?>