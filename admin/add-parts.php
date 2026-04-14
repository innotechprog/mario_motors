<?php
include 'assets/classes/connect_db_class.php';
include 'assets/classes/auth_class.php';
require_once 'assets/classes/users_class.php';
require_once 'assets/classes/parts_class.php';

$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$user = new User($db);
$part = new Part($db);

$auth->requireLogin();
$part->ensureTables();

$user_id = $_SESSION['user_id'];
$user->setUserId($user_id);
$user_data = $user->read($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Mario Motors & Spare Parts | Add Parts</title>
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    #drop-zone:hover {
        border-color: #0d6efd !important;
        background: #e7f1ff !important;
    }

    #preview-container .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #preview-container .card:hover:not(.dragging-card) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }

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

    .picker-card {
        position: relative;
        overflow: hidden;
        will-change: transform;
        transform: translateZ(0);
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

    .picker-card.selected[data-order="1"] {
        border: 3px solid #dc3545;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
    }

    .picker-card.selected[data-order="1"]::after {
        background: #dc3545;
        content: "★1";
        font-size: 1rem;
    }

    .picker-card .card-body {
        background: #fff;
    }

    #picker-preview {
        scrollbar-width: thin;
        scrollbar-color: #0d6efd #e9ecef;
    }

    #picker-preview::-webkit-scrollbar { height: 6px; }
    #picker-preview::-webkit-scrollbar-track { background: #e9ecef; border-radius: 3px; }
    #picker-preview::-webkit-scrollbar-thumb { background: #0d6efd; border-radius: 3px; }
    #picker-preview::-webkit-scrollbar-thumb:hover { background: #0b5ed7; }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.9); }
        to   { opacity: 1; transform: scale(1); }
    }
    .picker-card { animation: fadeInScale 0.3s ease; }

    @keyframes popIn {
        0%   { transform: scale(0); opacity: 0; }
        50%  { transform: scale(1.2); }
        100% { transform: scale(1);   opacity: 1; }
    }

    .picker-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: transparent;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .picker-card .position-relative {
        position: relative;
        background: #fff;
        overflow: hidden;
    }

    .picker-card::after {
        content: attr(data-order);
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 40px; height: 40px;
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

    .picker-card.selected::after {
        display: flex;
        animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .picker-card.selected .picker-overlay {
        background: rgba(13, 110, 253, 0.5) !important;
    }

    .picker-card:hover .picker-overlay { background: transparent !important; }
    .picker-card.selected:hover .picker-overlay { background: rgba(13, 110, 253, 0.5) !important; }

    .drag-over {
        border-left: 3px solid #0d6efd !important;
        background: rgba(13, 110, 253, 0.05) !important;
    }

    .grip-handle {
        opacity: 0.3;
        transition: opacity 0.2s ease;
        cursor: grab;
    }
    .grip-handle:hover { opacity: 1; }
    .grip-handle:active { cursor: grabbing; }
  </style>
</head>
<body>
<?php include 'header.php'; include 'sidemenu.php'; ?>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Parts</h1>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <form id="partsForm" class="mt-4" action="processes/process_part.php" method="POST" enctype="multipart/form-data">

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="part_name" class="form-label">Part Name</label>
                  <input type="text" class="form-control" name="part_name" id="part_name">
                  <span class="text-danger" id="part_name-error"></span>
                </div>
                <div class="col-md-6">
                  <label for="part_number" class="form-label">Part Number</label>
                  <input type="text" class="form-control" name="part_number" id="part_number">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="category" class="form-label">Category</label>
                  <select class="form-select" name="category" id="category">
                    <option value="">Select Category</option>
                    <option value="Engine">Engine</option>
                    <option value="Brakes">Brakes</option>
                    <option value="Suspension">Suspension</option>
                    <option value="Exhaust">Exhaust</option>
                    <option value="Lighting">Lighting</option>
                    <option value="Body">Body</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Interior">Interior</option>
                    <option value="Wheels">Wheels</option>
                    <option value="Other">Other</option>
                  </select>
                  <span class="text-danger" id="category-error"></span>
                </div>
                <div class="col-md-6">
                  <label for="make" class="form-label">Make</label>
                  <input type="text" class="form-control" name="make" id="make">
                  <span class="text-danger" id="make-error"></span>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="model" class="form-label">Model</label>
                  <input type="text" class="form-control" name="model" id="model">
                </div>
                <div class="col-md-4">
                  <label for="variant" class="form-label">Variant</label>
                  <input type="text" class="form-control" name="variant" id="variant">
                </div>
                <div class="col-md-4">
                  <label for="year" class="form-label">Year</label>
                  <input type="number" class="form-control" name="year" id="year" min="1900" max="2100">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="mm_code" class="form-label">MM Code</label>
                  <input type="text" class="form-control" name="mm_code" id="mm_code">
                </div>
                <div class="col-md-4">
                  <label for="condition_type" class="form-label">Condition Type</label>
                  <select class="form-select" name="condition_type" id="condition_type">
                    <option value="Used">Used</option>
                    <option value="New">New</option>
                    <option value="Refurbished">Refurbished</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="part_condition" class="form-label">Part Condition</label>
                  <select class="form-select" name="part_condition" id="part_condition">
                    <option value="Excellent">Excellent</option>
                    <option value="Good" selected>Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Needs Repair">Needs Repair</option>
                    <option value="For Parts">For Parts</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1">
                </div>
                <div class="col-md-4">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" step="0.01" class="form-control" name="price" id="price" min="0">
                  <span class="text-danger" id="price-error"></span>
                </div>
                <div class="col-md-4">
                  <label for="visibility" class="form-label">Visibility</label>
                  <select class="form-select" name="visibility" id="visibility">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-12">
                  <label for="description" class="form-label">Description</label>
                  <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-12">
                  <label class="form-label">Part Images</label>
                  <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" style="display: none;">

                  <!-- Drag & Drop Area -->
                  <div id="drop-zone" class="border rounded p-4 text-center" style="border: 2px dashed #ccc; background: #f8f9fa; cursor: pointer; transition: all 0.3s;">
                      <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                      <h5 class="mt-3">Drag &amp; Drop images here</h5>
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

              <!-- Progress Bar -->
              <div id="progress" class="mt-3" style="display: none;">
                  <div class="progress">
                      <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                  </div>
                  <p id="progressText" class="mt-2"></p>
              </div>

              <!-- Message -->
              <div id="message" class="mt-3" style="display: none;"></div>

              <button type="submit" class="btn btn-primary">Save Part</button>
              <a href="parts" class="btn btn-outline-secondary">View Parts</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Image Picker Modal -->
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

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
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

<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/partformval.js"></script>
</body>
</html>
