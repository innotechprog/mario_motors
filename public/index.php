<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

$pageTitle = 'Home';
$currentNav = '';
$why = [
    ['title' => 'Certified Quality', 'desc' => 'Every vehicle undergoes a rigorous inspection before it reaches our lot.', 'icon' => ''],
    ['title' => 'Fair Pricing', 'desc' => 'Transparent pricing in rands with no hidden fees and competitive market rates.', 'icon' => ''],
    ['title' => 'Fast Service', 'desc' => 'Same-day test drives and efficient spare-part sourcing when you need it.', 'icon' => ''],
    ['title' => 'Trusted Locally', 'desc' => 'Serving Pretoria and Gauteng with honest advice and after-sales support.', 'icon' => ''],
];
$hero = hero_image_url();
$previewCars = cars_home_preview(4);

layout_start(compact(
    'pageTitle',
    'currentNav',
    'previewCars',
));
require VIEW_PATH . '/pages/home.php';
layout_end();
