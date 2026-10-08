<?php
/** Admin bootstrap: config, secure session, DB, auth. */
require dirname(__DIR__, 2) . '/config/config.php';

session_name(SESSION_NAME);
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);
session_start();

require dirname(__DIR__, 2) . '/includes/db.php';
require dirname(__DIR__, 2) . '/includes/functions.php';
require __DIR__ . '/layout.php';
