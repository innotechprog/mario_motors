<?php
include "assets/classes/connect_db_class.php";
include "assets/classes/auth_class.php";
require_once 'assets/classes/users_class.php';
require 'assets/classes/cars_class.php';
require 'assets/classes/images_class.php';
$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$user = new User($db);
$car = new Car($db);
$image = new Image($db);
$car_id = "";

// Require the user to be logged in
$auth->requireLogin();

// User is authenticated, proceed
$user_id = $_SESSION['user_id'];
$user->setUserId($user_id);
$user_data = $user->read($user_id);
//echo $user_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mario Motors & Spare Parts</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  
  <!-- Custom Styles for Image Upload -->
  <style>
    #drop-zone:hover {
        border-color: #0d6efd !important;
        background: #e7f1ff !important;
    }
    
    /* Only apply hover effects to preview container cards, not all cards */
    #preview-container .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    #preview-container .card:hover:not(.dragging-card) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
    
    /* Prevent hover effects during drag operations */
    .dragging-card {
        opacity: 0.6 !important;
        transform: scale(0.95) !important;
        transition: none !important;
    }
    
    .dragging-card:hover {
        transform: scale(0.95) !important;
        box-shadow: none !important;
    }
    
    #preview-container .card img {
        transition: transform 0.3s;
    }
    
    #preview-container .card:hover img {
        transform: scale(1.05);
    }
    
    /* Image Picker Modal Styles */
    .picker-card {
        position: relative;
        overflow: hidden;
        will-change: transform;
        transform: translateZ(0); /* GPU acceleration */
        backface-visibility: hidden;
        background: #fff;
    }
    
    .picker-card img {
        display: block;
        width: 100%;
        height: 120px;
        object-fit: cover;
        background: #f0f0f0;
    }
    
    .picker-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
        z-index: 10;
    }
    
    .picker-card.selected {
        border: 2px solid #0d6efd;
    }
    
    /* Special styling for primary image (first selected) */
    .picker-card.selected[data-order="1"] {
        border: 3px solid #dc3545;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
    }
    
    .picker-card.selected[data-order="1"]::after {
        background: #dc3545;
        content: "★1";
        font-size: 1rem;
    }
    
    /* Ensure card body doesn't become transparent */
    .picker-card .card-body {
        background: #fff;
    }
    
    #picker-preview {
        scrollbar-width: thin;
        scrollbar-color: #0d6efd #e9ecef;
    }
    
    #picker-preview::-webkit-scrollbar {
        height: 6px;
    }
    
    #picker-preview::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 3px;
    }
    
    #picker-preview::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 3px;
    }
    
    #picker-preview::-webkit-scrollbar-thumb:hover {
        background: #0b5ed7;
    }
    
    /* Animation for picker cards */
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .picker-card {
        animation: fadeInScale 0.3s ease;
    }
    
    /* Badge pulse animation */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
    
    @keyframes popIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Picker overlay - always has badge, controlled by CSS only */
    .picker-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: transparent;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Prevent overlay from showing through */
    .picker-card .position-relative {
        position: relative;
        background: #fff;
        overflow: hidden;
    }
    
    /* Badge - use ::after pseudo-element, controlled by data attribute */
    .picker-card::after {
        content: attr(data-order);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        z-index: 10;
        pointer-events: none;
    }
    
    /* Show badge and overlay when selected */
    .picker-card.selected::after {
        display: flex;
        animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .picker-card.selected .picker-overlay {
        background: rgba(13, 110, 253, 0.5) !important;
    }
    
    /* Prevent transparency issues on hover */
    .picker-card:hover .picker-overlay {
        background: transparent !important;
    }
    
    .picker-card.selected:hover .picker-overlay {
        background: rgba(13, 110, 253, 0.5) !important;
    }
    
    /* Click ripple effect */
    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    
    .picker-card.selecting {
        animation: pulse 0.3s ease;
    }
    
    /* Drag and drop visual feedback */
    .drag-over {
        border-left: 3px solid #0d6efd !important;
        background: rgba(13, 110, 253, 0.05) !important;
    }
    
    .grip-handle {
        opacity: 0.3;
        transition: opacity 0.2s ease;
        cursor: grab;
    }
    
    .grip-handle:hover {
        opacity: 1;
    }
    
    .grip-handle:active {
        cursor: grabbing;
    }
  </style>

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <?php
  include "header.php";
  include "sidemenu.php";
  ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Add a car</h1>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
                            <!-- General Form Elements -->
       
    <form id="carForm" class="mt-4" action="processes/process_car.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <!-- Year -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="form-label">Year</label>
                <select class="form-select" aria-label="Year select" name="year">
                    <option value="">Select Year</option>
                    <?php
                    $currentYear = date("Y");
                    $minYear = 1900;
                    for ($year = $currentYear; $year >= $minYear; $year--) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                    ?>
                </select>
                <span class="text-danger" id="year-error"></span>
            </div>
        </div>

        <!-- MM Code -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="mm_code" class="form-label">MM Code</label>
                <input type="text" class="form-control" name="mm_code" id="mm_code">
                <span class="text-danger" id="mm-code-error"></span>
            </div>
        </div>

        <!-- Make -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="make" class="form-label">Make</label>
                <select class="form-select" name="make" id="make">
                    <option value="">Select Make</option>
                    <option value="Audi">Audi</option>
                    <option value="BMW">BMW</option>
                    <option value="Mercedes-Benz">Mercedes-Benz</option>
                    <option value="Volkswagen">Volkswagen</option>
                    <option value="Toyota">Toyota</option>
                    <option value="Honda">Honda</option>
                    <option value="Nissan">Nissan</option>
                    <option value="Ford">Ford</option>
                    <option value="Chevrolet">Chevrolet</option>
                    <option value="Hyundai">Hyundai</option>
                    <option value="Kia">Kia</option>
                    <option value="Mazda">Mazda</option>
                    <option value="Subaru">Subaru</option>
                    <option value="Mitsubishi">Mitsubishi</option>
                    <option value="Lexus">Lexus</option>
                    <option value="Infiniti">Infiniti</option>
                    <option value="Acura">Acura</option>
                    <option value="Volvo">Volvo</option>
                    <option value="Jaguar">Jaguar</option>
                    <option value="Land Rover">Land Rover</option>
                    <option value="Porsche">Porsche</option>
                    <option value="Mini">Mini</option>
                    <option value="Jeep">Jeep</option>
                    <option value="Dodge">Dodge</option>
                    <option value="Chrysler">Chrysler</option>
                    <option value="Cadillac">Cadillac</option>
                    <option value="Buick">Buick</option>
                    <option value="GMC">GMC</option>
                    <option value="Lincoln">Lincoln</option>
                    <option value="Tesla">Tesla</option>
                    <option value="Genesis">Genesis</option>
                    <option value="Alfa Romeo">Alfa Romeo</option>
                    <option value="Maserati">Maserati</option>
                    <option value="Ferrari">Ferrari</option>
                    <option value="Lamborghini">Lamborghini</option>
                    <option value="Bentley">Bentley</option>
                    <option value="Rolls-Royce">Rolls-Royce</option>
                    <option value="Other">Other</option>
                </select>
                <span class="text-danger" id="make-error"></span>
                
                <!-- Custom Make Input (hidden by default) -->
                <div id="custom-make-container" class="mt-2" style="display: none;">
                    <label for="custom_make" class="form-label">Please specify the make:</label>
                    <input type="text" class="form-control" name="custom_make" id="custom_make" placeholder="Enter custom make">
                    <span class="text-danger" id="custom-make-error"></span>
                </div>
            </div>
        </div>

        <!-- Model -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="model" class="form-label">Model</label>
                <select class="form-select" name="model" id="model" disabled>
                    <option value="">Select Make First</option>
                </select>
                <span class="text-danger" id="model-error"></span>
                
                <!-- Custom Model Input (hidden by default) -->
                <div id="custom-model-container" class="mt-2" style="display: none;">
                    <label for="custom_model" class="form-label">Please specify the model:</label>
                    <input type="text" class="form-control" name="custom_model" id="custom_model" placeholder="Enter custom model">
                    <span class="text-danger" id="custom-model-error"></span>
                </div>
            </div>
        </div>

        <!-- Variant -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="variant" class="form-label">Variant</label>
                <select class="form-select" name="variant" id="variant" disabled>
                    <option value="">Select Model First</option>
                </select>
                <span class="text-danger" id="variant-error"></span>
                
                <!-- Custom Variant Input (hidden by default) -->
                <div id="custom-variant-container" class="mt-2" style="display: none;">
                    <label for="custom_variant_input" class="form-label">Please specify the variant:</label>
                    <input type="text" class="form-control" name="custom_variant_input" id="custom_variant_input" placeholder="Enter custom variant">
                    <span class="text-danger" id="custom-variant-input-error"></span>
                </div>
            </div>
        </div>

        <!-- Custom Variant -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="custom_variant" class="form-label">Custom Variant</label>
                <input type="text" class="form-control" name="custom_variant" id="custom_variant">
                <span class="text-danger" id="custom-variant-error"></span>
            </div>
        </div>

        <!-- VIN Optional -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="vin" class="form-label">VIN Optional</label>
                <input type="text" class="form-control" name="vin" id="vin">
                <span class="text-danger" id="vin-error"></span>
            </div>
        </div>

        <!-- Mileage -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="mileage" class="form-label">Mileage</label>
                <input type="number" class="form-control" name="mileage" id="mileage" min="0">
                <span class="text-danger" id="mileage-error"></span>
            </div>
        </div>

        <!-- Price -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" name="price" id="price" min="0" step="0.01">
                <span class="text-danger" id="price-error"></span>
            </div>
        </div>

        <!-- Color -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="color" class="form-label">Color</label>
                <select class="form-select" name="color" id="color">
                    <option value="">Select Color</option>
                    <option value="White">White</option>
                    <option value="Black">Black</option>
                    <option value="Silver">Silver</option>
                    <option value="Gray">Gray</option>
                    <option value="Red">Red</option>
                    <option value="Blue">Blue</option>
                    <option value="Green">Green</option>
                    <option value="Yellow">Yellow</option>
                    <option value="Orange">Orange</option>
                    <option value="Brown">Brown</option>
                    <option value="Beige">Beige</option>
                    <option value="Gold">Gold</option>
                    <option value="Purple">Purple</option>
                    <option value="Pink">Pink</option>
                    <option value="Maroon">Maroon</option>
                    <option value="Navy">Navy</option>
                    <option value="Burgundy">Burgundy</option>
                    <option value="Champagne">Champagne</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Titanium">Titanium</option>
                    <option value="Pearl White">Pearl White</option>
                    <option value="Metallic Silver">Metallic Silver</option>
                    <option value="Matte Black">Matte Black</option>
                    <option value="Other">Other</option>
                </select>
                <span class="text-danger" id="color-error"></span>
            </div>
        </div>

        <!-- Transmission -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="transmission" class="form-label">Transmission</label>
                <select class="form-select" aria-label="Transmission select" name="transmission" id="transmission">
                    <option value="">Select Transmission</option>
                    <option value="Manual">Manual</option>
                    <option value="Automatic">Automatic</option>
                </select>
                <span class="text-danger" id="transmission-error"></span>
            </div>
        </div>

        <!-- Fuel Type -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="fuel_type" class="form-label">Fuel Type</label>
                <select class="form-select" aria-label="Fuel Type select" name="fuel_type" id="fuel_type">
                    <option value="">Select Fuel Type</option>
                    <option value="Petrol">Petrol</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Electric">Electric</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
                <span class="text-danger" id="fuel-type-error"></span>
            </div>
        </div>

        <!-- Description -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                <span class="text-danger" id="description-error"></span>
            </div>
        </div>

        <!-- Eligible For Finance -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="form-label">Eligible For Finance</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="finance_eligible" id="yes" value="Yes" checked>
                    <label class="form-check-label" for="yes">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="finance_eligible" id="no" value="No">
                    <label class="form-check-label" for="no">No</label>
                </div>
                <span class="text-danger" id="finance-eligible-error"></span>
            </div>
        </div>

        <!-- Used or New -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="form-label">Used or New</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition_type" id="used" value="Used" checked>
                    <label class="form-check-label" for="used">Used</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition_type" id="new" value="New">
                    <label class="form-check-label" for="new">New</label>
                </div>
                <span class="text-danger" id="condition-type-error"></span>
            </div>
        </div>

        <!-- Condition -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="form-label">Condition</label>
                <select class="form-select" aria-label="Condition select" name="condition">
                    <option value="">Select Condition</option>
                    <option value="Very Poor">Very Poor</option>
                    <option value="Poor">Poor</option>
                    <option value="Fair">Fair</option>
                    <option value="Average">Average</option>
                    <option value="Good">Good</option>
                    <option value="Excellent">Excellent</option>
                </select>
                <span class="text-danger" id="condition-error"></span>
            </div>
        </div>

        <!-- Visibility Field -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="form-label">Show Car on Website?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="visibility_yes" value="Yes" checked>
                    <label class="form-check-label" for="visibility_yes">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="visibility_no" value="No">
                    <label class="form-check-label" for="visibility_no">No</label>
                </div>
                <span class="text-danger" id="visibility-error"></span>
            </div>
        </div>

        <!-- Car Images -->
        <div class="row mb-3">
    <div class="col-sm-12">
        <label for="images" class="form-label">Car Images</label>
        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" style="display: none;">
        
        <!-- Drag & Drop Area -->
        <div id="drop-zone" class="border rounded p-4 text-center" style="border: 2px dashed #ccc; background: #f8f9fa; cursor: pointer; transition: all 0.3s;">
            <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
            <h5 class="mt-3">Drag & Drop images here</h5>
            <p class="text-muted mb-3">or</p>
            <button type="button" class="btn btn-primary" id="custom-images-btn">
                <i class="bi bi-images"></i> Select Images in Order
            </button>
            <p class="text-muted small mt-2">Supports: JPG, PNG, WEBP, GIF</p>
            <p class="text-warning small mt-1"><i class="bi bi-info-circle"></i> Choose your images in the order you want them to appear</p>
        </div>
        
        <span class="text-danger" id="images-error"></span>
        
        <!-- Preview Container -->
        <div id="preview-container" class="mt-3"></div>
    </div>
</div>

<!-- Custom Image Picker Modal -->
<div class="modal fade" id="imagePickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-images"></i> Select Images 
                    <span id="picker-counter" class="badge bg-light text-primary ms-2">0 selected</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: calc(100vh - 180px); overflow-y: auto;">
                <div class="container-fluid py-3">
                    <div class="alert alert-info mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle fs-4 me-3"></i>
                            <div>
                                <strong>How to select images in order:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><strong>Click images in the exact order</strong> you want them to appear on your listing</li>
                                    <li>The <strong>first image you select will be the main/primary image</strong></li>
                                    <li>Numbered badges (#1, #2, #3...) show the selection order</li>
                                    <li>Click again to deselect an image</li>
                                    <li>View your selection sequence at the bottom</li>
                                    <li>The order you choose here determines how images appear to customers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="picker-grid" class="row g-2"></div>
                </div>
            </div>
            <div class="modal-footer d-block bg-light" style="height: 120px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Selected Images (in order):</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.clearPickerSelection()">
                        <i class="bi bi-x-circle"></i> Clear All
                    </button>
                </div>
                <div id="picker-preview" class="d-flex gap-2 overflow-auto mb-2" style="height: 60px;">
                    <div class="text-muted small">No images selected</div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="window.confirmImageSelection()">
                        <i class="bi bi-check-circle"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal (WhatsApp style) -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: rgba(0,0,0,0.95);">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="modalImageName">Image Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalImagePreview" src="" alt="Preview" class="img-fluid" style="max-height: 70vh; object-fit: contain;">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

        <!-- Progress Bar -->
        <div id="progress" class="mt-3" style="display: none;">
            <div class="progress">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
            </div>
            <p id="progressText" class="mt-2"></p>
        </div>

        <!-- Message -->
        <div id="message" class="mt-3" style="display: none;"></div>

        <!-- Submit Button -->
        <div class="row mb-3">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary">Submit Form</button>
            </div>
        </div>
    </form>

            </div>
          </div>

        </div>      
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/carformval.js"></script>
</body>

</html>