<?php
/**
 * Front controller - routes clean URLs to page templates.
 */
declare(strict_types=1);

// Let PHP's built-in preview server serve real static files directly.
if (PHP_SAPI === 'cli-server') {
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $privatePath = preg_match('#^/(?:config|database|docs|includes|storage|admin/includes)(?:/|$)#i', $requestPath)
        || preg_match('#^/uploads/.*\.(?:php|phtml|phar)(?:$|/)#i', $requestPath)
        || preg_match('#^/(?:\.htaccess|\.gitignore|\.env(?:\..*)?|README\.md|composer\.(?:json|lock))$#i', $requestPath)
        || preg_match('#\.(?:sql|md|log|ini|sh)$#i', $requestPath);
    if ($privatePath) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        exit('Not found');
    }

    $rootPath = realpath(__DIR__);
    $requestedFile = realpath(__DIR__ . $requestPath);
    if ($requestPath !== '/' && $rootPath && $requestedFile
        && str_starts_with($requestedFile, $rootPath . DIRECTORY_SEPARATOR)) {
        if (is_file($requestedFile)
            || (is_dir($requestedFile) && is_file($requestedFile . DIRECTORY_SEPARATOR . 'index.php'))) {
            return false;
        }
    }
}

require __DIR__ . '/config/config.php';

// CMS-managed images and page content must refresh when an admin updates them.
header('Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

session_name(SESSION_NAME);
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);
session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

// Resolve request path relative to install directory
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$path = '/' . ltrim(substr($uri, strlen($base)), '/');
if ($path !== '/' ) {
    $path = rtrim($path, '/');
}

// 301 redirect manager (DB-driven)
$redirect = db_row("SELECT * FROM redirects WHERE old_url = ? AND is_active = 1", [$path]);
if ($redirect) {
    header('Location: ' . $redirect['new_url'], true, (int)($redirect['status_code'] ?: 301));
    exit;
}

// Apache rewrites this URL in production; make the equivalent work in PHP's preview server too.
if ($path === '/sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    exit;
}

// Static routes
$routes = [
    '/'                      => 'home.php',
    '/about-us'              => 'about.php',
    '/doctors'               => 'doctors.php',
    '/specialities'          => 'specialities.php',
    '/treatments'            => 'treatments.php',
    '/technology'            => 'technology.php',
    '/patient-journey'       => 'patient-journey.php',
    '/patient-safety'        => 'patient-safety.php',
    '/insurance-payment'     => 'insurance.php',
    '/international-patients'=> 'international.php',
    '/patient-education'     => 'education.php',
    '/faqs'                  => 'faqs.php',
    '/blog'                  => 'blog.php',
    '/gallery'               => 'gallery.php',
    '/reels'                 => 'reels.php',
    '/videos'                => 'reels.php',
    '/awards'                => 'awards.php',
    '/academics'             => 'academics.php',
    '/community'             => 'community.php',
    '/media-news'            => 'media-news.php',
    '/contact-us'            => 'contact.php',
    '/book-appointment'      => 'appointment.php',
    '/thank-you'             => 'thank-you.php',
    '/sitemap'               => 'sitemap-page.php',
    '/privacy-policy'        => 'privacy.php',
    '/terms-and-conditions'  => 'terms.php',
    '/medical-disclaimer'    => 'medical-disclaimer.php',
    '/accessibility'         => 'accessibility.php',
];

// Dynamic routes
if (preg_match('#^/doctors/([a-z0-9-]+)$#', $path, $m)) {
    $slug = $m[1];
    require __DIR__ . '/pages/doctor.php';
    exit;
}
if (preg_match('#^/specialities/([a-z0-9-]+)$#', $path, $m)) {
    $slug = $m[1];
    require __DIR__ . '/pages/speciality.php';
    exit;
}
if (preg_match('#^/treatments/([a-z0-9-]+)$#', $path, $m)) {
    $slug = $m[1];
    require __DIR__ . '/pages/treatment.php';
    exit;
}
if (preg_match('#^/blog/([a-z0-9-]+)$#', $path, $m)) {
    $slug = $m[1];
    require __DIR__ . '/pages/blog-post.php';
    exit;
}

if (isset($routes[$path])) {
    require __DIR__ . '/pages/' . $routes[$path];
    exit;
}

http_response_code(404);
require __DIR__ . '/pages/404.php';
