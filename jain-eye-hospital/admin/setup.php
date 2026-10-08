<?php
/** One-time first administrator bootstrap; disabled automatically once an account exists. */
require __DIR__ . '/includes/bootstrap.php';

if ((int)db_val("SELECT COUNT(*) FROM admins") > 0) {
    redirect('admin/login.php');
}

$errors = [];
$name = '';
$email = '';
$createdAdminId = 0;
$setupLock = 'jeh_first_admin_v1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $passwordConfirm = (string)($_POST['password_confirm'] ?? '');

    if ($name === '' || mb_strlen($name) > 160) {
        $errors[] = 'Enter your name (up to 160 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($password) < 14 || strlen($password) > 72) {
        $errors[] = 'Choose a password between 14 and 72 characters.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'The passwords do not match.';
    }

    if (!$errors) {
        $lockAcquired = false;
        try {
            $lockAcquired = (int)db_val('SELECT GET_LOCK(?, 8)', [$setupLock]) === 1;
            if (!$lockAcquired) {
                $errors[] = 'First-time setup is busy. Please try again in a moment.';
            } elseif ((int)db_val("SELECT COUNT(*) FROM admins") > 0) {
                $errors[] = 'Administrator setup has already been completed. Please sign in.';
            } else {
                $pdo = db();
                $pdo->beginTransaction();
                db_exec(
                    "INSERT INTO admins (role_id, name, email, password_hash) VALUES (1, ?, ?, ?)",
                    [$name, $email, password_hash($password, PASSWORD_DEFAULT)]
                );
                $createdAdminId = (int)$pdo->lastInsertId();
                $pdo->commit();
            }
        } catch (Throwable $exception) {
            if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('First administrator setup failed: ' . $exception->getMessage());
            $errors[] = 'We could not complete setup. Check the database connection and try again.';
        } finally {
            if ($lockAcquired) {
                db_val('SELECT RELEASE_LOCK(?)', [$setupLock]);
            }
        }
    }

    if ($createdAdminId > 0) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $createdAdminId;
        log_security('admin_first_setup', $email);
        redirect('admin/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>First Administrator Setup | <?= e(SITE_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body>
<div class="login-wrap">
  <form class="login-card" method="post" action="<?= url('admin/setup.php') ?>">
    <div class="login-logo"><img src="<?= asset('img/logo-official.webp') ?>" alt="<?= e(SITE_NAME) ?> — <?= e(SITE_TAGLINE) ?>"></div>
    <h1>Set Up Your Admin Account</h1>
    <p>This one-time setup is available only while no administrator account exists. Choose a unique password between 14 and 72 characters.</p>
    <?php if ($errors): ?>
    <div class="alert alert--error"><ul style="margin-left:18px;list-style:disc"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <?= csrf_field() ?>
    <div class="field"><label for="name">Your name</label>
      <input id="name" name="name" type="text" required maxlength="160" autocomplete="name" value="<?= e($name) ?>"></div>
    <div class="field"><label for="email">Admin email</label>
      <input id="email" name="email" type="email" required maxlength="190" autocomplete="email" value="<?= e($email) ?>"></div>
    <div class="field"><label for="password">Password</label>
      <input id="password" name="password" type="password" required minlength="14" maxlength="72" autocomplete="new-password"></div>
    <div class="field"><label for="password_confirm">Confirm password</label>
      <input id="password_confirm" name="password_confirm" type="password" required minlength="14" maxlength="72" autocomplete="new-password"></div>
    <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">Create Administrator</button>
    <p style="margin-top:18px;font-size:.78rem;color:var(--grey)">Setup closes automatically as soon as the first administrator is created.</p>
  </form>
</div>
</body>
</html>
