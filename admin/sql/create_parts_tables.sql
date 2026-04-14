CREATE TABLE IF NOT EXISTS parts (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS part_images (
    image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    part_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_part_images_part (part_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
