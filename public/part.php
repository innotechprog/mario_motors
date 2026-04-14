<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$part = find_part($id);

if ($part === null) {
    http_response_code(404);
    $pageTitle = 'Part Not Found';
    $currentNav = 'parts';
    layout_start(compact('pageTitle', 'currentNav'));
    require VIEW_PATH . '/pages/part-missing.php';
    layout_end();
    exit;
}

$pageTitle = (string) $part['name'];
$currentNav = 'parts';
$img = part_image_url_for($part);
$images = part_image_urls((int) $part['id'], $img);

// Meta description for this part
$metaDescription = trim(implode(' — ', array_filter([
    (string) $part['name'],
    (string) $part['category'],
    (string) $part['brand'],
    (string) $part['price'],
])));

// Build specs array with all part information
$specs = [];
$pushSpec = function (string $label, mixed $value, string $icon = '📋') use (&$specs): void {
    $val = (string) $value;
    $val = trim($val);
    if ($val !== '' && $val !== '0' && $val !== 'N/A') {
        $specs[] = [
            'label' => $label,
            'value' => $val,
            'icon' => $icon,
        ];
    }
};

$pushSpec('Category', $part['category'] ?? '', '🏷');
$pushSpec('Brand', $part['brand'] ?? '', '🏭');
$pushSpec('Part Number', $part['part_number'] ?? '', '🔢');
$pushSpec('Model', $part['model'] ?? '', '🚗');
$pushSpec('Variant', $part['variant'] ?? '', '⚙️');
$pushSpec('Year', $part['year'] ?? '', '📅');
$pushSpec('Condition Type', $part['condition_type'] ?? '', '✓');
$pushSpec('Condition', $part['part_condition'] ?? '', '🔍');
$pushSpec('Quantity', $part['quantity'] ?? '', '📦');
$pushSpec('MM Code', $part['mm_code'] ?? '', '📝');
$pushSpec('Status', $part['status'] ?? '', '🏁');

layout_start(compact('pageTitle', 'currentNav', 'metaDescription', 'part', 'img', 'images', 'specs'));
require VIEW_PATH . '/pages/part-show.php';
layout_end();
