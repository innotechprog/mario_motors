<?php
include "assets/classes/auth_class.php";
// Include the database connection and User class
require_once 'assets/classes/users_class.php';
require_once 'assets/classes/connect_db_class.php'; // Assuming you have a Database class for connection

// Create a new database connection
$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
// Create a new User object
$auth->logout();

?>