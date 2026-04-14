<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

$normalized = $_GET;
$needsRedirect = false;

if (isset($normalized['type']) && ! isset($normalized['cat']) && trim((string) $normalized['type']) !== '') {
	$normalized['cat'] = trim((string) $normalized['type']);
	unset($normalized['type']);
	$needsRedirect = true;
}

if (isset($normalized['year']) && ! isset($normalized['min_year']) && ! isset($normalized['max_year'])) {
	$year = (int) $normalized['year'];
	if ($year > 0) {
		$normalized['min_year'] = $year;
		$normalized['max_year'] = $year;
	}
	unset($normalized['year']);
	$needsRedirect = true;
}

if (isset($normalized['brand']) && trim((string) $normalized['brand']) !== '') {
	if (! isset($normalized['q']) || trim((string) $normalized['q']) === '') {
		$normalized['q'] = trim((string) $normalized['brand']);
	}
	unset($normalized['brand']);
	$needsRedirect = true;
}

foreach (['cat', 'q', 'min_year', 'max_year', 'min_price', 'max_price'] as $key) {
	if (isset($normalized[$key]) && trim((string) $normalized[$key]) === '') {
		unset($normalized[$key]);
		$needsRedirect = true;
	}
}

if (isset($normalized['page']) && (int) $normalized['page'] <= 1) {
	unset($normalized['page']);
	$needsRedirect = true;
}

if ($needsRedirect) {
	ksort($normalized);
	redirect($normalized === [] ? 'parts' : 'parts?' . http_build_query($normalized));
}

$listing = parts_listing_from_get($_GET);
$queryGet = $_GET;
extract($listing, EXTR_OVERWRITE);

$pageTitle = 'Car Parts';
$currentNav = 'parts';

layout_start(compact('pageTitle', 'currentNav'));
require VIEW_PATH . '/pages/parts-index.php';
layout_end();
