<?php
class Review {
    private $conn;
    private $table = 'Reviews';

    public $review_id;
    public $reviewer_id;
    public $reviewee_id;
    public $car_id;
    public $rating;
    public $comment;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new review
    public function create() {
        $query = "INSERT INTO {$this->table} (reviewer_id, reviewee_id, car_id, rating, comment)
                  VALUES (:reviewer_id, :reviewee_id, :car_id, :rating, :comment)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':reviewer_id', $this->reviewer_id);
        $stmt->bindParam(':reviewee_id', $this->reviewee_id);
        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':comment', $this->comment);

        return $stmt->execute();
    }

    // Read reviews for a user
    public function readByReviewee() {
        $query = "SELECT * FROM {$this->table} WHERE reviewee_id = :reviewee_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reviewee_id', $this->reviewee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a review
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE review_id = :review_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':review_id', $this->review_id);
        return $stmt->execute();
    }
}
?>