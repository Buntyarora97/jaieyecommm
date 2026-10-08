<?php
require __DIR__ . '/includes/bootstrap.php';

if (current_admin()) { redirect('admin/'); }

$error = '';
$locked = false;

// simple lockout tracking
if (!empty($_SESSION['login_lock_until']) && time() < $_SESSION['login_lock_until']) {
    $locked = true;
    $error = 'Too many failed attempts. Please try again in ' . (int)(($_SESSION['login_lock_until'] - time()) / 60 + 1) . ' minute(s).';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {
    csrf_check();
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $admin = db_row("SELECT * FROM admins WHERE email = ? AND is_active = 1", [$email]);
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        unset($_SESSION['login_attempts'], $_SESSION['login_lock_until']);
        db_exec("UPDATE admins SET last_login_at = NOW() WHERE id = ?", [$admin['id']]);
        log_security('admin_login_success', $email);
        redirect('admin/');
    }

    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    log_security('admin_login_failed', $email);
    if ($_SESSION['login_attempts'] >= LOGIN_MAX_ATTEMPTS) {
        $_SESSION['login_lock_until'] = time() + LOGIN_LOCKOUT_MINUTES * 60;
        $_SESSION['login_attempts'] = 0;
        $error = 'Too many failed attempts. Account access paused for ' . LOGIN_LOCKOUT_MINUTES . ' minutes.';
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Admin Login | <?= e(SITE_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body>
<div class="login-wrap">
  <form class="login-card" method="post" action="<?= url('admin/login.php') ?>">
    <div class="login-logo"><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg></div>
    <h1>Admin Login</h1>
    <p><?= e(SITE_NAME) ?> - Content Management System</p>
    <?php if ($error): ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <div class="field"><label for="email">Email</label>
      <input id="email" type="email" name="email" required autocomplete="username"></div>
    <div class="field"><label for="password">Password</label>
      <input id="password" type="password" name="password" required autocomplete="current-password"></div>
    <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center" <?= $locked ? 'disabled' : '' ?>>Sign In</button>
    <p style="margin-top:18px;font-size:.75rem;color:var(--grey)">Default: admin@jaineye.com / Admin@123 — change immediately after first login.</p>
  </form>
</div>
</body>
</html>
