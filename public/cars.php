<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

$normalized = $_GET;
$needsRedirect = false;

if (isset($normalized['cat']) && ! isset($normalized['type']) && trim((string) $normalized['cat']) !== '') {
	$normalized['type'] = trim((string) $normalized['cat']);
	unset($normalized['cat']);
	$needsRedirect = true;
}

if (isset($normalized['type']) && trim((string) $normalized['type']) === '') {
	unset($normalized['type']);
	$needsRedirect = true;
}

if (isset($normalized['page']) && (int) $normalized['page'] <= 1) {
	unset($normalized['page']);
	$needsRedirect = true;
}

if ($needsRedirect) {
	$normalized = array_filter(
		$normalized,
		static fn (mixed $value): bool => ! (is_string($value) && trim($value) === '')
	);
	ksort($normalized);
	redirect($normalized === [] ? 'cars' : 'cars?' . http_build_query($normalized));
}

$listing = cars_listing_from_get($_GET);
$queryGet = $_GET;
extract($listing, EXTR_OVERWRITE);

$pageTitle = 'Browse Cars';
$currentNav = 'cars';

layout_start(compact('pageTitle', 'currentNav'));
require VIEW_PATH . '/pages/cars-index.php';
layout_end();
