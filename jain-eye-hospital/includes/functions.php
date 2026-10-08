<?php
/** Shared helper functions: security, CSRF, SEO, content helpers. */

/* ---------------- Output / escaping ---------------- */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    if (strpos($path, '/admin') === 0) {
        $base = rtrim(dirname($base), '/\\');
    }
    return ($base === '' ? '' : $base) . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function uploads_url(string $path): string
{
    return url('uploads/' . ltrim($path, '/'));
}

function redirect(string $to, int $code = 302): void
{
    if (!preg_match('#^https?://#i', $to)) {
        $to = url(ltrim($to, '/'));
    }
    header('Location: ' . $to, true, $code);
    exit;
}

/* ---------------- CSRF ---------------- */
function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Security check failed. Please go back and try again.');
    }
}

/* ---------------- Flash messages ---------------- */
function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $msg = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

/* ---------------- Strings ---------------- */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'item';
}

function excerpt(string $text, int $length = 160): string
{
    $text = trim(strip_tags($text));
    if (mb_strlen($text) <= $length) return $text;
    return rtrim(mb_substr($text, 0, $length)) . '…';
}

function format_date(?string $date, string $format = 'd M Y'): string
{
    if (!$date) return '';
    $ts = strtotime($date);
    return $ts ? date($format, $ts) : '';
}

/* ---------------- Settings ---------------- */
function setting(string $key, string $default = ''): string
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        foreach (db_all("SELECT setting_key, setting_value FROM site_settings") as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings[$key] ?? $default;
}

/* ---------------- SEO ---------------- */
function seo_for(string $route): array
{
    $row = db_row("SELECT * FROM seo_metadata WHERE route = ?", [$route]);
    return $row ?: [];
}

function page_seo(string $route, string $title, string $description): array
{
    $meta = seo_for($route);
    return [
        'title'       => $meta['meta_title'] ?? $title,
        'description' => $meta['meta_description'] ?? $description,
        'canonical'   => $meta['canonical_url'] ?? (SITE_URL . $route),
        'og_image'    => $meta['og_image'] ?? '',
        'robots'      => $meta['robots'] ?? 'index,follow',
    ];
}

/* ---------------- Content helpers ---------------- */
function image_or_placeholder(?string $path, string $class = '', string $alt = '', bool $lazy = true): string
{
    $src = $path ? uploads_url($path) : asset('img/placeholder.svg');
    $loading = $lazy ? ' loading="lazy" decoding="async"' : ' fetchpriority="high"';
    return '<img src="' . e($src) . '" alt="' . e($alt) . '"' . ($class ? ' class="' . e($class) . '"' : '') . $loading . '>';
}

function appointment_statuses(): array
{
    return ['New', 'Contacted', 'Pending Confirmation', 'Confirmed', 'Completed', 'Cancelled'];
}

function generate_reference(): string
{
    return 'JEH-' . date('ym') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

/* ---------------- Email ---------------- */
function notify_staff(string $subject, string $body): void
{
    if (!MAIL_ENABLED) return;
    $headers = 'From: ' . MAIL_FROM . "\r\n" . 'Content-Type: text/plain; charset=utf-8';
    @mail(MAIL_TO, $subject, $body, $headers);
}

/* ---------------- Admin auth ---------------- */
function current_admin(): ?array
{
    if (empty($_SESSION['admin_id'])) return null;
    static $admin = null;
    if ($admin === null) {
        $admin = db_row(
            "SELECT a.*, r.name AS role_name FROM admins a
             LEFT JOIN roles r ON r.id = a.role_id
             WHERE a.id = ? AND a.is_active = 1",
            [$_SESSION['admin_id']]
        );
    }
    return $admin ?: null;
}

function require_login(): void
{
    if (!current_admin()) {
        redirect('admin/login.php');
    }
}

function has_permission(string $permission): bool
{
    $admin = current_admin();
    if (!$admin) return false;
    if ((int)$admin['role_id'] === 1) return true; // Super Admin
    static $perms = null;
    if ($perms === null) {
        $perms = array_column(db_all(
            "SELECT p.name FROM permissions p
             JOIN role_permissions rp ON rp.permission_id = p.id
             WHERE rp.role_id = ?",
            [$admin['role_id']]
        ), 'name');
    }
    return in_array($permission, $perms, true);
}

function log_activity(string $action, string $module = '', string $details = ''): void
{
    $admin = current_admin();
    db_exec(
        "INSERT INTO admin_activity_logs (admin_id, action, module, details, ip_address, created_at)
         VALUES (?,?,?,?,?,NOW())",
        [$admin['id'] ?? null, $action, $module, $details, $_SERVER['REMOTE_ADDR'] ?? '']
    );
}

function log_security(string $event, string $details = ''): void
{
    db_exec(
        "INSERT INTO security_logs (event, details, ip_address, user_agent, created_at)
         VALUES (?,?,?,?,NOW())",
        [$event, $details, $_SERVER['REMOTE_ADDR'] ?? '', substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)]
    );
}

/* ---------------- Navigation (DB driven) ---------------- */
function nav_tree(): array
{
    static $tree = null;
    if ($tree !== null) return $tree;
    $items = db_all(
        "SELECT * FROM navigation_items
         WHERE is_active = 1 AND menu_location = 'header'
         ORDER BY parent_id, position"
    );
    $tree = ['top' => [], 'children' => []];
    foreach ($items as $item) {
        if ($item['parent_id']) {
            $tree['children'][$item['parent_id']][] = $item;
        } else {
            $tree['top'][] = $item;
        }
    }
    // attach mega columns
    foreach ($tree['top'] as &$top) {
        if ((int)$top['has_mega'] === 1) {
            $cols = db_all(
                "SELECT * FROM mega_menu_columns WHERE nav_item_id = ? AND is_active = 1 ORDER BY position",
                [$top['id']]
            );
            foreach ($cols as &$col) {
                $col['links'] = json_decode($col['links_json'] ?? '[]', true) ?: [];
            }
            $top['mega'] = $cols;
        }
    }
    return $tree;
}

function footer_columns(): array
{
    $rows = db_all("SELECT * FROM footer_links WHERE is_active = 1 ORDER BY column_group, position");
    $cols = [];
    foreach ($rows as $row) {
        $cols[$row['column_group']][] = $row;
    }
    return $cols;
}
