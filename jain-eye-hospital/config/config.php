<?php
/**
 * Jain Eye Hospital & Laser Centre - Configuration
 * IMPORTANT: Update the credentials below after creating the
 * MySQL database & user in cPanel. Never commit real credentials.
 */

$env = static function (string $key, string $default): string {
    $value = getenv($key);
    return $value === false ? $default : $value;
};

define('DB_HOST', $env('DB_HOST', 'localhost'));
define('DB_NAME', $env('DB_NAME', 'jaineye_cms'));
define('DB_USER', $env('DB_USER', 'jaineye_user'));
define('DB_PASS', $env('DB_PASS', 'CHANGE_ME_STRONG_PASSWORD'));
define('DB_CHARSET', $env('DB_CHARSET', 'utf8mb4'));

define('SITE_NAME', 'Jain Eye Hospital & Laser Centre');
define('SITE_URL', rtrim($env('SITE_URL', 'https://jaineye.com'), '/'));
define('SITE_TAGLINE', 'Advanced Super-Speciality Eye Care');

// Contact
define('SITE_PHONE_1', '011 4378 4377');
define('SITE_PHONE_2', '09643536373');
define('SITE_PHONE_3', '9643 900 900');
define('SITE_EMAIL', 'info@jaineye.com');
define('SITE_ADDRESS', 'AG-152, near Richi Rich Banquet, Block AG, Poorbi Shalimar Bagh, Shalimar Bagh, Delhi, 110088, India');
define('SITE_AREA', 'Shalimar Bagh, Delhi');
define('SITE_INSTAGRAM', 'https://www.instagram.com/jaineyehospitalandlaser/');
define('SITE_FACEBOOK', 'https://www.facebook.com/Jaineyehospitalshalimarbagh');
define('SITE_YOUTUBE', 'https://www.youtube.com/@JAINEYEHOSPITALLASERCENTRE');
define('SITE_MAPS_URL', 'https://www.google.com/maps/search/?api=1&query=Jain+Eye+Hospital+Shalimar+Bagh+Delhi');
define('SITE_MAPS_EMBED', 'https://www.google.com/maps?q=AG-152%20Shalimar%20Bagh%20Delhi%20110088&output=embed');

// Email (set MAIL_ENABLED true after configuring mail on hosting)
define('MAIL_ENABLED', filter_var($env('MAIL_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN));
define('MAIL_TO', $env('MAIL_TO', 'info@jaineye.com'));
define('MAIL_FROM', $env('MAIL_FROM', 'no-reply@jaineye.com'));

// Security
define('SESSION_NAME', 'JEHSESSID');
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);

// Environment: 'production' or 'development'
define('APP_ENV', $env('APP_ENV', 'production'));

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}
date_default_timezone_set('Asia/Kolkata');
