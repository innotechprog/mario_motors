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
if(isset($_GET['id'])){
  $car_id = $_GET['id'];
  $car->setCarId($car_id);
  $car_data = $car->read();
  $car_data = $car_data->fetch();

  $image->setCarId($car_id);
  $car_images = $image->readByCar();
}
else{
  echo "Not set";
}
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

  <title>Car Information</title>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  
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
      <h1>Car Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
          <li class="breadcrumb-item"><a href="cars">Cars</a></li>
          <li class="breadcrumb-item active">Edit Car Info</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

               
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#car-edit">Edit Car Information</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#images_tab">Images</button>
                </li>

                

              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-edit pt-3" id="car-edit">
                  <!-- Profile Edit Form -->
                  <form method="post" id="carForm" action="processes/process_car.php">
    <!-- Year -->
    <div class="row mb-3">
        <label for="year" class="col-md-4 col-lg-3 col-form-label">Year</label>
        <div class="col-md-8 col-lg-9">
            <select class="form-select" aria-label="Year select" name="year" id="year">
                <option value="">Select Year</option>
                <?php
                $currentYear = date("Y");
                for ($year = 1900; $year <= $currentYear; $year++) {
                    echo "<option value=\"$year\"" . ($car_data['year'] == $year ? " selected" : "") . ">$year</option>";
                }
                ?>
            </select>
            <span class="text-danger" id="year-error"></span>
        </div>
    </div>

    <!-- MM Code -->
    <div class="row mb-3">
        <label for="mm_code" class="col-md-4 col-lg-3 col-form-label">MM Code</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="mm_code" id="mm_code" value="<?php echo htmlspecialchars($car_data['mm_code']); ?>">
            <span class="text-danger" id="mm-code-error"></span>
        </div>
    </div>

    <!-- Make -->
    <div class="row mb-3">
        <label for="make" class="col-md-4 col-lg-3 col-form-label">Make</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="make" id="make" value="<?php echo htmlspecialchars($car_data['make']); ?>">
            <span class="text-danger" id="make-error"></span>
        </div>
    </div>

    <!-- Model -->
    <div class="row mb-3">
        <label for="model" class="col-md-4 col-lg-3 col-form-label">Model</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="model" id="model" value="<?php echo htmlspecialchars($car_data['model']); ?>">
            <span class="text-danger" id="model-error"></span>
        </div>
    </div>

    <!-- Variant -->
    <div class="row mb-3">
        <label for="variant" class="col-md-4 col-lg-3 col-form-label">Variant</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="variant" id="variant" value="<?php echo htmlspecialchars($car_data['variant']); ?>">
            <span class="text-danger" id="variant-error"></span>
        </div>
    </div>

    <!-- Custom Variant -->
    <div class="row mb-3">
        <label for="custom_variant" class="col-md-4 col-lg-3 col-form-label">Custom Variant</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="custom_variant" id="custom_variant" value="<?php echo htmlspecialchars($car_data['custom_variant']); ?>">
            <span class="text-danger" id="custom-variant-error"></span>
        </div>
    </div>

    <!-- VIN Optional -->
    <div class="row mb-3">
        <label for="vin" class="col-md-4 col-lg-3 col-form-label">VIN Optional</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="vin" id="vin" value="<?php echo htmlspecialchars($car_data['vin']); ?>">
            <span class="text-danger" id="vin-error"></span>
        </div>
    </div>

    <!-- Mileage -->
    <div class="row mb-3">
        <label for="mileage" class="col-md-4 col-lg-3 col-form-label">Mileage</label>
        <div class="col-md-8 col-lg-9">
            <input type="number" class="form-control" name="mileage" id="mileage" min="0" value="<?php echo htmlspecialchars($car_data['mileage']); ?>">
            <span class="text-danger" id="mileage-error"></span>
        </div>
    </div>

    <!-- Price -->
    <div class="row mb-3">
        <label for="price" class="col-md-4 col-lg-3 col-form-label">Price</label>
        <div class="col-md-8 col-lg-9">
            <input type="number" class="form-control" name="price" id="price" min="0" step="0.01" value="<?php echo htmlspecialchars($car_data['price']); ?>">
            <span class="text-danger" id="price-error"></span>
        </div>
    </div>

    <!-- Color -->
    <div class="row mb-3">
        <label for="color" class="col-md-4 col-lg-3 col-form-label">Color</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="color" id="color" value="<?php echo htmlspecialchars($car_data['color']); ?>">
            <span class="text-danger" id="color-error"></span>
        </div>
    </div>

    <!-- Transmission -->
    <div class="row mb-3">
        <label for="transmission" class="col-md-4 col-lg-3 col-form-label">Transmission</label>
        <div class="col-md-8 col-lg-9">
            <select class="form-select" aria-label="Transmission select" name="transmission" id="transmission">
                <option value="">Select Transmission</option>
                <option value="Manual" <?php echo ($car_data['transmission'] == 'manual' ? 'selected' : ''); ?>>Manual</option>
                <option value="Automatic" <?php echo ($car_data['transmission'] == 'automatic' ? 'selected' : ''); ?>>Automatic</option>
            </select>
            <span class="text-danger" id="transmission-error"></span>
        </div>
    </div>

    <!-- Fuel Type -->
    <div class="row mb-3">
        <label for="fuel_type" class="col-md-4 col-lg-3 col-form-label">Fuel Type</label>
        <div class="col-md-8 col-lg-9">
            <select class="form-select" aria-label="Fuel Type select" name="fuel_type" id="fuel_type">
                <option value="">Select Fuel Type</option>
                <option value="Petrol" <?php echo ($car_data['fuel_type'] == 'petrol' ? 'selected' : ''); ?>>Petrol</option>
                <option value="Diesel" <?php echo ($car_data['fuel_type'] == 'diesel' ? 'selected' : ''); ?>>Diesel</option>
                <option value="Electric" <?php echo ($car_data['fuel_type'] == 'electric' ? 'selected' : ''); ?>>Electric</option>
                <option value="Hybrid" <?php echo ($car_data['fuel_type'] == 'hybrid' ? 'selected' : ''); ?>>Hybrid</option>
            </select>
            <span class="text-danger" id="fuel-type-error"></span>
        </div>
    </div>

    <!-- Description -->
    <div class="row mb-3">
        <label for="description" class="col-md-4 col-lg-3 col-form-label">Description</label>
        <div class="col-md-8 col-lg-9">
            <textarea class="form-control" name="description" id="description" rows="4"><?php echo htmlspecialchars($car_data['description']); ?></textarea>
            <span class="text-danger" id="description-error"></span>
        </div>
    </div>

    <!-- Eligible For Finance -->
    <div class="row mb-3">
        <label for="finance_eligible" class="col-md-4 col-lg-3 col-form-label">Eligible For Finance</label>
        <div class="col-md-8 col-lg-9">
            <input type="text" class="form-control" name="finance_eligible" id="finance_eligible" value="<?php echo htmlspecialchars($car_data['finance_eligible']); ?>">
            <span class="text-danger" id="finance-eligible-error"></span>
        </div>
    </div>

    <!-- Used or New -->
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Used or New</label>
        <div class="col-md-8 col-lg-9">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="condition_type" id="used" value="Used" <?php echo ($car_data['condition_type'] == 'Used' ? 'checked' : ''); ?>>
                <label class="form-check-label" for="used">Used</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="condition_type" id="new" value="New" <?php echo ($car_data['condition_type'] == 'New' ? 'checked' : ''); ?>>
                <label class="form-check-label" for="new">New</label>
            </div>
            <span class="text-danger" id="condition-type-error"></span>
        </div>
    </div>

    <!-- Condition -->
    <div class="row mb-3">
        <label for="condition" class="col-md-4 col-lg-3 col-form-label">Condition</label>
        <div class="col-md-8 col-lg-9">
            <select class="form-select" aria-label="Condition select" name="condition" id="condition">
                <option value="">Select Condition</option>
                <option value="Very Poor" <?php echo ($car_data['car_condition'] == 'Very Poor' ? 'selected' : ''); ?>>Very Poor</option>
                <option value="Poor" <?php echo ($car_data['car_condition'] == 'Poor' ? 'selected' : ''); ?>>Poor</option>
                <option value="Fair" <?php echo ($car_data['car_condition'] == 'Fair' ? 'selected' : ''); ?>>Fair</option>
                <option value="Average" <?php echo ($car_data['car_condition'] == 'Average' ? 'selected' : ''); ?>>Average</option>
                <option value="Good" <?php echo ($car_data['car_condition'] == 'Good' ? 'selected' : ''); ?>>Good</option>
                <option value="Excellent" <?php echo ($car_data['car_condition'] == 'Excellent' ? 'selected' : ''); ?>>Excellent</option>
            </select>
            <span class="text-danger" id="condition-error"></span>
        </div>
    </div>
