<?php

declare(strict_types=1);

$root = dirname(__DIR__);

define('VIEW_PATH', $root . '/views');
define('PUBLIC_PATH', $root . '/public');
define('STORAGE_PATH', $root . '/storage');

$GLOBALS['APP_CONFIG'] = require $root . '/config/app.php';

$appDebug = (bool) ($GLOBALS['APP_CONFIG']['debug'] ?? false);
if (! $appDebug) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

require $root . '/src/Support/helpers.php';
require $root . '/src/Data/store.php';
require $root . '/src/Data/catalog.php';
require $root . '/src/Http/cars-listing.php';
require $root . '/src/Http/parts-listing.php';
