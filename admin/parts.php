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
$parts = $part->readAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Mario Motors & Spare Parts | Parts</title>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; include 'sidemenu.php'; ?>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Parts</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="user-profile">Home</a></li>
        <li class="breadcrumb-item active">Parts</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <a href="add-parts" class="btn btn-secondary mt-4">Add Parts</a>
            <table class="table datatable mt-3">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Make</th>
                  <th>Model</th>
                  <th>Year</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Date Added</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($parts as $partData): ?>
                  <tr class="part-row">
                    <td><?php echo htmlspecialchars($partData['part_name']); ?></td>
                    <td><?php echo htmlspecialchars($partData['category']); ?></td>
                    <td><?php echo htmlspecialchars($partData['make']); ?></td>
                    <td><?php echo htmlspecialchars($partData['model'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars((string) ($partData['year'] ?? '')); ?></td>
                    <td><?php echo 'R ' . number_format((float) $partData['price'], 2); ?></td>
                    <td><?php echo (int) $partData['quantity']; ?></td>
                    <td><?php echo htmlspecialchars($partData['created_at']); ?></td>
                    <td>
                      <a href="edit-part?id=<?php echo (int) $partData['part_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                      <button class="delete-btn delete-part" id="<?php echo (int) $partData['part_id']; ?>">Delete</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/delete_part.js"></script>
</body>
</html>