<!-- Visibility Field -->
<div class="row mb-3">
<label class="col-md-4 col-lg-3 col-form-label">Show Car on Website?</label>
    <div class="col-md-8 col-lg-9">        
        <div class="form-check">
            <input class="form-check-input" type="radio" name="visibility" id="visibility_yes" value="Yes" <?php echo ($car_data['visibility'] == 'Yes' ? 'checked' : ''); ?>>
            <label class="form-check-label" for="visibility_yes">Yes</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="visibility" id="visibility_no" value="No" <?php echo ($car_data['visibility'] == 'No' ? 'checked' : ''); ?>>
            <label class="form-check-label" for="visibility_no">No</label>
        </div>
        <span class="text-danger" id="visibility-error"></span>
    </div>
</div>
    <!-- Car Images -->
   <!-- <div class="row mb-3">
        <label for="images" class="col-md-4 col-lg-3 col-form-label">Car Images</label>
        <div class="col-md-8 col-lg-9">
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
            <span class="text-danger" id="images-error"></span>
        </div>
    </div>-->

    <!-- Submit Button -->
    <div class="text-center">
      <input type="hidden" value="<?php echo $car_id ?>" name="car_id">
        <button type="submit" class="btn btn-primary" name="edit_car">Save Changes</button>
    </div>
</form>
  
                </div>

                <div class="tab-pane fade pt-3" id="images_tab">
                  <!-- Change Password Form -->
                  
                  <form method="post" action="process_user">

                    <div class="row mb-3">
                      <?php
                    
                      while($rows = $car_images->fetch())
                      {
                        $imagePath = (string) ($rows['image_url'] ?? '');
                        $imagePath = str_replace('\\', '/', trim($imagePath));
                        while (strpos($imagePath, '../') === 0) {
                          $imagePath = substr($imagePath, 3);
                        }
                        if (strpos($imagePath, 'admin/') === 0) {
                          $imagePath = substr($imagePath, 6);
                        }
                        ?>
                        <div class="col-lg-4">
                          <div class="car-image">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="" class="w-100">
                            <button type="button" class="btn btn-secondary mt-2 delete-car" id="<?php echo $rows['image_id'] ?>">Delete <i class="bi bi-trash"></i></button>
                          </div>
                        </div>
                        <?php
                      }
                      ?>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
  <script src="assets/js/delete_car_image.js"></script>
  <script src="assets/js/editformvalidation.js"></script>

</body>

</html>