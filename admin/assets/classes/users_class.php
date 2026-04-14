<?php
class User {
    private $conn;
    private $table = 'users';

    // Properties
    public $user_id;
    public $username;
    public $password_hash;
    public $email;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $role;
    public $created_at;

    // New properties
    public $profile_image;
    public $about;
    public $company;
    public $job;
    public $country;
    public $address;
    public $twitter;
    public $facebook;
    public $instagram;
    public $linkedin;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    public function create() {
        $query = "INSERT INTO {$this->table} (
            username, password_hash, email, first_name, last_name, phone_number, role, profile_image, about, company, job, country, address, twitter, facebook, instagram, linkedin
        ) VALUES (
            :username, :password_hash, :email, :first_name, :last_name, :phone_number, :role, :profile_image, :about, :company, :job, :country, :address, :twitter, :facebook, :instagram, :linkedin
        )";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password_hash', $this->password_hash);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':profile_image', $this->profile_image);
        $stmt->bindParam(':about', $this->about);
        $stmt->bindParam(':company', $this->company);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':twitter', $this->twitter);
        $stmt->bindParam(':facebook', $this->facebook);
        $stmt->bindParam(':instagram', $this->instagram);
        $stmt->bindParam(':linkedin', $this->linkedin);

        return $stmt->execute();
    }

    // Read a user by ID
    public function read() {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a user
    public function update() {
        $query = "UPDATE {$this->table} SET 
            email = :email, 
            first_name = :first_name, 
            last_name = :last_name, 
            phone_number = :phone_number, 
            about = :about, 
            company = :company, 
            job = :job, 
            country = :country, 
            address = :address, 
            twitter = :twitter, 
            facebook = :facebook, 
            instagram = :instagram, 
            linkedin = :linkedin 
        WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
       // $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':about', $this->about);
        $stmt->bindParam(':company', $this->company);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':twitter', $this->twitter);
        $stmt->bindParam(':facebook', $this->facebook);
        $stmt->bindParam(':instagram', $this->instagram);
        $stmt->bindParam(':linkedin', $this->linkedin);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }

    public function updatePassword(){
        $query = "UPDATE {$this->table} SET 
           
            password_hash = :password_hash 
        WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password_hash', $this->password_hash);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }
    // Delete a user
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }

    // Login a user
    public function login($username, $password) {
        $query = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function loginByEmail($email, $password) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    public function getByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Setters
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setUsername($username) { $this->username = $username; }
    public function setPasswordHash($password_hash) { $this->password_hash = $password_hash; }
    public function setEmail($email) { $this->email = $email; }
    public function setFirstName($first_name) { $this->first_name = $first_name; }
    public function setLastName($last_name) { $this->last_name = $last_name; }
    public function setPhoneNumber($phone_number) { $this->phone_number = $phone_number; }
    public function setRole($role) { $this->role = $role; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    // New setters
    public function setProfileImage($profile_image) { $this->profile_image = $profile_image; }
    public function setAbout($about) { $this->about = $about; }
    public function setCompany($company) { $this->company = $company; }
    public function setJob($job) { $this->job = $job; }
    public function setCountry($country) { $this->country = $country; }
    public function setAddress($address) { $this->address = $address; }
    public function setTwitter($twitter) { $this->twitter = $twitter; }
    public function setFacebook($facebook) { $this->facebook = $facebook; }
    public function setInstagram($instagram) { $this->instagram = $instagram; }
    public function setLinkedin($linkedin) { $this->linkedin = $linkedin; }

    // Getters
    public function getUserId() { return $this->user_id; }
    public function getUsername() { return $this->username; }
    public function getPasswordHash() { return $this->password_hash; }
    public function getEmail() { return $this->email; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getPhoneNumber() { return $this->phone_number; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }

    // New getters
    public function getProfileImage() { return $this->profile_image; }
    public function getAbout() { return $this->about; }
    public function getCompany() { return $this->company; }
    public function getJob() { return $this->job; }
    public function getCountry() { return $this->country; }
    public function getAddress() { return $this->address; }
    public function getTwitter() { return $this->twitter; }
    public function getFacebook() { return $this->facebook; }
    public function getInstagram() { return $this->instagram; }
    public function getLinkedin() { return $this->linkedin; }
}
?>