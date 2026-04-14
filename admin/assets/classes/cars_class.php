<?php
class Car {
    private $conn;
    private $table = 'cars';

    // Attributes
    public $car_id;
    public $seller_id;
    public $year;
    public $mm_code;
    public $make;
    public $model;
    public $variant;
    public $custom_variant;
    public $vin;
    public $mileage;
    public $price;
    public $color;
    public $transmission;
    public $fuel_type;
    public $description;
    public $finance_eligible;
    public $condition_type;
    public $condition;
    public $status;
    public $created_at;
    private $visibility;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Setters
    public function setCarId($car_id) { $this->car_id = $car_id; }
    public function setSellerId($seller_id) { $this->seller_id = $seller_id; }
    public function setYear($year) { $this->year = $year; }
    public function setMmCode($mm_code) { $this->mm_code = $mm_code; }
    public function setMake($make) { $this->make = $make; }
    public function setModel($model) { $this->model = $model; }
    public function setVariant($variant) { $this->variant = $variant; }
    public function setCustomVariant($custom_variant) { $this->custom_variant = $custom_variant; }
    public function setVin($vin) { $this->vin = $vin; }
    public function setMileage($mileage) { $this->mileage = $mileage; }
    public function setPrice($price) { $this->price = $price; }
    public function setColor($color) { $this->color = $color; }
    public function setTransmission($transmission) { $this->transmission = $transmission; }
    public function setFuelType($fuel_type) { $this->fuel_type = $fuel_type; }
    public function setDescription($description) { $this->description = $description; }
    public function setFinanceEligible($finance_eligible) { $this->finance_eligible = $finance_eligible; }
    public function setConditionType($condition_type) { $this->condition_type = $condition_type; }
    public function setCondition($condition) { $this->condition = $condition; }
    public function setStatus($status) { $this->status = $status; }
    public function setVisibility($visibility){$this->visibility = $visibility;}
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    // Getters
    public function getCarId() { return $this->car_id; }
    public function getSellerId() { return $this->seller_id; }
    public function getYear() { return $this->year; }
    public function getMmCode() { return $this->mm_code; }
    public function getMake() { return $this->make; }
    public function getModel() { return $this->model; }
    public function getVariant() { return $this->variant; }
    public function getCustomVariant() { return $this->custom_variant; }
    public function getVin() { return $this->vin; }
    public function getMileage() { return $this->mileage; }
    public function getPrice() { return $this->price; }
    public function getColor() { return $this->color; }
    public function getTransmission() { return $this->transmission; }
    public function getFuelType() { return $this->fuel_type; }
    public function getDescription() { return $this->description; }
    public function getFinanceEligible() { return $this->finance_eligible; }
    public function getConditionType() { return $this->condition_type; }
    public function getCondition() { return $this->condition; }
    public function getStatus() { return $this->status; }
    public function getVisibility(){ return $this->visibility;}
    public function getCreatedAt() { return $this->created_at; }

    // Create a new car listing
    public function create() {
        $query = "INSERT INTO {$this->table} 
                  (seller_id, year, mm_code, make, model, variant, custom_variant, vin, mileage, price, color, transmission, fuel_type, description, finance_eligible, condition_type, car_condition, status,visibility) 
                  VALUES 
                  (:seller_id, :year, :mm_code, :make, :model, :variant, :custom_variant, :vin, :mileage, :price, :color, :transmission, :fuel_type, :description, :finance_eligible, :condition_type, :condition, :status, :visibility)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':seller_id', $this->seller_id);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':mm_code', $this->mm_code);
        $stmt->bindParam(':make', $this->make);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':variant', $this->variant);
        $stmt->bindParam(':custom_variant', $this->custom_variant);
        $stmt->bindParam(':vin', $this->vin);
        $stmt->bindParam(':mileage', $this->mileage);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':transmission', $this->transmission);
        $stmt->bindParam(':fuel_type', $this->fuel_type);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':finance_eligible', $this->finance_eligible);
        $stmt->bindParam(':condition_type', $this->condition_type);
        $stmt->bindParam(':condition', $this->condition);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':visibility', $this->visibility);

        if ($stmt->execute()) {
            // Retrieve the auto-generated car_id
            $this->car_id = $this->conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    // Read a car by ID 
    public function read() {
        $query = "SELECT c.car_id, `seller_id`, `make`, `model`, `year`, `mileage`, `price`, `color`, `transmission`, `fuel_type`, `description`, `status`, `created_at`, `mm_code`, `variant`, `custom_variant`, `vin`, `finance_eligible`, `condition_type`, `car_condition`,`visibility`,
         COALESCE(
            (SELECT img2.image_url FROM images img2 WHERE img2.car_id = c.car_id AND img2.is_primary = 1 LIMIT 1),
            (SELECT img3.image_url FROM images img3 WHERE img3.car_id = c.car_id ORDER BY img3.image_id ASC LIMIT 1)
         ) AS image_url
         FROM {$this->table} c
         WHERE c.car_id = :car_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->execute();
        return $stmt;
    }

    public function readAll() {
        $query = "SELECT c.car_id, `seller_id`, `make`, `model`, `year`, `mileage`, `price`, `color`, `transmission`, `fuel_type`, `description`, `status`, `created_at`, `mm_code`, `variant`, `custom_variant`, `vin`, `finance_eligible`, `condition_type`, `car_condition`
                 FROM {$this->table} c order by c.car_id desc";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Update a car listing
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET 
                    seller_id = :seller_id, 
                    year = :year, 
                    mm_code = :mm_code, 
                    make = :make, 
                    model = :model, 
                    variant = :variant, 
                    custom_variant = :custom_variant, 
                    vin = :vin, 
                    mileage = :mileage, 
                    price = :price, 
                    color = :color, 
                    transmission = :transmission, 
                    fuel_type = :fuel_type, 
                    description = :description, 
                    finance_eligible = :finance_eligible, 
                    condition_type = :condition_type, 
                    car_condition = :condition, 
                    status = :status,
                    visibility = :visibility
                  WHERE car_id = :car_id";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':seller_id', $this->seller_id);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':mm_code', $this->mm_code);
        $stmt->bindParam(':make', $this->make);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':variant', $this->variant);
        $stmt->bindParam(':custom_variant', $this->custom_variant);
        $stmt->bindParam(':vin', $this->vin);
        $stmt->bindParam(':mileage', $this->mileage);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':transmission', $this->transmission);
        $stmt->bindParam(':fuel_type', $this->fuel_type);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':finance_eligible', $this->finance_eligible);
        $stmt->bindParam(':condition_type', $this->condition_type);
        $stmt->bindParam(':condition', $this->condition);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':visibility', $this->visibility);
        $stmt->bindParam(':car_id', $this->car_id);

        return $stmt->execute();
    }

    // Delete a car listing
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE car_id = :car_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':car_id', $this->car_id);
        return $stmt->execute();
    }
    function calculateMonthlyRepayment($loanAmount, $annualInterestRate = 11.72, $months = 72) {
        $r = ($annualInterestRate / 100) / 12; // Convert annual interest to monthly decimal
    
        // Avoid division by zero in case of zero interest rate
        if ($r == 0) {
            return $loanAmount / $months;
        }
    
        $monthlyPayment = ($loanAmount * $r * pow(1 + $r, $months)) / (pow(1 + $r, $months) - 1);
    
        return (float) $monthlyPayment; // Ensure it returns a float
    }
}
?>