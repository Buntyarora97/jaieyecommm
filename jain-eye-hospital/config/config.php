<?php
/**
 * Jain Eye Hospital & Laser Centre - Configuration
 * IMPORTANT: Update the credentials below after creating the
 * MySQL database & user in cPanel. Never commit real credentials.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'jaineye_cms');
define('DB_USER', 'jaineye_user');
define('DB_PASS', 'CHANGE_ME_STRONG_PASSWORD');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'Jain Eye Hospital & Laser Centre');
define('SITE_URL', 'https://jaineye.com');      // no trailing slash
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
define('MAIL_ENABLED', false);
define('MAIL_TO', 'info@jaineye.com');
define('MAIL_FROM', 'no-reply@jaineye.com');

// Security
define('SESSION_NAME', 'JEHSESSID');
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);

// Environment: 'production' or 'development'
define('APP_ENV', 'production');

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}
date_default_timezone_set('Asia/Kolkata');
