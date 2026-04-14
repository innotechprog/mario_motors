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
function parts_listing_from_get(array $get): array
{
    $search = isset($get['q']) ? trim((string) $get['q']) : '';
    $activeCategory = isset($get['cat']) && $get['cat'] !== '' ? (string) $get['cat'] : null;
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

    $filtered = array_filter($GLOBALS['parts'], static function (array $p) use ($search, $activeCategory, $activeMinYear, $activeMaxYear, $activeMinPrice, $activeMaxPrice): bool {
        $q = strtolower($search);
        $matchesSearch = $search === ''
            || str_contains(strtolower($p['name']), $q)
            || str_contains(strtolower($p['category']), $q)
            || str_contains(strtolower($p['brand']), $q);
        $matchesCategory = $activeCategory === null || $p['category'] === $activeCategory;
        $py = isset($p['year']) ? (int) $p['year'] : null;
        $matchesMinYear = $activeMinYear === null || ($py !== null && $py >= $activeMinYear);
        $matchesMaxYear = $activeMaxYear === null || ($py !== null && $py <= $activeMaxYear);

        $rawPrice = $p['priceValue'] ?? ($p['price'] ?? null);
        if (is_numeric($rawPrice)) {
            $price = (int) $rawPrice;
        } else {
            $digits = preg_replace('/[^0-9]/', '', (string) ($rawPrice ?? '')) ?? '';
            $price = $digits !== '' ? (int) $digits : null;
        }
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

function parts_query_href(array $overrides, array $get): string
{
    $base = [
        'q' => $get['q'] ?? '',
        'cat' => $get['cat'] ?? '',
        'min_year' => $get['min_year'] ?? '',
        'max_year' => $get['max_year'] ?? '',
        'min_price' => $get['min_price'] ?? '',
        'max_price' => $get['max_price'] ?? '',
        'page' => $get['page'] ?? '',
    ];
    $merged = array_merge($base, $overrides);
    $merged = array_filter($merged, static fn ($v) => $v !== null && $v !== '');

    return $merged === [] ? 'parts' : 'parts?' . http_build_query($merged);
}
