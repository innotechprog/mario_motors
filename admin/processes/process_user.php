<?php
header('Content-Type: application/json'); // Set JSON response header
// Include the database connection and User class
require_once '../assets/classes/connect_db_class.php'; // Assuming this file contains your PDO database connection
require_once '../assets/classes/users_class.php'; // Include the User class
include "../assets/classes/auth_class.php";
$database = new Database();
$db = $database->connect();
// Initialize the User class with the database connection
$user = new User($db);
$auth = new Auth($db);
// Require the user to be logged in
$auth->requireLogin();

// User is authenticated, proceed
$user_id = $_SESSION['user_id'];
$user->setUserId($user_id);
$user_data = $user->read($user_id);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   

    // Check if it's an update or a new user creation
    if (!empty($_POST['user_id']) && isset($_POST['update_profile'])) {
         // Assign form data to User object properties
   // $user->setUsername($_POST['username']);
    $user->setEmail($_POST['email']); 
    $user->setFirstName($_POST['first_name']);
    $user->setLastName($_POST['last_name']);
    $user->setPhoneNumber($_POST['phone_number']);
    //$user->setRole($_POST['role']);
   // $user->setProfileImage($_POST['profile_image']);
    $user->setAbout($_POST['about']);
    $user->setCompany($_POST['company']);
    $user->setJob($_POST['job']);
    $user->setCountry($_POST['country']);
    $user->setAddress($_POST['address']);
    $user->setTwitter($_POST['twitter']);
    $user->setFacebook($_POST['facebook']);
    $user->setInstagram($_POST['instagram']);
    $user->setLinkedin($_POST['linkedin']);

        // Update existing user
        $user->setUserId($_POST['user_id']);
        if ($user->update()) {
            //echo $_POST['user_id']."User updated successfully!";
           header("Location:../user-profile");
        } else {
            echo "Failed to update user.";
        }
    }
    else if (!empty($_POST['user_id']) && isset($_POST['password'])) {
        $current_password = $_POST['password'] ?? '';
        $new_password = $_POST['newPassword'] ?? '';
        $re_new_password = $_POST['renewPassword'] ?? '';
    
        $old_password_hash = $user_data['password_hash'];
    
        // Verify the current password
        if (password_verify($current_password, $old_password_hash)) {
            if ($new_password === $re_new_password) {
                // Hash and update new password
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $user->setPasswordHash($password_hash);
                $user->updatePassword();
                
                echo json_encode(["success" => true, "message" => "Password updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "New passwords do not match."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Old password is incorrect."]);
        }
        exit;
    }    
     else {        
        // Create new user
        // Hash the password before saving (assuming password is provided in the form)
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->setPasswordHash($password_hash);

        if ($user->create()) {
            echo "User created successfully!";
        } else {
            echo "Failed to create user.";
        }
    }
} else {
    echo "Invalid request method.";
}
?>