<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$car = find_car($id);

if ($car === null) {
    $pageTitle = 'Car Not Found';
    $currentNav = 'cars';
    layout_start(compact('pageTitle', 'currentNav'));
    require VIEW_PATH . '/pages/car-missing.php';
    layout_end();
    exit;
}

$pageTitle = (string) $car['name'];
$currentNav = 'cars';
$img = car_primary_image_url($car);
$images = car_image_urls((int) $car['id'], $img);

// Meta description for this car
$addrCfg = config('business_address');
$city = is_array($addrCfg) ? trim((string) ($addrCfg['city'] ?? '')) : '';
$metaDescription = trim(implode(' — ', array_filter([
    (string) $car['name'],
    $car['year'] > 0 ? (string) $car['year'] : '',
    (string) $car['price'],
    $city,
])));
$specs = [];

$pushSpec = static function (string $label, mixed $value, string $icon) use (&$specs): void {
    $v = trim((string) $value);
    if ($v === '' || strtolower($v) === 'n/a') {
        return;
    }
    $specs[] = ['label' => $label, 'value' => $v, 'icon' => $icon];
};

$pushSpec('Year', $car['year'] ?? '', '📅');
$pushSpec('Brand', $car['brand'] ?? '', '🏷');
$pushSpec('Model', $car['model'] ?? '', '🚘');
$pushSpec('Variant', $car['variant'] ?? '', '🧩');
$pushSpec('Mileage', $car['mileage'] ?? '', '⏲');
$pushSpec('Transmission', $car['transmission'] ?? '', '⚙');
$pushSpec('Fuel', $car['fuelType'] ?? '', '⛽');
$pushSpec('Engine', $car['engine'] ?? '', '🔧');
$pushSpec('Horsepower', $car['horsepower'] ?? '', '🐎');
$pushSpec('Color', $car['color'] ?? '', '🎨');
$pushSpec('Condition Type', $car['category'] ?? '', '📌');
$pushSpec('Condition', $car['condition'] ?? '', '✅');
$pushSpec('Finance Eligible', $car['financeEligible'] ?? '', '💳');
$pushSpec('Status', $car['status'] ?? '', '📈');
$pushSpec('MM Code', $car['mmCode'] ?? '', '🔢');
$pushSpec('VIN', $car['vin'] ?? '', '🆔');

layout_start(compact('pageTitle', 'currentNav', 'metaDescription', 'car', 'img', 'images', 'specs'));
require VIEW_PATH . '/pages/car-show.php';
layout_end();
