<?php

declare(strict_types=1);

$defaultCars = [
    [
        'id' => 1, 'brand' => 'Toyota', 'model' => 'Camry XSE', 'name' => 'Toyota Camry XSE', 'year' => 2024,
        'price' => 'R 529 900', 'mileage' => '15,200 mi', 'engine' => '2.5L 4-Cylinder', 'transmission' => '8-Speed Automatic',
        'fuelType' => 'Gasoline', 'horsepower' => '203 HP', 'category' => 'Sedan',
        'description' => 'The Toyota Camry XSE delivers a perfect blend of sportiness and reliability. Featuring a bold mesh grille, dual exhaust tips, and 19-inch alloy wheels. Inside, enjoy a leather-trimmed interior with heated front seats, JBL premium audio, and a 9-inch touchscreen with wireless Apple CarPlay.',
    ],
    [
        'id' => 2, 'brand' => 'Ford', 'model' => 'Mustang GT', 'name' => 'Ford Mustang GT', 'year' => 2024,
        'price' => 'R 789 900', 'mileage' => '8,400 mi', 'engine' => '5.0L V8', 'transmission' => '6-Speed Manual',
        'fuelType' => 'Gasoline', 'horsepower' => '480 HP', 'category' => 'Coupe',
        'description' => 'The iconic Ford Mustang GT delivers raw American muscle with its thundering 5.0L V8 engine. This pony car features performance-tuned suspension, Brembo front brakes, and available MagneRide active suspension for track-ready handling.',
    ],
    [
        'id' => 3, 'brand' => 'Honda', 'model' => 'CR-V Hybrid', 'name' => 'Honda CR-V Hybrid', 'year' => 2024,
        'price' => 'R 679 900', 'mileage' => '5,100 mi', 'engine' => '2.0L Hybrid', 'transmission' => 'CVT',
        'fuelType' => 'Hybrid', 'horsepower' => '204 HP', 'category' => 'SUV',
        'description' => 'The Honda CR-V Hybrid combines practicality with impressive fuel economy. This compact SUV offers a spacious interior with 76.5 cubic feet of cargo space, a 9-inch touchscreen infotainment system, and Honda Sensing suite of safety features.',
    ],
    [
        'id' => 4, 'brand' => 'BMW', 'model' => '330i', 'name' => 'BMW 330i', 'year' => 2023,
        'price' => 'R 869 900', 'mileage' => '12,800 mi', 'engine' => '2.0L Turbo 4-Cylinder', 'transmission' => '8-Speed Automatic',
        'fuelType' => 'Gasoline', 'horsepower' => '255 HP', 'category' => 'Sedan',
        'description' => 'The BMW 3 Series remains the benchmark for sport sedans. With its perfectly balanced chassis, responsive turbocharged engine, and luxurious interior featuring a 14.9-inch curved display, this 330i delivers an engaging driving experience.',
    ],
    [
        'id' => 5, 'brand' => 'Tesla', 'model' => 'Model 3 Long Range', 'name' => 'Tesla Model 3 Long Range', 'year' => 2024,
        'price' => 'R 819 900', 'mileage' => '3,200 mi', 'engine' => 'Dual Motor Electric', 'transmission' => 'Single-Speed',
        'fuelType' => 'Electric', 'horsepower' => '346 HP', 'category' => 'Sedan',
        'description' => 'The Tesla Model 3 Long Range offers up to 358 miles of range on a single charge. With instant torque, Autopilot capability, and a minimalist interior centered around a 15.4-inch touchscreen.',
    ],
    [
        'id' => 6, 'brand' => 'Jeep', 'model' => 'Wrangler Rubicon', 'name' => 'Jeep Wrangler Rubicon', 'year' => 2024,
        'price' => 'R 959 900', 'mileage' => '9,700 mi', 'engine' => '3.6L V6', 'transmission' => '8-Speed Automatic',
        'fuelType' => 'Gasoline', 'horsepower' => '285 HP', 'category' => 'SUV',
        'description' => 'The Jeep Wrangler Rubicon is built for serious off-roading with Dana 44 heavy-duty axles, electronic locking differentials, and a disconnecting front sway bar.',
    ],
    [
        'id' => 7, 'brand' => 'Mercedes-Benz', 'model' => 'C300', 'name' => 'Mercedes-Benz C300', 'year' => 2023,
        'price' => 'R 919 900', 'mileage' => '11,500 mi', 'engine' => '2.0L Turbo 4-Cylinder', 'transmission' => '9-Speed Automatic',
        'fuelType' => 'Gasoline', 'horsepower' => '255 HP', 'category' => 'Sedan',
        'description' => 'The Mercedes-Benz C300 blends luxury and technology with its MBUX infotainment system, 11.9-inch portrait touchscreen, and augmented reality navigation. Refined ride quality and premium materials throughout.',
    ],
    [
        'id' => 8, 'brand' => 'Toyota', 'model' => 'RAV4 TRD Off-Road', 'name' => 'Toyota RAV4 TRD Off-Road', 'year' => 2024,
        'price' => 'R 719 900', 'mileage' => '7,600 mi', 'engine' => '2.5L 4-Cylinder', 'transmission' => '8-Speed Automatic',
        'fuelType' => 'Gasoline', 'horsepower' => '203 HP', 'category' => 'SUV',
        'description' => 'The Toyota RAV4 TRD Off-Road features multi-terrain select, dynamic torque vectoring AWD, and TRD-tuned suspension for confident off-road adventures while maintaining everyday comfort.',
    ],
];

