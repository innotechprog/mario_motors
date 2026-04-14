<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    return $GLOBALS['APP_CONFIG'][$key] ?? $default;
}

function esc(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

/**
 * Encodes a URL path for use in HTML (spaces → %20, etc.). Leading slash preserved.
 */
function url_path_segments(string $path): string
{
    $path = str_replace('\\', '/', $path);
    $parts = array_values(array_filter(explode('/', $path), static fn (string $s): bool => $s !== ''));
    if ($parts === []) {
        return '/';
    }

    return '/' . implode('/', array_map(rawurlencode(...), $parts));
}

/**
 * URL path from domain root to the `public` folder (no leading slash in return value), e.g. "" or "Mario Motors/public".
 * Prefers filesystem (DOCUMENT_ROOT + PUBLIC_PATH) so rewritten URLs like /cars do not break asset links.
 */
function public_web_path(): string
{
    $override = config('public_url_path');
    if (is_string($override) && $override !== '') {
        return trim(str_replace('\\', '/', $override), '/');
    }

    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    if (is_string($docRoot) && $docRoot !== '' && defined('PUBLIC_PATH')) {
        $docReal = realpath($docRoot);
        $pubReal = realpath(PUBLIC_PATH);
        if ($docReal !== false && $pubReal !== false && str_starts_with($pubReal, $docReal)) {
            $rel = substr($pubReal, strlen($docReal));
            $rel = trim(str_replace('\\', '/', $rel), '/');

            return $rel;
        }
    }

    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = dirname($scriptName);
    if ($dir === '/' || $dir === '\\' || $dir === '.') {
        return '';
    }

    return trim($dir, '/');
}

/**
 * Absolute URL for a file in `public/assets/`. Works for index.php, extensionless routes, and deploy subfolders.
 * Optional `assets_base_url` (path from domain root, e.g. `/shop/assets`) overrides the `/assets/` location.
 */
function asset(string $path): string
{
    $override = config('assets_base_url');
    if (is_string($override) && $override !== '') {
        $tail = rtrim($override, '/') . '/' . ltrim($path, '/');
        if (str_starts_with($tail, 'http://') || str_starts_with($tail, 'https://')) {
            return $tail;
        }

        $p = '/' . ltrim(str_replace('\\', '/', $tail), '/');

        return site_origin() . url_path_segments($p);
    }

    $tail = '/assets/' . ltrim(str_replace('\\', '/', $path), '/');
    $prefix = public_web_path();
    $joined = ($prefix === '' ? '' : $prefix . '/') . ltrim($tail, '/');
    $joined = '/' . ltrim((string) preg_replace('#/+#', '/', $joined), '/');

    return site_origin() . url_path_segments($joined);
}

/**
 * Logo basename under public/assets/ (see config `logo_path`, default logo.png).
 */
function site_logo_filename(): string
{
    $p = config('logo_path');

    return is_string($p) && $p !== '' ? basename($p) : 'logo.png';
}

/**
 * URL to the site logo in assets (e.g. …/assets/logo.png when using public/index.php).
 */
function logo_url(): string
{
    return asset(site_logo_filename());
}

function has_site_logo(): bool
{
    $publicRoot = defined('PUBLIC_PATH') ? (string) constant('PUBLIC_PATH') : '';
    if ($publicRoot === '') {
        return false;
    }

    return is_file($publicRoot . '/assets/' . site_logo_filename());
}

/**
 * Normalized social links for icons (https URLs only). WhatsApp included when $whatsappUrl is non-empty.
 *
 * @return list<array{network: string, url: string, label: string}>
 */
function social_icon_links(string $whatsappUrl = ''): array
{
    $wa = trim($whatsappUrl);
    $out = [];
    if ($wa !== '' && str_starts_with($wa, 'http')) {
        $out[] = ['network' => 'whatsapp', 'url' => $wa, 'label' => 'WhatsApp'];
    }

    $raw = config('social_links');
    if (! is_array($raw)) {
        return $out;
    }

    $order = ['facebook', 'instagram', 'tiktok', 'youtube', 'x', 'linkedin'];
    $labels = [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'tiktok' => 'TikTok',
        'youtube' => 'YouTube',
        'x' => 'X',
        'linkedin' => 'LinkedIn',
    ];

    foreach ($order as $key) {
        $url = isset($raw[$key]) && is_string($raw[$key]) ? trim($raw[$key]) : '';
        if ($url === '' || ! str_starts_with($url, 'http')) {
            continue;
        }
        $out[] = [
            'network' => $key,
            'url' => $url,
            'label' => $labels[$key] ?? ucfirst($key),
        ];
    }

    return $out;
}

/**
 * Inline SVG icon (24×24, currentColor) for a social network key.
 */
function social_icon_svg(string $network): string
{
    $d = match ($network) {
        'whatsapp' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z',
        'facebook' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
        'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
        'tiktok' => 'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05 6.33 6.33 0 00-5.46 9.85 6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z',
        'youtube' => 'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
        'x' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
        'linkedin' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
        default => '',
    };

    if ($d === '') {
        return '';
    }

    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true"><path d="' . $d . '"/></svg>';
}

/**
 * @return list<string>
 */
function featured_car_brands(): array
{
    $v = config('featured_car_brands');
    if (! is_array($v)) {
        return [];
    }

    return array_values(array_filter(array_map('strval', $v), static fn (string $s): bool => $s !== ''));
}

/** Web URL to brands banner image, or null if file is not in public/assets. */
function brands_banner_url(): ?string
{
    $name = config('brands_banner_image');
    if (! is_string($name) || $name === '') {
        return null;
    }
    $name = basename($name);
    $publicRoot = defined('PUBLIC_PATH') ? (string) constant('PUBLIC_PATH') : '';
    if ($publicRoot !== '' && is_file($publicRoot . '/assets/' . $name)) {
        return asset($name);
    }

    return null;
}

function site_origin(): string
{
    $configured = config('base_url');
    if (is_string($configured) && $configured !== '') {
        return rtrim($configured, '/');
    }

    $https = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (! empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme . '://' . $host;
}

function absolute_asset_url(string $path): string
{
    return asset($path);
}

function canonical_request_url(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $uri = explode('#', $uri, 2)[0];

    return site_origin() . $uri;
}

/** Normalized comma-separated keywords for meta tags. */
/**
 * @return list<array{title: string, category: string, image: string, blurb: string}>
 */
function home_part_category_tiles(): array
{
    $v = config('home_part_category_tiles');
    if (! is_array($v)) {
        return [];
    }

    $out = [];
    foreach ($v as $row) {
        if (! is_array($row)) {
            continue;
        }
        $title = trim((string) ($row['title'] ?? ''));
        $category = trim((string) ($row['category'] ?? ''));
        $image = trim((string) ($row['image'] ?? ''));
        $blurb = trim((string) ($row['blurb'] ?? ''));
        if ($title === '' || $category === '' || $image === '') {
            continue;
        }
        $out[] = [
            'title' => $title,
            'category' => $category,
            'image' => basename($image),
            'blurb' => $blurb,
        ];
    }

    return $out;
}

function seo_keywords_content(mixed $override = null): string
{
    if (is_string($override) && trim($override) !== '') {
        return trim($override);
    }

    $v = config('seo_keywords');
    if (is_string($v) && trim($v) !== '') {
        return trim($v);
    }
    if (is_array($v)) {
        $parts = array_values(array_filter(array_map(static fn ($x): string => trim((string) $x), $v), static fn (string $s): bool => $s !== ''));

        return implode(', ', $parts);
    }

    return '';
}

/**
 * @return list<string>
 */
function business_services_highlight(): array
{
    $v = config('services_highlight');
    if (! is_array($v)) {
        return [];
    }

    return array_values(array_filter(array_map('strval', $v), static fn (string $s): bool => $s !== ''));
}

function business_services_tagline(): string
{
    $lines = business_services_highlight();

    return $lines === [] ? '' : implode(' · ', $lines);
}

/**
 * @return list<array{label: string, value: string}>
 */
function business_hours(): array
{
    $value = config('business_hours');
    if (! is_array($value)) {
        return [];
    }

    $hours = [];
    foreach ($value as $row) {
        if (! is_array($row)) {
            continue;
        }

        $label = trim((string) ($row['label'] ?? ''));
        $time = trim((string) ($row['value'] ?? ''));
        if ($label === '' || $time === '') {
            continue;
        }

        $hours[] = ['label' => $label, 'value' => $time];
    }

    return $hours;
}

function business_hours_text(): string
{
    $lines = array_map(
        static fn (array $row): string => $row['label'] . ': ' . $row['value'],
        business_hours(),
    );

    return implode("\n", $lines);
}

function business_address_display(): string
{
    $a = config('business_address');
    if (! is_array($a)) {
        return '';
    }

    $street = trim((string) ($a['street'] ?? ''));
    $suburb = trim((string) ($a['suburb'] ?? ''));
    $line1 = $street . ($suburb !== '' ? ($street !== '' ? ', ' : '') . $suburb : '');

    $city = trim((string) ($a['city'] ?? ''));
    $region = trim((string) ($a['region'] ?? ''));
    $postal = trim((string) ($a['postal'] ?? ''));
    $mid = $city;
    if ($region !== '') {
        $mid .= ($mid !== '' ? ', ' : '') . $region;
    }
    if ($postal !== '') {
        $mid .= ($mid !== '' ? ' ' : '') . $postal;
    }

    $country = (string) ($a['country'] ?? '');
    $line3 = $country === 'ZA' ? 'South Africa' : $country;

    $lines = array_filter([$line1, $mid, $line3], static fn ($x) => $x !== '');

    return implode("\n", $lines);
}

/**
 * @param array<string, mixed> $vars
 */
function layout_start(array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require VIEW_PATH . '/layouts/head.php';
    require VIEW_PATH . '/layouts/navbar.php';
}

/**
 * @param array<string, mixed> $vars
 */
function layout_head_only(array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require VIEW_PATH . '/layouts/head.php';
}

function layout_end(): void
{
    require VIEW_PATH . '/layouts/footer.php';
    require VIEW_PATH . '/layouts/layout-end.php';
}

function layout_close(): void
{
    require VIEW_PATH . '/layouts/layout-end.php';
}
