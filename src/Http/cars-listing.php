<?php

declare(strict_types=1);

/**
 * @return array{
 *   search: string,
 *   activeCategory: string|null,
 *   activeMinYear: int|null,
 *   activeMaxYear: int|null,
 *   activeMinPrice: int|null,
 *   activeMaxPrice: int|null,
 *   page: int,
 *   perPage: int,
 *   totalItems: int,
 *   totalPages: int,
 *   filtered: list<array<string, mixed>>
 * }
 */
function cars_listing_from_get(array $get): array
{
    $search = isset($get['q']) ? trim((string) $get['q']) : '';
    $activeCategory = isset($get['type']) && $get['type'] !== '' ? (string) $get['type'] : null;
    $activeMinYear = isset($get['min_year']) && $get['min_year'] !== '' ? (int) $get['min_year'] : null;
    $activeMaxYear = isset($get['max_year']) && $get['max_year'] !== '' ? (int) $get['max_year'] : null;
    $activeMinPrice = isset($get['min_price']) && $get['min_price'] !== '' ? (int) $get['min_price'] : null;
    $activeMaxPrice = isset($get['max_price']) && $get['max_price'] !== '' ? (int) $get['max_price'] : null;

    if ($activeMinYear !== null && $activeMaxYear !== null && $activeMinYear > $activeMaxYear) {
        [$activeMinYear, $activeMaxYear] = [$activeMaxYear, $activeMinYear];
    }
    if ($activeMinPrice !== null && $activeMaxPrice !== null && $activeMinPrice > $activeMaxPrice) {
        [$activeMinPrice, $activeMaxPrice] = [$activeMaxPrice, $activeMinPrice];
    }

    $filtered = array_filter($GLOBALS['cars'], static function (array $c) use ($search, $activeCategory, $activeMinYear, $activeMaxYear, $activeMinPrice, $activeMaxPrice): bool {
        $q = strtolower($search);
        $matchesSearch = $search === ''
            || str_contains(strtolower($c['name']), $q)
            || str_contains(strtolower($c['brand']), $q)
            || str_contains(strtolower($c['model']), $q)
            || str_contains((string) $c['year'], $search);
        $matchesCategory = $activeCategory === null || $c['category'] === $activeCategory;
        $year = isset($c['year']) ? (int) $c['year'] : null;
        $matchesMinYear = $activeMinYear === null || ($year !== null && $year >= $activeMinYear);
        $matchesMaxYear = $activeMaxYear === null || ($year !== null && $year <= $activeMaxYear);

        $rawPrice = isset($c['price']) ? (string) $c['price'] : '';
        $digits = preg_replace('/[^0-9]/', '', $rawPrice) ?? '';
        $price = $digits !== '' ? (int) $digits : null;
        $matchesMinPrice = $activeMinPrice === null || ($price !== null && $price >= $activeMinPrice);
        $matchesMaxPrice = $activeMaxPrice === null || ($price !== null && $price <= $activeMaxPrice);

        return $matchesSearch && $matchesCategory && $matchesMinYear && $matchesMaxYear && $matchesMinPrice && $matchesMaxPrice;
    });

    $filtered = array_values($filtered);
    $perPageConfig = (int) config('listing_per_page', 12);
    $perPage = $perPageConfig > 0 ? min($perPageConfig, 60) : 12;
    $totalItems = count($filtered);
    $totalPages = max(1, (int) ceil($totalItems / $perPage));
    $page = isset($get['page']) ? max(1, (int) $get['page']) : 1;
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    $offset = ($page - 1) * $perPage;
    $paged = array_slice($filtered, $offset, $perPage);

    return [
        'search' => $search,
        'activeCategory' => $activeCategory,
        'activeMinYear' => $activeMinYear,
        'activeMaxYear' => $activeMaxYear,
        'activeMinPrice' => $activeMinPrice,
        'activeMaxPrice' => $activeMaxPrice,
        'page' => $page,
        'perPage' => $perPage,
        'totalItems' => $totalItems,
        'totalPages' => $totalPages,
        'filtered' => $paged,
    ];
}

function cars_query_href(array $overrides, array $get): string
{
    $base = [
        'q' => $get['q'] ?? '',
        'type' => $get['type'] ?? '',
        'min_year' => $get['min_year'] ?? '',
        'max_year' => $get['max_year'] ?? '',
        'min_price' => $get['min_price'] ?? '',
        'max_price' => $get['max_price'] ?? '',
        'page' => $get['page'] ?? '',
    ];
    $merged = array_merge($base, $overrides);
    $merged = array_filter($merged, static fn ($v) => $v !== null && $v !== '');

    return $merged === [] ? 'cars' : 'cars?' . http_build_query($merged);
}
