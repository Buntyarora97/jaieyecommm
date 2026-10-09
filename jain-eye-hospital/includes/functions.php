<?php
/** Shared helper functions: security, CSRF, SEO, content helpers. */

/* ---------------- Output / escaping ---------------- */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $base = rtrim($scriptDir, '/');
    if (preg_match('#/admin$#', $base)) {
        $base = substr($base, 0, -6);
    }
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function uploads_url(string $path): string
{
    return url('uploads/' . ltrim($path, '/'));
}

function safe_https_url(?string $value): string
{
    $value = trim((string)$value);
    if ($value === '' || !filter_var($value, FILTER_VALIDATE_URL)) return '';
    $parts = parse_url($value);
    if (!$parts || strtolower((string)($parts['scheme'] ?? '')) !== 'https' || empty($parts['host'])) return '';
    if (isset($parts['user']) || isset($parts['pass'])) return '';
    return $value;
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
    $path = trim((string)$path);
    if ($path === '') {
        $classAttr = $class !== '' ? ' class="' . e('image-placeholder ' . $class) . '"' : ' class="image-placeholder"';
        $label = $alt !== '' ? $alt . '. Photo not yet provided.' : 'Photo not yet provided.';
        return '<div' . $classAttr . ' role="img" aria-label="' . e($label) . '"><span aria-hidden="true">Photo not yet provided</span></div>';
    }
    $src = str_starts_with($path, 'assets/') ? url($path) : uploads_url($path);
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

/**
 * Reusable, CMS-aware image and short description for navigation cards.
 * Uses the published page record where possible and authentic hospital
 * photography for fixed informational routes.
 */
function navigation_link_details(string $href, string $label = ''): array
{
    static $content = null;
    if ($content === null) {
        $content = [
            'images' => [
                '/' => 'assets/img/hospital-pic-2.webp',
                '/about-us' => 'assets/img/hospital-pic-2.webp',
                '/about-us#mission-vision' => 'assets/img/hospital-pic-8.webp',
                '/about-us#leadership' => '',
                '/about-us#our-team' => 'assets/img/hospital-pic-7.webp',
                '/gallery?category=infrastructure' => 'assets/img/hospital-pic-8.webp',
                '/awards' => 'assets/img/hospital-pic-2.webp',
                '/academics' => 'assets/img/surgeon-at-the-operating-microscope.webp',
                '/community' => 'assets/img/hospital-pic-15.webp',
                '/doctors' => '',
                '/specialities' => '',
                '/treatments' => '',
                '/technology' => '',
                '/technology#diagnostics' => 'assets/img/hospital-pic-16.webp',
                '/technology#laser' => 'assets/img/hospital-pic-17.webp',
                '/technology#surgical' => 'assets/img/surgeon-at-the-operating-microscope.webp',
                '/technology#facility' => 'assets/img/hospital-pic-8.webp',
                '/patient-journey' => 'assets/img/hospital-pic-15.webp',
                '/patient-education' => 'assets/img/hospital-pic-15.webp',
                '/faqs' => 'assets/img/hospital-pic-15.webp',
                '/patient-safety' => 'assets/img/ophthalmic-microsurgery-preparation.webp',
                '/insurance-payment' => 'assets/img/hospital-pic-8.webp',
                '/international-patients' => 'assets/img/hospital-pic-15.webp',
                '/reels' => 'assets/img/hospital-pic-15.webp',
                '/gallery?category=technology' => 'assets/img/hospital-pic-17.webp',
                '/media-news' => 'assets/img/hospital-pic-8.webp',
                '/contact-us' => 'assets/img/hospital-pic-2.webp',
                '/book-appointment' => 'assets/img/hospital-pic-15.webp',
            ],
            'descriptions' => [
                '/' => SITE_NAME . ' in ' . SITE_AREA . '.',
                '/about-us' => 'Learn about the hospital and its patient-centred approach.',
                '/about-us#mission-vision' => 'The mission, vision and values that guide the hospital.',
                '/about-us#leadership' => 'View the doctors listed by the hospital.',
                '/about-us#our-team' => 'Find practical information for planning a visit.',
                '/gallery?category=infrastructure' => 'Photographs of the hospital and its spaces.',
                '/awards' => 'Verified recognition information, when available.',
                '/academics' => 'Confirmed academic and training updates.',
                '/community' => 'Published community initiative updates.',
                '/doctors' => 'Meet the doctors listed by the hospital.',
                '/specialities' => 'Explore the eye-care specialities listed by the hospital.',
                '/treatments' => 'Explore published treatment and procedure information.',
                '/technology' => 'View hospital photographs of clinical spaces and equipment.',
                '/patient-journey' => 'What to bring and what to ask when planning a visit.',
                '/patient-education' => 'General eye-care education and patient resources.',
                '/faqs' => 'Answers to common questions about planning a visit.',
                '/patient-safety' => 'Questions to discuss with your clinician.',
                '/insurance-payment' => 'Ask the hospital about current payment information.',
                '/international-patients' => 'Contact the hospital to discuss visit planning.',
                '/reels' => 'Watch patient education videos when available.',
                '/gallery?category=technology' => 'Photographs of equipment at the hospital.',
                '/media-news' => 'Hospital news and approved updates.',
                '/contact-us' => 'Contact details and directions to the hospital.',
                '/book-appointment' => 'Send an appointment request to the hospital team.',
            ],
            'technology_categories' => [],
        ];

        foreach (db_all("SELECT slug, name, photo, designation, specialisation, biography FROM doctors WHERE status='published' ORDER BY position") as $doctor) {
            $route = '/doctors/' . $doctor['slug'];
            $content['images'][$route] = $doctor['photo'] ?: '';
            $about = trim(implode(' · ', array_filter([$doctor['designation'], $doctor['specialisation']])));
            $content['descriptions'][$route] = $about ?: excerpt((string)($doctor['biography'] ?? ''), 115);
        }

        foreach (db_all("SELECT slug, name, image, short_description FROM specialities WHERE status='published' ORDER BY position") as $speciality) {
            $route = '/specialities/' . $speciality['slug'];
            $content['images'][$route] = $speciality['image'] ?: '';
            $content['descriptions'][$route] = $speciality['short_description'] ?: 'Read about ' . $speciality['name'] . ' and request a consultation.';
            if (empty($content['images']['/specialities'])) $content['images']['/specialities'] = $speciality['image'] ?: '';
        }

        foreach (db_all("SELECT slug, name, image, short_description FROM treatments WHERE status='published' ORDER BY position") as $treatment) {
            $route = '/treatments/' . $treatment['slug'];
            $content['images'][$route] = $treatment['image'] ?: '';
            $content['descriptions'][$route] = $treatment['short_description'] ?: 'Read information about ' . $treatment['name'] . '.';
            if (empty($content['images']['/treatments'])) $content['images']['/treatments'] = $treatment['image'] ?: '';
        }

        foreach (db_all("SELECT slug, name, category, image, short_description FROM technologies WHERE status='published' ORDER BY position") as $technology) {
            if (empty($content['images']['/technology'])) $content['images']['/technology'] = $technology['image'] ?: '';
            if (!empty($technology['category']) && empty($content['technology_categories'][$technology['category']])) {
                $content['technology_categories'][$technology['category']] = $technology['image'] ?: '';
            }
            $content['images']['/technology/' . $technology['slug']] = $technology['image'] ?: '';
        }
        if (empty($content['images']['/doctors'])) {
            $content['images']['/doctors'] = 'assets/img/hospital-pic-15.webp';
        }
        if (empty($content['images']['/about-us#leadership'])) {
            $content['images']['/about-us#leadership'] = $content['images']['/doctors'];
        }
    }

    $parts = parse_url($href) ?: [];
    $path = $parts['path'] ?? '/';
    $key = $path;
    if (!empty($parts['query'])) $key .= '?' . $parts['query'];
    if (!empty($parts['fragment'])) $key .= '#' . $parts['fragment'];
    $image = $content['images'][$key] ?? $content['images'][$path] ?? '';
    $description = $content['descriptions'][$key] ?? $content['descriptions'][$path] ?? '';

    if (!$image && $path === '/technology' && !empty($parts['fragment'])) {
        $image = $content['technology_categories'][$parts['fragment']] ?? '';
    }
    if (!$image) $image = 'assets/img/hospital-pic-15.webp';
    if (!$description) {
        $description = $label !== '' ? 'Explore ' . $label . ' at ' . SITE_NAME . '.' : 'Learn more about ' . SITE_NAME . '.';
    }

    return ['image' => $image, 'description' => excerpt($description, 115)];
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
