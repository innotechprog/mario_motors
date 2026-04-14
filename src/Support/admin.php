<?php

declare(strict_types=1);

const ADMIN_SESSION_KEY = 'mario_motors_admin_user_id';
const ADMIN_DEFAULT_EMAIL = 'admin@mariomotors.local';
const ADMIN_DEFAULT_PASSWORD = 'Admin@123';

function admin_session_boot(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name('mario_motors_admin');
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * @return list<array<string, mixed>>
 */
function admin_seed_users(): array
{
    $now = date(DATE_ATOM);

    return [[
        'id' => 1,
        'name' => 'Administrator',
        'email' => ADMIN_DEFAULT_EMAIL,
        'passwordHash' => password_hash(ADMIN_DEFAULT_PASSWORD, PASSWORD_DEFAULT),
        'role' => 'admin',
        'createdAt' => $now,
        'updatedAt' => $now,
    ]];
}

/**
 * @return list<array<string, mixed>>
 */
function admin_users(): array
{
    $users = storage_read_json('admin-users.json', admin_seed_users());
    if (! is_array($users)) {
        return admin_seed_users();
    }

    return array_values(array_filter($users, static fn (mixed $user): bool => is_array($user) && isset($user['id'], $user['email'], $user['passwordHash'])));
}

/**
 * @param list<array<string, mixed>> $users
 */
function admin_save_users(array $users): void
{
    storage_write_json('admin-users.json', array_values($users));
}

function admin_find_user_by_id(int $id): ?array
{
    foreach (admin_users() as $user) {
        if ((int) $user['id'] === $id) {
            return $user;
        }
    }

    return null;
}

function admin_find_user_by_login(string $login): ?array
{
    $needle = strtolower(trim($login));
    if ($needle === '') {
        return null;
    }

    foreach (admin_users() as $user) {
        $email = strtolower((string) ($user['email'] ?? ''));
        $name = strtolower((string) ($user['name'] ?? ''));
        if ($needle === $email || $needle === $name) {
            return $user;
        }
    }

    return null;
}

function admin_find_user_by_email(string $email): ?array
{
    return admin_find_user_by_login($email);
}

function admin_default_credentials_active(): bool
{
    $user = admin_find_user_by_email(ADMIN_DEFAULT_EMAIL);
    if ($user === null) {
        return false;
    }

    return password_verify(ADMIN_DEFAULT_PASSWORD, (string) ($user['passwordHash'] ?? ''));
}

function admin_current_user(): ?array
{
    admin_session_boot();
    $id = isset($_SESSION[ADMIN_SESSION_KEY]) ? (int) $_SESSION[ADMIN_SESSION_KEY] : 0;
    if ($id < 1) {
        return null;
    }

    return admin_find_user_by_id($id);
}

function admin_is_authenticated(): bool
{
    return admin_current_user() !== null;
}

function admin_require_auth(): array
{
    $user = admin_current_user();
    if ($user === null) {
        admin_set_flash('error', 'Please log in to continue.');
        admin_redirect('login.php');
    }

    return $user;
}

function admin_attempt_login(string $login, string $password): bool
{
    $user = admin_find_user_by_login($login);
    if ($user === null) {
        return false;
    }

    $hash = (string) ($user['passwordHash'] ?? '');
    if ($hash === '' || ! password_verify($password, $hash)) {
        return false;
    }

    admin_session_boot();
    session_regenerate_id(true);
    $_SESSION[ADMIN_SESSION_KEY] = (int) $user['id'];

    return true;
}

function admin_logout(): void
{
    admin_session_boot();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', (bool) ($params['secure'] ?? false), (bool) ($params['httponly'] ?? true));
    }
    session_destroy();
}

function admin_redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function admin_set_flash(string $type, string $message): void
{
    admin_session_boot();
    $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
}

function admin_get_flash(): ?array
{
    admin_session_boot();
    $flash = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);

    return is_array($flash) ? $flash : null;
}

function admin_update_user(int $id, array $payload): ?array
{
    $users = admin_users();
    foreach ($users as $index => $user) {
        if ((int) $user['id'] !== $id) {
            continue;
        }

        $users[$index] = array_merge($user, $payload, ['updatedAt' => date(DATE_ATOM)]);
        admin_save_users($users);

        return $users[$index];
    }

    return null;
}

/**
 * @return list<array<string, mixed>>
 */
function admin_catalog(string $type): array
{
    return isset($GLOBALS[$type]) && is_array($GLOBALS[$type]) ? array_values($GLOBALS[$type]) : [];
}

function admin_find_catalog_item(string $type, int $id): ?array
{
    foreach (admin_catalog($type) as $item) {
        if ((int) ($item['id'] ?? 0) === $id) {
            return $item;
        }
    }

    return null;
}

function admin_upsert_catalog_item(string $type, ?int $id, array $payload): int
{
    $items = admin_catalog($type);

    if ($id === null) {
        $payload['id'] = next_catalog_id($items);
        $items[] = $payload;
        save_catalog_items($type, $items);

        return (int) $payload['id'];
    }

    foreach ($items as $index => $item) {
        if ((int) ($item['id'] ?? 0) !== $id) {
            continue;
        }

        $payload['id'] = $id;
        $items[$index] = $payload;
        save_catalog_items($type, $items);

        return $id;
    }

    $payload['id'] = $id;
    $items[] = $payload;
    save_catalog_items($type, $items);

    return $id;
}

function admin_delete_catalog_item(string $type, int $id): bool
{
    $items = admin_catalog($type);
    $filtered = array_values(array_filter($items, static fn (array $item): bool => (int) ($item['id'] ?? 0) !== $id));
    if (count($filtered) === count($items)) {
        return false;
    }

    save_catalog_items($type, $filtered);

    return true;
}