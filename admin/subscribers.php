<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "assets/classes/connect_db_class.php";

// Create database connection
try {
    $database = new Database();
    $db = $database->connect();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Simple authentication check - check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data for header
try {
    $user_stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
    $user_stmt->execute([$_SESSION['user_id']]);
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $user_data = ['first_name' => 'Admin', 'last_name' => 'User', 'job' => 'Administrator'];
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $subscriber_id = $_POST['subscriber_id'] ?? 0;
    
    if ($action === 'toggle_status' && $subscriber_id) {
        try {
            $toggleStmt = $db->prepare("UPDATE car_alerts_subscribers SET is_active = NOT is_active WHERE id = ?");
            $toggleStmt->execute([$subscriber_id]);
            $message = "Subscriber status updated successfully.";
        } catch (Exception $e) {
            $error = "Error updating subscriber: " . $e->getMessage();
        }
    } elseif ($action === 'delete' && $subscriber_id) {
        try {
            $deleteStmt = $db->prepare("DELETE FROM car_alerts_subscribers WHERE id = ?");
            $deleteStmt->execute([$subscriber_id]);
            $message = "Subscriber deleted successfully.";
        } catch (Exception $e) {
            $error = "Error deleting subscriber: " . $e->getMessage();
        }
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=" . urlencode($message ?? $error ?? ''));
    exit();
}

// Get subscribers
try {
    $query = "SELECT * FROM car_alerts_subscribers ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Error fetching subscribers: " . $e->getMessage();
    $subscribers = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Subscribers - Mario Motors & Spare Parts Admin</title>
  <meta content="Manage car alert subscribers" name="description">
  <meta content="subscribers, car alerts, admin" name="keywords">

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
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="user-profile" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Mario Motors & Spare Parts</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/male_avatar.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo substr(htmlspecialchars($user_data['first_name']),0,1).' '.htmlspecialchars($user_data['last_name']); ?></span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($user_data['first_name']).' '.htmlspecialchars($user_data['last_name']); ?></h6>
              <span><?php echo htmlspecialchars($user_data['job']); ?></span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="user-profile">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <!-- ======= Sidebar ======= -->
  <?php include "sidemenu.php"; ?>
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Car Alert Subscribers</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Subscribers</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                <?php if (isset($_GET['msg']) && !empty($_GET['msg'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Car Alert Subscribers</h5>
                        
                        <!-- Statistics Row -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-center bg-success text-white">
                                    <div class="card-body">
                                        <h4><?php echo count(array_filter($subscribers, function($s) { return $s['is_active']; })); ?></h4>
                                        <p class="mb-0">Active Subscribers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-danger text-white">
                                    <div class="card-body">
                                        <h4><?php echo count(array_filter($subscribers, function($s) { return !$s['is_active']; })); ?></h4>
                                        <p class="mb-0">Inactive</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-info text-white">
                                    <div class="card-body">
                                        <h4><?php echo count($subscribers); ?></h4>
                                        <p class="mb-0">Total</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-warning text-white">
                                    <div class="card-body">
                                        <h4><?php echo count(array_filter($subscribers, function($s) { return $s['created_at'] >= date('Y-m-d', strtotime('-7 days')); })); ?></h4>
                                        <p class="mb-0">This Week</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (count($subscribers) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Preferred Make</th>
                                        <th>Price Range</th>
                                        <th>Status</th>
                                        <th>Subscribed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subscribers as $subscriber): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($subscriber['name']); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($subscriber['email']); ?>">
                                                <?php echo htmlspecialchars($subscriber['email']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo $subscriber['preferred_make'] ?: 'Any'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php 
                                                $price_range = $subscriber['price_range'];
                                                switch($price_range) {
                                                    case '0-100000': echo 'Under R100k'; break;
                                                    case '100000-200000': echo 'R100k - R200k'; break;
                                                    case '200000-300000': echo 'R200k - R300k'; break;
                                                    case '300000-500000': echo 'R300k - R500k'; break;
                                                    case '500000+': echo 'R500k+'; break;
                                                    default: echo 'Any';
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($subscriber['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M j, Y', strtotime($subscriber['created_at'])); ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="subscriber_id" value="<?php echo $subscriber['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo $subscriber['is_active'] ? 'warning' : 'success'; ?>">
                                                        <?php echo $subscriber['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subscriber?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="subscriber_id" value="<?php echo $subscriber['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <h6>No subscribers yet!</h6>
                            <p class="mb-0">When customers subscribe to car alerts on your website, they will appear here.</p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="alert alert-light mt-4">
                            <h6><i class="bi bi-info-circle"></i> How it works:</h6>
                            <ul class="mb-0">
                                <li>Customers subscribe on your website for car alerts</li>
                                <li>They receive email notifications when new cars matching their preferences are added</li>
                                <li>Alerts are sent automatically when you add cars through this admin panel</li>
                                <li>You can manage subscriber status and preferences here</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>
</html>