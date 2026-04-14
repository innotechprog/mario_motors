<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in response
ini_set('log_errors', 1);

// Include the database connection, auth and Part class
require_once '../assets/classes/connect_db_class.php';
require_once '../assets/classes/auth_class.php';
require_once '../assets/classes/parts_class.php';

// Create a new database connection
$database = new Database();
$db = $database->connect();

// Initialize authentication
$auth = new Auth($db);

// Require the user to be logged in
$auth->requireLogin();

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Create a new Part object
$part = new Part($db);

// Ensure tables exist
$part->ensureTables();

function sanitizeInput($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirectToEditPart($partId, $status, $message): void {
    header('Location: ../edit-part?id=' . (int) $partId . '&status=' . urlencode($status) . '&message=' . urlencode($message));
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $isEditRequest = isset($_POST['edit_part']);

        $part->setSellerId($user_id);
        $part->setYear(isset($_POST['year']) && $_POST['year'] !== '' ? intval($_POST['year']) : null);
        $part->setMmCode(sanitizeInput($_POST['mm_code'] ?? ''));
        $part->setMake(sanitizeInput($_POST['make'] ?? ''));
        $part->setModel(sanitizeInput($_POST['model'] ?? ''));
        $part->setVariant(sanitizeInput($_POST['variant'] ?? ''));
        $part->setPartName(sanitizeInput($_POST['part_name'] ?? ''));
        $part->setPartNumber(sanitizeInput($_POST['part_number'] ?? ''));
        $part->setCategory(sanitizeInput($_POST['category'] ?? ''));
        $part->setConditionType(sanitizeInput($_POST['condition_type'] ?? 'Used'));
        $part->setPartCondition(sanitizeInput($_POST['part_condition'] ?? 'Good'));
        $part->setQuantity(max(1, intval($_POST['quantity'] ?? 1)));
        $part->setPrice(floatval($_POST['price'] ?? 0));
        $part->setDescription(sanitizeInput($_POST['description'] ?? ''));
        $part->setVisibility(sanitizeInput($_POST['visibility'] ?? 'Yes'));

        $make = trim($_POST['make'] ?? '');
        $partName = trim($_POST['part_name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price = floatval($_POST['price'] ?? 0);

        if ($make === '' || $partName === '' || $category === '' || $price <= 0) {
            if ($isEditRequest) {
                redirectToEditPart((int) ($_POST['part_id'] ?? 0), 'error', 'Make, part name, category and price are required.');
            }

            echo json_encode(['success' => false, 'message' => 'Make, part name, category and price are required.']);
            exit;
        }

        if ($isEditRequest) {
            $part_id = isset($_POST['part_id']) ? (int) $_POST['part_id'] : 0;
            if ($part_id <= 0) {
                redirectToEditPart(0, 'error', 'Invalid part selected.');
            }

            $part->setPartId($part_id);
            $existingPart = $part->readOneBySeller($user_id);
            if (!$existingPart) {
                redirectToEditPart($part_id, 'error', 'Part not found or access denied.');
            }

            $part->setStatus($existingPart['status'] ?? 'Available');

            if (!$part->update()) {
                redirectToEditPart($part_id, 'error', 'Failed to update part details.');
            }

            $images_processed = 0;
            $image_errors = [];
            $existingImages = $part->readImages();
            $hasPrimaryImage = false;
            foreach ($existingImages as $existingImage) {
                if ((int) ($existingImage['is_primary'] ?? 0) === 1) {
                    $hasPrimaryImage = true;
                    break;
                }
            }
            $nextImageNumber = count($existingImages) + 1;

            if (!empty($_FILES['images']) && !empty($_FILES['images']['tmp_name'])) {
                $part_folder = 'part_' . $part_id;
                $base_upload_dir = '../img/parts/';
                $upload_dir = $base_upload_dir . $part_folder . '/';

                if (!is_dir($base_upload_dir) && !mkdir($base_upload_dir, 0755, true)) {
                    throw new Exception('Unable to create uploads directory');
                }

                if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
                    throw new Exception('Unable to create part folder');
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if (empty($tmp_name)) {
                        continue;
                    }

                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $file_type = $_FILES['images']['type'][$key] ?? '';
                    $file_size = $_FILES['images']['size'][$key] ?? 0;
                    $original_filename = $_FILES['images']['name'][$key] ?? ('image_' . $key);

                    if (in_array($file_type, $allowed_types, true) && $file_size <= 10 * 1024 * 1024) {
                        $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
                        if ($ext === '') {
                            $ext = 'jpg';
                        }

                        $filename = sprintf('%03d', $nextImageNumber) . '.' . $ext;
                        $nextImageNumber++;
                        $target_path = $upload_dir . $filename;

                        if (move_uploaded_file($tmp_name, $target_path)) {
                            $is_primary = (!$hasPrimaryImage && $images_processed === 0) ? 1 : 0;

                            if ($part->addImage('admin/img/parts/' . $part_folder . '/' . $filename, $is_primary)) {
                                $images_processed++;
                                if ($is_primary === 1) {
                                    $hasPrimaryImage = true;
                                }
                            } else {
                                $image_errors[] = 'Failed to save image ' . $original_filename . ' to database';
                            }
                        } else {
                            $image_errors[] = 'Failed to upload image ' . $original_filename;
                        }
                    } else {
                        $image_errors[] = 'Invalid image type or size for ' . $original_filename;
                    }
                }
            }

            $message = 'Part updated successfully!';
            if ($images_processed > 0) {
                $message .= ' ' . $images_processed . ' image(s) uploaded.';
            }
            if (!empty($image_errors)) {
                $message .= ' Some images had errors: ' . implode(', ', $image_errors);
            }

            redirectToEditPart($part_id, 'success', $message);
        }

        error_log('Starting part creation process...');
        $part->setStatus('Available');

        $createResult = $part->create();

        if ($createResult) {
            $part_id = $part->getPartId();

            $images_processed = 0;
            $image_errors = [];

            if (!empty($_FILES['images'])) {
                $part_folder = 'part_' . $part_id;
                $base_upload_dir = '../img/parts/';
                $upload_dir = $base_upload_dir . $part_folder . '/';

                if (!is_dir($base_upload_dir)) {
                    if (!mkdir($base_upload_dir, 0755, true)) {
                        throw new Exception('Unable to create uploads directory');
                    }
                }

                if (!is_dir($upload_dir)) {
                    if (!mkdir($upload_dir, 0755, true)) {
                        throw new Exception('Unable to create part folder');
                    }
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name)) {
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        $file_type = $_FILES['images']['type'][$key];
                        $file_size = $_FILES['images']['size'][$key];

                        if (in_array($file_type, $allowed_types, true) && $file_size <= 10 * 1024 * 1024) {
                            $original_filename = $_FILES['images']['name'][$key];
                            $ext = pathinfo($original_filename, PATHINFO_EXTENSION);
                            $filename = sprintf('%03d', $key + 1) . '.' . $ext;
                            $target_path = $upload_dir . $filename;

                            if (move_uploaded_file($tmp_name, $target_path)) {
                                $is_primary = ($key == 0) ? 1 : 0;

                                if ($part->addImage('admin/img/parts/' . $part_folder . '/' . $filename, $is_primary)) {
                                    $images_processed++;
                                } else {
                                    $image_errors[] = 'Failed to save image ' . $original_filename . ' to database';
                                }
                            } else {
                                $image_errors[] = 'Failed to upload image ' . $original_filename;
                            }
                        } else {
                            $image_errors[] = 'Invalid image type or size for ' . ($_FILES['images']['name'][$key] ?? $key);
                        }
                    }
                }
            }

            $message = 'Part added successfully!';
            if ($images_processed > 0) {
                $message .= ' ' . $images_processed . ' image(s) uploaded.';
            }
            if (!empty($image_errors)) {
                $message .= ' Some images had errors: ' . implode(', ', $image_errors);
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'part_id' => $part_id,
                'images_processed' => $images_processed
            ]);
            exit();
        }

        echo json_encode([
            'success' => false,
            'message' => 'Failed to add part. Database error.',
        ]);
        exit();
    } catch (Exception $e) {
        // Handle any errors that occurred during processing
        error_log("Part creation error: " . $e->getMessage());
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
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit();
}
?>
