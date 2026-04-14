<?php
class Favorite {
    private $conn;
    private $table = 'favorites';

    public $favorite_id;
    public $user_id;
    public $car_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a car to favorites
    public function create() {
        $query = "INSERT INTO {$this->table} (user_id, car_id) VALUES (:user_id, :car_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':car_id', $this->car_id);

        return $stmt->execute();
    }

    // Remove a car from favorites
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE user_id = :user_id AND car_id = :car_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':car_id', $this->car_id);

        return $stmt->execute();
    }

    // Get all favorites for a user
    public function readByUser() {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>