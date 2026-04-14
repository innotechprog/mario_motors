<?php
include "assets/classes/connect_db_class.php";
include "assets/classes/auth_class.php";
require_once 'assets/classes/users_class.php';
$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$user = new User($db);
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

  <title>My Profile</title>
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
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="assets/img/male_avatar.png" alt="Profile" class="rounded-circle">
              <h2><?php echo htmlspecialchars($user_data['first_name']).' '.htmlspecialchars($user_data['last_name']); ?></h2>
              <h3><?php echo htmlspecialchars($user_data['job']); ?></h3>
              <div class="social-links mt-2">
                <a href="<?php echo htmlspecialchars($user_data['twitter']); ?>" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="<?php echo htmlspecialchars($user_data['facebook']); ?>" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="<?php echo htmlspecialchars($user_data['instagram']); ?>" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="<?php echo htmlspecialchars($user_data['linkedin']); ?>" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">
              <div class="tab-pane fade show active profile-overview" id="profile-overview">
    <h5 class="card-title">About</h5>
    <p class="small fst-italic"><?php echo htmlspecialchars($user_data['about']) ?></p>

    <h5 class="card-title">Profile Details</h5>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Full Name</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['first_name']).' '.htmlspecialchars($user_data['last_name']); ?></div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Company</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['company']); ?></div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Job</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['company']);?></div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Country</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['country']); ?></div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Address</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['address']); ?></div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Phone</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['phone_number']); ?></div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Email</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user_data['email']); ?></div>
    </div>
</div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="post" id="formData" action="processes/process_user.php">
   <!-- <div class="row mb-3">
        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
        <div class="col-md-8 col-lg-9">
            <img src="<?php echo htmlspecialchars($user_data['profile_image']); ?>" alt="Profile">
            <div class="pt-2">
                <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image" data-bs-toggle="modal" data-bs-target="#uploadImageModal"><i class="bi bi-upload"></i></a>
                <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image" onclick="deleteProfileImage()"><i class="bi bi-trash"></i></a>
            </div>
        </div>
    </div>-->
    <input name="user_id" type="hidden" class="form-control" id="user_id" value="<?php echo htmlspecialchars($user_data['user_id']); ?>">

    <div class="row mb-3">
        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
        <div class="col-md-8 col-lg-9">
            <input name="first_name" type="text" class="form-control" id="first_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
        <div class="col-md-8 col-lg-9">
            <input name="last_name" type="text" class="form-control" id="last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
        <div class="col-md-8 col-lg-9">
            <textarea name="about" class="form-control" id="about" style="height: 100px"><?php echo htmlspecialchars($user_data['about']); ?></textarea>
        </div>
    </div>

    <div class="row mb-3">
        <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
        <div class="col-md-8 col-lg-9">
            <input name="company" type="text" class="form-control" id="company" value="<?php echo htmlspecialchars($user_data['company']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
        <div class="col-md-8 col-lg-9">
            <input name="job" type="text" class="form-control" id="Job" value="<?php echo htmlspecialchars($user_data['job']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
        <div class="col-md-8 col-lg-9">
            <input name="country" type="text" class="form-control" id="Country" value="<?php echo htmlspecialchars($user_data['country']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
        <div class="col-md-8 col-lg-9">
            <input name="address" type="text" class="form-control" id="Address" value="<?php echo htmlspecialchars($user_data['address']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
        <div class="col-md-8 col-lg-9">
            <input name="phone_number" type="text" class="form-control" id="phone_number" value="<?php echo htmlspecialchars($user_data['phone_number']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
        <div class="col-md-8 col-lg-9">
            <input name="email" type="email" class="form-control" id="Email" value="<?php echo htmlspecialchars($user_data['email']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
        <div class="col-md-8 col-lg-9">
            <input name="twitter" type="text" class="form-control" id="Twitter" value="<?php echo htmlspecialchars($user_data['twitter']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
        <div class="col-md-8 col-lg-9">
            <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php echo htmlspecialchars($user_data['facebook']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
        <div class="col-md-8 col-lg-9">
            <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php echo htmlspecialchars($user_data['instagram']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
        <div class="col-md-8 col-lg-9">
            <input name="linkedin" type="text" class="form-control" id="Linkedin" value="<?php echo htmlspecialchars($user_data['linkedin']); ?>">
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
    </div>
</form>
  
                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="post" class="password_form" >
                  <input name="user_id" type="hidden" class="form-control" value="<?php echo htmlspecialchars($user_data['user_id']); ?>">
                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewPassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
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
  <script src="assets/js/validate_change_password.js"></script>
</body>

</html>