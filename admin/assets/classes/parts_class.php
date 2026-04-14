<?php
class Part {
    private $conn;
    private $table = 'parts';
    private $imagesTable = 'part_images';

    public $part_id;
    public $seller_id;
    public $year;
    public $mm_code;
    public $make;
    public $model;
    public $variant;
    public $part_name;
    public $part_number;
    public $category;
    public $condition_type;
    public $part_condition;
    public $quantity;
    public $price;
    public $description;
    public $status;
    public $visibility;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function ensureTables(): bool {
        $partsSql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            part_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            seller_id INT UNSIGNED NOT NULL,
            year SMALLINT NULL,
            mm_code VARCHAR(100) NULL,
            make VARCHAR(100) NOT NULL,
            model VARCHAR(100) NULL,
            variant VARCHAR(100) NULL,
            part_name VARCHAR(255) NOT NULL,
            part_number VARCHAR(120) NULL,
            category VARCHAR(100) NOT NULL,
            condition_type VARCHAR(50) NOT NULL DEFAULT 'Used',
            part_condition VARCHAR(50) NULL,
            quantity INT UNSIGNED NOT NULL DEFAULT 1,
            price DECIMAL(12,2) NOT NULL DEFAULT 0,
            description TEXT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'Available',
            visibility VARCHAR(10) NOT NULL DEFAULT 'Yes',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_parts_seller (seller_id),
            INDEX idx_parts_make_model (make, model),
            INDEX idx_parts_category (category)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $imagesSql = "CREATE TABLE IF NOT EXISTS {$this->imagesTable} (
            image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            part_id INT UNSIGNED NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            is_primary TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_part_images_part (part_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        return $this->conn->exec($partsSql) !== false && $this->conn->exec($imagesSql) !== false;
    }

    public function setPartId($part_id) { $this->part_id = $part_id; }
    public function getPartId() { return $this->part_id; }
    public function setSellerId($seller_id) { $this->seller_id = $seller_id; }
    public function setYear($year) { $this->year = $year; }
    public function setMmCode($mm_code) { $this->mm_code = $mm_code; }
    public function setMake($make) { $this->make = $make; }
    public function setModel($model) { $this->model = $model; }
    public function setVariant($variant) { $this->variant = $variant; }
    public function setPartName($part_name) { $this->part_name = $part_name; }
    public function setPartNumber($part_number) { $this->part_number = $part_number; }
    public function setCategory($category) { $this->category = $category; }
    public function setConditionType($condition_type) { $this->condition_type = $condition_type; }
    public function setPartCondition($part_condition) { $this->part_condition = $part_condition; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setPrice($price) { $this->price = $price; }
    public function setDescription($description) { $this->description = $description; }
    public function setStatus($status) { $this->status = $status; }
    public function setVisibility($visibility) { $this->visibility = $visibility; }

    public function create() {
        $query = "INSERT INTO {$this->table}
            (seller_id, year, mm_code, make, model, variant, part_name, part_number, category, condition_type, part_condition, quantity, price, description, status, visibility)
            VALUES
            (:seller_id, :year, :mm_code, :make, :model, :variant, :part_name, :part_number, :category, :condition_type, :part_condition, :quantity, :price, :description, :status, :visibility)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':seller_id', $this->seller_id);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':mm_code', $this->mm_code);
        $stmt->bindParam(':make', $this->make);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':variant', $this->variant);
        $stmt->bindParam(':part_name', $this->part_name);
        $stmt->bindParam(':part_number', $this->part_number);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':condition_type', $this->condition_type);
        $stmt->bindParam(':part_condition', $this->part_condition);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':visibility', $this->visibility);

        if ($stmt->execute()) {
            $this->part_id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function addImage($image_url, $is_primary = 0): bool {
        $query = "INSERT INTO {$this->imagesTable} (part_id, image_url, is_primary) VALUES (:part_id, :image_url, :is_primary)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':is_primary', $is_primary);

        return $stmt->execute();
    }

    public function readAll(): array {
        $query = "SELECT p.*, COALESCE(
                    (SELECT pi.image_url FROM {$this->imagesTable} pi WHERE pi.part_id = p.part_id AND pi.is_primary = 1 LIMIT 1),
                    (SELECT pi2.image_url FROM {$this->imagesTable} pi2 WHERE pi2.part_id = p.part_id ORDER BY pi2.image_id ASC LIMIT 1)
                 ) AS image_url
                 FROM {$this->table} p
                 ORDER BY p.part_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne(): ?array {
        $query = "SELECT * FROM {$this->table} WHERE part_id = :part_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function readOneBySeller($sellerId): ?array {
        $query = "SELECT * FROM {$this->table} WHERE part_id = :part_id AND seller_id = :seller_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->bindParam(':seller_id', $sellerId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function readImages(): array {
        $query = "SELECT * FROM {$this->imagesTable} WHERE part_id = :part_id ORDER BY is_primary DESC, image_id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(): bool {
        $query = "UPDATE {$this->table}
                  SET year = :year,
                      mm_code = :mm_code,
                      make = :make,
                      model = :model,
                      variant = :variant,
                      part_name = :part_name,
                      part_number = :part_number,
                      category = :category,
                      condition_type = :condition_type,
                      part_condition = :part_condition,
                      quantity = :quantity,
                      price = :price,
                      description = :description,
                      status = :status,
                      visibility = :visibility
                  WHERE part_id = :part_id AND seller_id = :seller_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':mm_code', $this->mm_code);
        $stmt->bindParam(':make', $this->make);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':variant', $this->variant);
        $stmt->bindParam(':part_name', $this->part_name);
        $stmt->bindParam(':part_number', $this->part_number);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':condition_type', $this->condition_type);
        $stmt->bindParam(':part_condition', $this->part_condition);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':visibility', $this->visibility);
        $stmt->bindParam(':part_id', $this->part_id);
        $stmt->bindParam(':seller_id', $this->seller_id);

        return $stmt->execute();
    }

    public function deleteImageByIdForSeller($imageId, $sellerId): bool {
        $query = "SELECT pi.image_id, pi.part_id, pi.image_url, pi.is_primary
                  FROM {$this->imagesTable} pi
                  INNER JOIN {$this->table} p ON p.part_id = pi.part_id
                  WHERE pi.image_id = :image_id AND p.seller_id = :seller_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':image_id', $imageId);
        $stmt->bindParam(':seller_id', $sellerId);
        $stmt->execute();

        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$image) {
            return false;
        }

        $deleteStmt = $this->conn->prepare("DELETE FROM {$this->imagesTable} WHERE image_id = :image_id");
        $deleteStmt->bindParam(':image_id', $imageId);
        $deleted = $deleteStmt->execute();

        if (!$deleted) {
            return false;
        }

        $path = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $image['image_url']);
        if (file_exists($path)) {
            @unlink($path);
        }

        if ((int) $image['is_primary'] === 1) {
            $nextPrimaryStmt = $this->conn->prepare("SELECT image_id FROM {$this->imagesTable} WHERE part_id = :part_id ORDER BY image_id ASC LIMIT 1");
            $nextPrimaryStmt->bindParam(':part_id', $image['part_id']);
            $nextPrimaryStmt->execute();
            $nextPrimary = $nextPrimaryStmt->fetch(PDO::FETCH_ASSOC);

            if ($nextPrimary) {
                $setPrimaryStmt = $this->conn->prepare("UPDATE {$this->imagesTable} SET is_primary = 1 WHERE image_id = :image_id");
                $setPrimaryStmt->bindParam(':image_id', $nextPrimary['image_id']);
                $setPrimaryStmt->execute();
            }
        }

        return true;
    }

    public function delete(): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE part_id = :part_id");
        $stmt->bindParam(':part_id', $this->part_id);

        return $stmt->execute();
    }

    public function deletePartImagesFolder($partId): void {
        $stmt = $this->conn->prepare("SELECT image_url FROM {$this->imagesTable} WHERE part_id = :part_id");
        $stmt->bindParam(':part_id', $partId);
        $stmt->execute();

        while ($img = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $path = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $img['image_url']);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $folderPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'parts' . DIRECTORY_SEPARATOR . 'part_' . $partId;
        if (is_dir($folderPath)) {
            @rmdir($folderPath);
        }
    }
}
?>
