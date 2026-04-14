<?php
class Image {
    private $conn;
    private $table = 'images';

    public $image_id;
    public $car_id;
    public $image_url;
    public $is_primary;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add an image for a car
    public function create() {
        $query = "INSERT INTO {$this->table} (car_id, image_url, is_primary) VALUES (:car_id, :image_url, :is_primary)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':is_primary', $this->is_primary);

        return $stmt->execute();
    }

    // Read images for a car
    public function readByCar() {
        $query = "SELECT * FROM {$this->table} WHERE car_id = :car_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->execute();
        return $stmt;
    }

    // Delete an image
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE image_id = :image_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':image_id', $this->image_id);
        return $stmt->execute();
    }
    function deleteCarImage($carId) {
        // Step 1: Fetch the image URL from the database
        $folderPath = "../img/cars/".$carId;
        $stmt = $this->conn->prepare("SELECT image_url FROM {$this->table} WHERE car_id = :car_id");
        $stmt->bindParam(':car_id', $carId);
        $stmt->execute();

        while($car = $stmt->fetch()){
              if ($car && !empty($car['image_url'])) {
            $imagePath = $car['image_url']; // Get the image path from the database
             
            // Step 2: Delete the image file if it exists
            if (file_exists($imagePath)) {
                if (unlink($imagePath)) { // Delete the image file
                   //return "Image deleted successfully.";
                } else {
                // return "Failed to delete the image file.";
                }
            } else {
                //return "Image file does not exist.";
            }
        } else {
            //return "Car not found or no image associated with this car.";
        }
    }
    if (rmdir($folderPath)) {
        return "Folder and its contents deleted successfully.";
    } else {
        return "Failed to delete the folder.";
    }
    }
    
     // Delete an image by car
     public function deleteByCarId() {
        $query = "DELETE FROM {$this->table} WHERE car_id = :car_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':car_id', $this->car_id);
        return $stmt->execute();
    }

    // Setters
    public function setImageId($image_id) { $this->image_id = $image_id; }
    public function setCarId($car_id) { $this->car_id = $car_id; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    public function setIsPrimary($is_primary) { $this->is_primary = $is_primary; }

    // Getters
    public function getImageId() { return $this->image_id; }
    public function getCarId() { return $this->car_id; }
    public function getImageUrl() { return $this->image_url; }
    public function getIsPrimary() { return $this->is_primary; }
}
?>