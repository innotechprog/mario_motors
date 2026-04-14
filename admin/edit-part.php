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

$part_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($part_id <= 0) {
  header('Location: parts');
  exit;
}

$part->setPartId($part_id);
$part_data = $part->readOneBySeller($user_id);
if (!$part_data) {
  header('Location: parts');
  exit;
}

$part_images = $part->readImages();
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Mario Motors & Spare Parts | Edit Part</title>
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
</head>
<body>
<?php include 'header.php'; include 'sidemenu.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Part</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
        <li class="breadcrumb-item"><a href="parts">Parts</a></li>
        <li class="breadcrumb-item active">Edit Part</li>
      </ol>
    </nav>
  </div>

  <section class="section profile">
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body pt-3">
            <?php if ($message !== ''): ?>
              <div class="alert <?php echo $status === 'success' ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
              </div>
            <?php endif; ?>

            <ul class="nav nav-tabs nav-tabs-bordered">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#part-edit">Edit Part Information</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#images-tab">Images</button>
              </li>
            </ul>

            <div class="tab-content pt-2">
              <div class="tab-pane fade show active profile-edit pt-3" id="part-edit">
                <form method="post" action="processes/process_part.php" enctype="multipart/form-data">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label for="part_name" class="form-label">Part Name</label>
                      <input type="text" class="form-control" name="part_name" id="part_name" value="<?php echo htmlspecialchars($part_data['part_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label for="part_number" class="form-label">Part Number</label>
                      <input type="text" class="form-control" name="part_number" id="part_number" value="<?php echo htmlspecialchars($part_data['part_number'] ?? ''); ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label for="category" class="form-label">Category</label>
                      <select class="form-select" name="category" id="category" required>
                        <?php
                        $categories = ['Engine', 'Brakes', 'Suspension', 'Exhaust', 'Lighting', 'Body', 'Electrical', 'Interior', 'Wheels', 'Other'];
                        foreach ($categories as $category) {
                          $selected = ($part_data['category'] === $category) ? 'selected' : '';
                          echo '<option value="' . htmlspecialchars($category) . '" ' . $selected . '>' . htmlspecialchars($category) . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="make" class="form-label">Make</label>
                      <input type="text" class="form-control" name="make" id="make" value="<?php echo htmlspecialchars($part_data['make']); ?>" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-4">
                      <label for="model" class="form-label">Model</label>
                      <input type="text" class="form-control" name="model" id="model" value="<?php echo htmlspecialchars($part_data['model'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                      <label for="variant" class="form-label">Variant</label>
                      <input type="text" class="form-control" name="variant" id="variant" value="<?php echo htmlspecialchars($part_data['variant'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                      <label for="year" class="form-label">Year</label>
                      <input type="number" class="form-control" name="year" id="year" min="1900" max="2100" value="<?php echo htmlspecialchars((string) ($part_data['year'] ?? '')); ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-4">
                      <label for="mm_code" class="form-label">MM Code</label>
                      <input type="text" class="form-control" name="mm_code" id="mm_code" value="<?php echo htmlspecialchars($part_data['mm_code'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                      <label for="condition_type" class="form-label">Condition Type</label>
                      <select class="form-select" name="condition_type" id="condition_type">
                        <?php
                        $conditionTypes = ['Used', 'New', 'Refurbished'];
                        foreach ($conditionTypes as $conditionType) {
                          $selected = (($part_data['condition_type'] ?? 'Used') === $conditionType) ? 'selected' : '';
                          echo '<option value="' . htmlspecialchars($conditionType) . '" ' . $selected . '>' . htmlspecialchars($conditionType) . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label for="part_condition" class="form-label">Part Condition</label>
                      <select class="form-select" name="part_condition" id="part_condition">
                        <?php
                        $partConditions = ['Excellent', 'Good', 'Fair', 'Needs Repair', 'For Parts'];
                        foreach ($partConditions as $partCondition) {
                          $selected = (($part_data['part_condition'] ?? 'Good') === $partCondition) ? 'selected' : '';
                          echo '<option value="' . htmlspecialchars($partCondition) . '" ' . $selected . '>' . htmlspecialchars($partCondition) . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-4">
                      <label for="quantity" class="form-label">Quantity</label>
                      <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="<?php echo (int) ($part_data['quantity'] ?? 1); ?>" required>
                    </div>
                    <div class="col-md-4">
                      <label for="price" class="form-label">Price</label>
                      <input type="number" step="0.01" class="form-control" name="price" id="price" min="0" value="<?php echo htmlspecialchars((string) $part_data['price']); ?>" required>
                    </div>
                    <div class="col-md-4">
                      <label for="visibility" class="form-label">Visibility</label>
                      <select class="form-select" name="visibility" id="visibility">
                        <option value="Yes" <?php echo ($part_data['visibility'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo ($part_data['visibility'] === 'No') ? 'selected' : ''; ?>>No</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="description" class="form-label">Description</label>
                      <textarea class="form-control" name="description" id="description" rows="4"><?php echo htmlspecialchars($part_data['description'] ?? ''); ?></textarea>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="images" class="form-label">Add More Images</label>
                      <input class="form-control" type="file" name="images[]" id="images" multiple accept="image/*">
                      <small class="text-muted">Upload additional images. Existing images remain visible in the Images tab.</small>
                    </div>
                  </div>

                  <div class="text-center">
                    <input type="hidden" name="part_id" value="<?php echo (int) $part_id; ?>">
                    <button type="submit" class="btn btn-primary" name="edit_part" value="1">Save Changes</button>
                    <a href="parts" class="btn btn-outline-secondary">Back to Parts</a>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade pt-3" id="images-tab">
                <div class="row mb-3">
                  <?php if (!empty($part_images)): ?>
                    <?php foreach ($part_images as $image): ?>
                      <?php
                      $imagePath = (string) ($image['image_url'] ?? '');
                      if (strpos($imagePath, 'admin/') === 0) {
                        $imagePath = substr($imagePath, 6);
                      }
                      ?>
                      <div class="col-lg-4 mb-3 part-image-card">
                        <div class="card h-100">
                          <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Part image" class="card-img-top" style="object-fit: cover; height: 220px;">
                          <div class="card-body py-2">
                            <?php if ((int) ($image['is_primary'] ?? 0) === 1): ?>
                              <span class="badge bg-primary">Primary</span>
                            <?php else: ?>
                              <span class="badge bg-secondary">Additional</span>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-danger float-end delete-part-image" data-image-id="<?php echo (int) $image['image_id']; ?>">Delete <i class="bi bi-trash"></i></button>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="col-12">
                      <div class="alert alert-warning mb-0">No images found for this part yet.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/delete_part_image.js"></script>
</body>
</html>
