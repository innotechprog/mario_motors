<?php
class Message {
    private $conn;
    private $table = 'Messages';

    public $message_id;
    public $sender_id;
    public $receiver_id;
    public $car_id;
    public $message;
    public $sent_at;
    public $is_read;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Send a new message
    public function create() {
        $query = "INSERT INTO {$this->table} (sender_id, receiver_id, car_id, message)
                  VALUES (:sender_id, :receiver_id, :car_id, :message)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':sender_id', $this->sender_id);
        $stmt->bindParam(':receiver_id', $this->receiver_id);
        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->bindParam(':message', $this->message);

        return $stmt->execute();
    }

    // Read messages for a user
    public function readByReceiver() {
        $query = "SELECT * FROM {$this->table} WHERE receiver_id = :receiver_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':receiver_id', $this->receiver_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark a message as read
    public function markAsRead() {
        $query = "UPDATE {$this->table} SET is_read = TRUE WHERE message_id = :message_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':message_id', $this->message_id);
        return $stmt->execute();
    }

    // Delete a message
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE message_id = :message_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':message_id', $this->message_id);
        return $stmt->execute();
    }
}
?>