$defaultParts = [
    [
        'id' => 1, 'name' => 'Brembo GT Brake Kit', 'price' => 'R 44 900', 'category' => 'Brakes', 'brand' => 'Brembo', 'year' => 2023,
        'image' => 'part-1.png',
        'compatibility' => 'BMW 3 Series, 4 Series, M3, M4',
        'description' => 'The Brembo GT Brake Kit features 6-piston calipers with cross-drilled rotors for maximum stopping power. Designed for high-performance driving, this kit reduces brake fade during spirited driving and track days.',
    ],
    [
        'id' => 2, 'name' => 'K&N Cold Air Intake', 'price' => 'R 6 490', 'category' => 'Engine', 'brand' => 'K&N', 'year' => 2024,
        'image' => 'part-2.png',
        'compatibility' => 'Ford Mustang GT 2018-2024',
        'description' => 'The K&N Cold Air Intake system delivers increased horsepower and acceleration by reducing air intake restriction. Dyno-tested to add up to 18 HP.',
    ],
    [
        'id' => 3, 'name' => 'Philips X-tremeVision LED H7', 'price' => 'R 1 590', 'category' => 'Lighting', 'brand' => 'Philips', 'year' => 2024,
        'image' => 'part-3.png',
        'compatibility' => 'Universal H7 Socket',
        'description' => 'Philips X-tremeVision LED headlight bulbs deliver up to 200% brighter light compared to standard halogen bulbs with a 6500K color temperature.',
    ],
    [
        'id' => 4, 'name' => 'Borla ATAK Cat-Back Exhaust', 'price' => 'R 34 900', 'category' => 'Exhaust', 'brand' => 'Borla', 'year' => 2024,
        'image' => 'part-4.png',
        'compatibility' => 'Chevrolet Camaro SS 2016-2024',
        'description' => 'The Borla ATAK Cat-Back Exhaust system delivers an aggressive exhaust note. Constructed from T-304 stainless steel with polished tips. Lifetime warranty included.',
    ],
    [
        'id' => 5, 'name' => 'BBS CH-R Wheel Set 19"', 'price' => 'R 58 900', 'category' => 'Wheels', 'brand' => 'BBS', 'year' => 2024,
        'image' => 'part-5.png',
        'compatibility' => '5x112 Bolt Pattern (Audi, VW, Mercedes)',
        'description' => 'The BBS CH-R wheels combine lightweight flow-formed aluminum construction with iconic motorsport-inspired design. Set of 4 in 19x8.5 satin black.',
    ],
    [
        'id' => 6, 'name' => 'KW V3 Coilover Kit', 'price' => 'R 38 900', 'category' => 'Suspension', 'brand' => 'KW', 'year' => 2023,
        'image' => 'part-6.png',
        'compatibility' => 'BMW F30/F32 3 & 4 Series',
        'description' => 'The KW V3 Coilover Kit features independently adjustable rebound and compression damping. Stainless steel construction ensures corrosion resistance.',
    ],
    [
        'id' => 7, 'name' => 'Bosch Premium Oil Filter', 'price' => 'R 189', 'category' => 'Engine', 'brand' => 'Bosch', 'year' => 2024,
        'image' => 'part-7.png',
        'compatibility' => 'Universal - Toyota, Honda, Nissan',
        'description' => 'Bosch Premium Oil Filters feature a blend of natural and synthetic media for superior filtration. Silicone anti-drainback valve prevents dry starts.',
    ],
    [
        'id' => 8, 'name' => 'NGK Iridium IX Spark Plugs (6x)', 'price' => 'R 1 290', 'category' => 'Engine', 'brand' => 'NGK', 'year' => 2024,
        'image' => 'part-8.png',
        'compatibility' => 'Universal - Most 6-Cylinder Engines',
        'description' => 'NGK Iridium IX Spark Plugs feature an ultra-fine 0.6mm iridium tip for superior ignitability and longevity. Set of 6.',
    ],
];

