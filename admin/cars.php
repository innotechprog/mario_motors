<?php
include "assets/classes/connect_db_class.php";
include "assets/classes/auth_class.php";
require_once 'assets/classes/users_class.php';
include "assets/classes/cars_class.php";
$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$car = new Car($db);
$user = new User($db);
// Require the user to be logged in
$auth->requireLogin();
// User is authenticated, proceed
$user_id = $_SESSION['user_id'];
$user->setUserId($user_id);
$user_data = $user->read($user_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mario Motors & Spare Parts | Cars</title>
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
      <h1>Cars</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
          <li class="breadcrumb-item active">Cars</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
            <a href="add-car" class="btn btn-secondary mt-4">Add a Car</a>

              <!-- Table with stripped rows -->
              <table class="table datatable">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Price</th>
            <th>Mileage</th>
            <th>Date Added</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
      <?php
    $cars = $car->readAll(); // Fetch all cars

foreach ($cars as $carData) {
    $carObject = new Car($db); // Assuming Car is your class
    $carObject->setSellerId($carData['seller_id']);
    $carObject->setMake($carData['make']);
    $carObject->setModel($carData['model']);
    $carObject->setYear($carData['year']);
    $carObject->setPrice($carData['price']);
    $carObject->setMileage($carData['mileage']);
    $carObject->setCreatedAt($carData['created_at']);
    $carObject->setCarId($carData['car_id']);
    
   echo "<tr class='car-row'>
        <td>{$carObject->getMake()}</td>
        <td>{$carObject->getModel()}</td>
        <td>{$carObject->getYear()}</td>
        <td>{$carObject->getPrice()}</td>
        <td>{$carObject->getMileage()}</td>
        <td>{$carObject->getCreatedAt()}</td>
        <td>
            <a href='edit-car?id={$carObject->getCarId()}' class='edit-btn'>
                <i class='bi bi-pencil'></i>
            </a>
            <button class='delete-btn delete-car' id='{$carData['car_id']}'>Delete</button>
        </td>
    </tr>";

}
?>
    </tbody>
</table>      <!-- End Table with stripped rows -->

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
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/delete_car.js"></script>

</body>

</html>