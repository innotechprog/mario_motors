<?php

declare(strict_types=1);

function ensure_storage_directory(): string
{
    $path = defined('STORAGE_PATH') ? (string) constant('STORAGE_PATH') : dirname(__DIR__, 2) . '/storage';
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }

    return $path;
}

function storage_path(string $file): string
{
    $clean = trim(str_replace('\\', '/', $file), '/');

    return ensure_storage_directory() . '/' . $clean;
}

function storage_read_json(string $file, mixed $default): mixed
{
    $path = storage_path($file);
    if (! is_file($path)) {
        storage_write_json($file, $default);

        return $default;
    }

    $raw = file_get_contents($path);
    if (! is_string($raw) || trim($raw) === '') {
        storage_write_json($file, $default);

        return $default;
    }

    $decoded = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $default;
    }

    return $decoded;
}

function storage_write_json(string $file, mixed $data): void
{
    $path = storage_path($file);
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        throw new RuntimeException('Unable to encode storage payload.');
    }

    if (file_put_contents($path, $json . PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Unable to write storage file: ' . $file);
    }
}

/**
 * @param list<array<string, mixed>> $defaults
 * @return list<array<string, mixed>>
 */
function load_catalog_items(string $type, array $defaults): array
{
    $items = storage_read_json('catalog-' . $type . '.json', $defaults);
    if (! is_array($items)) {
        return $defaults;
    }

    $items = array_values(array_filter($items, 'is_array'));

    return $items === [] ? $defaults : $items;
}

/**
 * @param list<array<string, mixed>> $items
 */
function save_catalog_items(string $type, array $items): void
{
    $items = array_values($items);
    storage_write_json('catalog-' . $type . '.json', $items);
    $GLOBALS[$type] = $items;
}

/**
 * @param list<array<string, mixed>> $items
 */
function next_catalog_id(array $items): int
{
    $max = 0;
    foreach ($items as $item) {
        $id = isset($item['id']) ? (int) $item['id'] : 0;
        if ($id > $max) {
            $max = $id;
        }
    }

    return $max + 1;
}