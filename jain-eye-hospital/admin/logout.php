<?php
require __DIR__ . '/includes/bootstrap.php';
log_security('admin_logout', current_admin()['email'] ?? '');
$_SESSION = [];
session_destroy();
header('Location: ' . url('admin/login.php'));
exit;