/** @var ?PDO */
$GLOBALS['CATALOG_DB'] = null;

function catalog_db(): ?PDO
{
    if (array_key_exists('CATALOG_DB', $GLOBALS) && $GLOBALS['CATALOG_DB'] instanceof PDO) {
        return $GLOBALS['CATALOG_DB'];
    }

    $dbClassPath = dirname(__DIR__, 2) . '/admin/assets/classes/connect_db_class.php';
    if (!is_file($dbClassPath)) {
        return null;
    }

    require_once $dbClassPath;

    if (!class_exists('Database')) {
        return null;
    }

    try {
        $database = new Database();
        $pdo = $database->connect();
        if (!($pdo instanceof PDO)) {
            return null;
        }

        // Keep fetch behavior consistent for catalog read queries.
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $GLOBALS['CATALOG_DB'] = $pdo;

        return $pdo;
    } catch (Throwable) {
        return null;
    }
}

function media_url_from_db_path(string $path): string
{
    $path = str_replace('\\', '/', trim($path));
    if ($path === '') {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    while (str_starts_with($path, '../')) {
        $path = substr($path, 3);
    }
    if (str_starts_with($path, './')) {
        $path = substr($path, 2);
    }

    if (str_starts_with($path, '/img/')) {
        $path = 'admin' . $path;
    } elseif (str_starts_with($path, 'img/')) {
        $path = 'admin/' . $path;
    }

    $publicPath = public_web_path();
    $appPrefix = '';
    if ($publicPath !== '') {
        $parent = trim(str_replace('\\', '/', (string) dirname($publicPath)), '/.');
        if ($parent !== '') {
            $appPrefix = '/' . $parent;
        }
    }

    return site_origin() . url_path_segments($appPrefix . '/' . ltrim($path, '/'));
}

function as_int_or_null(mixed $value): ?int
{
    if ($value === null || $value === '') {
        return null;
    }

    return (int) $value;
}

/** @return list<array<string, mixed>> */
function load_cars_from_db(): array
{
    $db = catalog_db();
    if (! $db instanceof PDO) {
        return [];
    }

     $sql = "SELECT c.car_id, c.make, c.model, c.variant, c.year, c.mileage, c.price, c.color, c.transmission, c.fuel_type, c.description, c.condition_type,
                         c.car_condition, c.vin, c.mm_code, c.finance_eligible, c.status,
                   COALESCE(
                      (SELECT i.image_url FROM images i WHERE i.car_id = c.car_id AND i.is_primary = 1 LIMIT 1),
                      (SELECT i2.image_url FROM images i2 WHERE i2.car_id = c.car_id ORDER BY i2.image_id ASC LIMIT 1)
                   ) AS image_url
            FROM cars c
            WHERE (c.visibility IS NULL OR c.visibility = 'Yes')
            ORDER BY c.car_id DESC";

    try {
        $rows = $db->query($sql)->fetchAll();
        if (! is_array($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $brand = trim((string) ($row['make'] ?? ''));
            $model = trim((string) ($row['model'] ?? ''));
            $variant = trim((string) ($row['variant'] ?? ''));
            $nameParts = array_values(array_filter([$brand, $model, $variant], static fn (string $s): bool => $s !== ''));
            $name = $nameParts === [] ? 'Vehicle' : implode(' ', $nameParts);
            $rawPrice = (float) ($row['price'] ?? 0);
            $priceInt = (int) round($rawPrice);
            $rawMileage = as_int_or_null($row['mileage'] ?? null);

            $out[] = [
                'id' => (int) ($row['car_id'] ?? 0),
                'brand' => $brand !== '' ? $brand : 'Unknown',
                'model' => $model,
                'name' => $name,
                'year' => as_int_or_null($row['year'] ?? null) ?? 0,
                'price' => format_rand($priceInt),
                'priceValue' => $priceInt,
                'mileage' => $rawMileage !== null ? number_format($rawMileage) . ' km' : 'Mileage not specified',
                'engine' => '',
                'transmission' => (string) ($row['transmission'] ?? ''),
                'fuelType' => (string) ($row['fuel_type'] ?? ''),
                'horsepower' => '',
                'color' => (string) ($row['color'] ?? ''),
                'category' => (string) ($row['condition_type'] ?? 'Vehicle'),
                'condition' => (string) ($row['car_condition'] ?? ''),
                'vin' => (string) ($row['vin'] ?? ''),
                'mmCode' => (string) ($row['mm_code'] ?? ''),
                'financeEligible' => (string) ($row['finance_eligible'] ?? ''),
                'status' => (string) ($row['status'] ?? ''),
                'description' => (string) ($row['description'] ?? ''),
                'image_url' => media_url_from_db_path((string) ($row['image_url'] ?? '')),
            ];
        }

        return $out;
    } catch (Throwable) {
        return [];
    }
}

/** @return list<array<string, mixed>> */
function load_parts_from_db(): array
{
    $db = catalog_db();
    if (! $db instanceof PDO) {
        return [];
    }

    $sql = "SELECT p.part_id, p.part_name, p.category, p.make, p.year, p.price, p.description,
                   p.part_number, p.model, p.variant, p.condition_type, p.part_condition,
                   p.quantity, p.mm_code, p.status,
                   COALESCE(
                      (SELECT pi.image_url FROM part_images pi WHERE pi.part_id = p.part_id AND pi.is_primary = 1 LIMIT 1),
                      (SELECT pi2.image_url FROM part_images pi2 WHERE pi2.part_id = p.part_id ORDER BY pi2.image_id ASC LIMIT 1)
                   ) AS image_url
            FROM parts p
            WHERE (p.visibility IS NULL OR p.visibility = 'Yes')
            ORDER BY p.part_id DESC";

    try {
        $rows = $db->query($sql)->fetchAll();
        if (! is_array($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $priceInt = (int) round((float) ($row['price'] ?? 0));
            $partId = (int) ($row['part_id'] ?? 0);
            $out[] = [
                'id' => $partId,
                'name' => (string) ($row['part_name'] ?? 'Part'),
                'price' => format_rand($priceInt),
                'priceValue' => $priceInt,
                'category' => (string) ($row['category'] ?? 'Parts'),
                'brand' => (string) ($row['make'] ?? ''),
                'year' => as_int_or_null($row['year'] ?? null) ?? 0,
                'compatibility' => trim((string) (($row['make'] ?? '') . ' ' . ($row['year'] ?? ''))),
                'description' => (string) ($row['description'] ?? ''),
                'part_number' => (string) ($row['part_number'] ?? ''),
                'model' => (string) ($row['model'] ?? ''),
                'variant' => (string) ($row['variant'] ?? ''),
                'condition_type' => (string) ($row['condition_type'] ?? ''),
                'part_condition' => (string) ($row['part_condition'] ?? ''),
                'quantity' => (int) ($row['quantity'] ?? 0),
                'mm_code' => (string) ($row['mm_code'] ?? ''),
                'status' => (string) ($row['status'] ?? ''),
                'image_url' => media_url_from_db_path((string) ($row['image_url'] ?? '')),
                'image' => '',
            ];
        }

        return $out;
    } catch (Throwable) {
        return [];
    }
}

/** @return list<string> */
function car_image_urls(int $carId, string $fallback = ''): array
{
    $db = catalog_db();
    if (! $db instanceof PDO || $carId < 1) {
        return $fallback !== '' ? [$fallback] : [];
    }

    $stmt = $db->prepare('SELECT image_url FROM images WHERE car_id = :car_id ORDER BY is_primary DESC, image_id ASC');
    $stmt->bindValue(':car_id', $carId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $urls = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $url = media_url_from_db_path((string) ($row['image_url'] ?? ''));
            if ($url !== '') {
                $urls[] = $url;
            }
        }

        if ($urls === [] && $fallback !== '') {
            return [$fallback];
        }

        return array_values(array_unique($urls));
    } catch (Throwable) {
        return $fallback !== '' ? [$fallback] : [];
    }
}

/** @return list<string> */
function part_image_urls(int $partId, string $fallback = ''): array
{
    $db = catalog_db();
    if (! $db instanceof PDO || $partId < 1) {
        return $fallback !== '' ? [$fallback] : [];
    }

    $stmt = $db->prepare('SELECT image_url FROM part_images WHERE part_id = :part_id ORDER BY is_primary DESC, image_id ASC');
    $stmt->bindValue(':part_id', $partId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $urls = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $url = media_url_from_db_path((string) ($row['image_url'] ?? ''));
            if ($url !== '') {
                $urls[] = $url;
            }
        }

        if ($urls === [] && $fallback !== '') {
            return [$fallback];
        }

        return array_values(array_unique($urls));
    } catch (Throwable) {
        return $fallback !== '' ? [$fallback] : [];
    }
}

function load_catalog_cars(array $defaults): array
{
    $fromDb = load_cars_from_db();
    if ($fromDb !== []) {
        return $fromDb;
    }

    return load_catalog_items('cars', $defaults);
}

function load_catalog_parts(array $defaults): array
{
    $fromDb = load_parts_from_db();
    if ($fromDb !== []) {
        return $fromDb;
    }

    return load_catalog_items('parts', $defaults);
}

$GLOBALS['cars'] = load_catalog_cars($defaultCars);
$GLOBALS['parts'] = load_catalog_parts($defaultParts);

function car_image_url(): string
{
    return asset('car-placeholder.jpg');
}

function part_image_url(): string
{
    return asset('part-placeholder.jpg');
}

function car_primary_image_url(array $car): string
{
    $raw = isset($car['image_url']) ? (string) $car['image_url'] : '';
    if ($raw !== '') {
        return $raw;
    }

    return car_image_url();
}

/** Web URL for a part image, preferring DB media paths and then static placeholders. */
function part_image_url_for(array $part): string
{
    $dbUrl = isset($part['image_url']) ? (string) $part['image_url'] : '';
    if ($dbUrl !== '') {
        return $dbUrl;
    }

    $raw = $part['image'] ?? null;
    $name = is_string($raw) && $raw !== '' ? basename($raw) : 'part-' . (int) ($part['id'] ?? 0) . '.png';
    $publicRoot = defined('PUBLIC_PATH') ? (string) constant('PUBLIC_PATH') : '';
    if ($publicRoot !== '' && is_file($publicRoot . '/assets/parts/' . $name)) {
        return asset('parts/' . $name);
    }

    return part_image_url();
}

/** Filename slug for `public/assets/brands/{slug}.{svg,png,webp}`. */
function brand_logo_slug(string $brand): string
{
    $s = strtolower(trim($brand));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';

    return trim($s, '-') !== '' ? trim($s, '-') : 'unknown';
}

/**
 * Web URL for a brand badge under `public/assets/brands/`, or empty string if none found.
 * Assets are original nominative badges (not OEM logo reproductions).
 */
function brand_logo_url(string $brand): string
{
    $slug = brand_logo_slug($brand);
    $publicRoot = defined('PUBLIC_PATH') ? (string) constant('PUBLIC_PATH') : '';
    if ($publicRoot === '') {
        return '';
    }
    $dir = $publicRoot . '/assets/brands/';
    foreach (['svg', 'png', 'webp'] as $ext) {
        $name = $slug . '.' . $ext;
        if (is_file($dir . $name)) {
            return asset('brands/' . $name);
        }
    }

    return '';
}

function hero_image_url(): string
{
    return asset('hero-car.jpg');
}

/** @return list<string> */
function car_brands(): array
{
    $brands = array_map(static fn (array $c): string => trim((string) ($c['brand'] ?? '')), $GLOBALS['cars']);
    $brands = array_values(array_filter($brands, static fn (string $b): bool => $b !== ''));
    $brands = array_values(array_unique($brands));
    natcasesort($brands);

    return array_values($brands);
}

/** @return list<string> */
function car_categories(): array
{
    $cats = array_map(static fn (array $c): string => $c['category'], $GLOBALS['cars']);

    return array_values(array_unique($cats));
}

/** @return list<int> */
function car_years(): array
{
    $currentYear = (int) date('Y');
    $years = [];
    for ($y = $currentYear; $y >= 1900; $y--) {
        $years[] = $y;
    }

    return $years;
}

/** @return list<int> */
function car_price_values(): array
{
    $maxPrice = 50000;
    foreach ($GLOBALS['cars'] as $car) {
        $raw = $car['priceValue'] ?? ($car['price'] ?? null);
        if (is_numeric($raw)) {
            $price = (int) $raw;
        } else {
            $digits = preg_replace('/[^0-9]/', '', (string) ($raw ?? '')) ?? '';
            $price = $digits !== '' ? (int) $digits : 0;
        }
        if ($price > $maxPrice) {
            $maxPrice = $price;
        }
    }

    $upper = (int) (ceil($maxPrice / 50000) * 50000);
    if ($upper < 50000) {
        $upper = 50000;
    }

    $values = [10000, 50000];
    for ($v = 100000; $v <= $upper; $v += 50000) {
        $values[] = $v;
    }

    $values = array_values(array_unique($values));
    sort($values, SORT_NUMERIC);

    return $values;
}

function format_rand(int $amount): string
{
    return 'R ' . number_format($amount, 0, '.', ' ');
}

/** @return list<string> */
function part_categories(): array
{
    return [
        'Engine',
        'Brakes',
        'Suspension',
        'Exhaust',
        'Lighting',
        'Body',
        'Electrical',
        'Interior',
        'Wheels',
    ];
}

/** @return list<string> */
function part_brands(): array
{
    $brands = array_map(static fn (array $p): string => $p['brand'], $GLOBALS['parts']);

    return array_values(array_unique($brands));
}

/** @return list<int> */
function part_years(): array
{
    $currentYear = (int) date('Y');
    $years = [];
    for ($y = $currentYear; $y >= 1900; $y--) {
        $years[] = $y;
    }

    return $years;
}

/** @return list<int> */
function part_price_values(): array
{
    $maxPrice = 50000;
    foreach ($GLOBALS['parts'] as $part) {
        $raw = $part['priceValue'] ?? ($part['price'] ?? null);
        if (is_numeric($raw)) {
            $price = (int) $raw;
        } else {
            $digits = preg_replace('/[^0-9]/', '', (string) ($raw ?? '')) ?? '';
            $price = $digits !== '' ? (int) $digits : 0;
        }
        if ($price > $maxPrice) {
            $maxPrice = $price;
        }
    }

    $upper = (int) (ceil($maxPrice / 50000) * 50000);
    if ($upper < 50000) {
        $upper = 50000;
    }

    $values = [10000, 50000];
    for ($v = 100000; $v <= $upper; $v += 50000) {
        $values[] = $v;
    }

    $values = array_values(array_unique($values));
    sort($values, SORT_NUMERIC);

    return $values;
}

/**
 * @return list<array<string, mixed>>
 */
function cars_home_preview(int $limit = 4): array
{
    if ($limit < 1) {
        return [];
    }

    return array_slice($GLOBALS['cars'], 0, $limit);
}

/**
 * @return list<array<string, mixed>>
 */
function parts_home_preview(int $limit = 4): array
{
    if ($limit < 1) {
        return [];
    }

    return array_slice($GLOBALS['parts'], 0, $limit);
}

function find_car(int $id): ?array
{
    foreach ($GLOBALS['cars'] as $c) {
        if ($c['id'] === $id) {
            return $c;
        }
    }

    return null;
}

function find_part(int $id): ?array
{
    foreach ($GLOBALS['parts'] as $p) {
        if ($p['id'] === $id) {
            return $p;
        }
    }

    return null;
}
