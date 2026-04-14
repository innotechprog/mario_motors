<?php
class Order {
    private $conn;
    private $table = 'Orders';

    public $order_id;
    public $car_id;
    public $buyer_id;
    public $seller_id;
    public $order_date;
    public $total_price;
    public $payment_status;
    public $payment_method;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new order
    public function create() {
        $query = "INSERT INTO {$this->table} (car_id, buyer_id, seller_id, total_price, payment_status, payment_method)
                  VALUES (:car_id, :buyer_id, :seller_id, :total_price, :payment_status, :payment_method)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->bindParam(':buyer_id', $this->buyer_id);
        $stmt->bindParam(':seller_id', $this->seller_id);
        $stmt->bindParam(':total_price', $this->total_price);
        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':payment_method', $this->payment_method);

        return $stmt->execute();
    }

    // Read an order by ID
    public function read() {
        $query = "SELECT * FROM {$this->table} WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update payment status
    public function updatePaymentStatus() {
        $query = "UPDATE {$this->table} SET payment_status = :payment_status WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':order_id', $this->order_id);

        return $stmt->execute();
    }

    // Delete an order
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $this->order_id);
        return $stmt->execute();
    }
}
?>