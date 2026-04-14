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
              <div id="part-message" class="alert d-none" role="alert"></div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="part_name" class="form-label">Part Name</label>
                  <input type="text" class="form-control" name="part_name" id="part_name" required>
                </div>
                <div class="col-md-6">
                  <label for="part_number" class="form-label">Part Number</label>
                  <input type="text" class="form-control" name="part_number" id="part_number">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="category" class="form-label">Category</label>
                  <select class="form-select" name="category" id="category" required>
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
                </div>
                <div class="col-md-6">
                  <label for="make" class="form-label">Make</label>
                  <input type="text" class="form-control" name="make" id="make" required>
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
                  <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" required>
                </div>
                <div class="col-md-4">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" step="0.01" class="form-control" name="price" id="price" min="0" required>
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
                  <label for="images" class="form-label">Part Images</label>
                  <input class="form-control" type="file" name="images[]" id="images" multiple accept="image/*">
                  <small class="text-muted">First image becomes the primary image.</small>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Save Part</button>
              <a href="parts" class="btn btn-outline-secondary">View Parts</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
  const partsForm = document.getElementById('partsForm');
  const partMessage = document.getElementById('part-message');

  partsForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    partMessage.classList.add('d-none');
    partMessage.classList.remove('alert-danger', 'alert-success');

    const formData = new FormData(partsForm);
    try {
      const response = await fetch(partsForm.action, {
        method: 'POST',
        body: formData
      });
      const responseText = await response.text();
      let data;

      try {
        data = JSON.parse(responseText);
      } catch (parseError) {
        partMessage.classList.remove('d-none');
        partMessage.classList.add('alert-danger');
        partMessage.textContent = 'Server returned an invalid response while saving the part.';
        return;
      }

      partMessage.classList.remove('d-none');
      if (data.success) {
        partMessage.classList.add('alert-success');
        partMessage.textContent = data.message;
        partsForm.reset();
      } else {
        partMessage.classList.add('alert-danger');
        partMessage.textContent = data.message;
      }
    } catch (error) {
      partMessage.classList.remove('d-none');
      partMessage.classList.add('alert-danger');
      partMessage.textContent = 'Unable to save part right now. Please try again.';
    }
  });
</script>
</body>
</html>
