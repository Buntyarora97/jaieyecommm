<?php
/** Admin layout: sidebar navigation + page shell. */
function admin_nav_items(): array
{
    return [
        'Main' => [
            'dashboard'    => ['Dashboard', 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
            'appointments' => ['Appointments', 'M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 16H5V10h14v10z'],
            'enquiries'    => ['Contact Enquiries', 'M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z'],
        ],
        'Content' => [
            'doctors'      => ['Doctors', 'M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z'],
            'specialities' => ['Specialities', 'M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5z'],
            'treatments'   => ['Treatments', 'M13 2 3 14h7l-1 8 10-12h-7l1-8z'],
            'technologies' => ['Technology', 'M9.4 16.6 4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0 4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z'],
            'blog_posts'   => ['Blog', 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z'],
            'blog_categories' => ['Blog Categories', 'M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z'],
            'faqs'         => ['FAQs', 'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 16h-2v-2h2v2zm1.8-7.3c-.4.5-.9.9-1.3 1.2-.4.3-.5.5-.5 1.1h-2c0-1.2.4-1.8 1-2.3.5-.4 1-.8 1.3-1.3.2-.3.3-.6.3-.9a1.6 1.6 0 0 0-3.2 0h-2a3.6 3.6 0 0 1 7.2 0c0 .8-.3 1.6-.8 2.2z'],
            'testimonials' => ['Testimonials', 'M6 17h3l2-4V7H5v6h3l-2 4zm8 0h3l2-4V7h-6v6h3l-2 4z'],
            'gallery_items'=> ['Gallery', 'M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 11l2.5 3 3.5-4.5L19 16H5l3.5-5z'],
            'reels'        => ['Reels', 'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-2 14.5v-9l6 4.5-6 4.5z'],
            'media_items'  => ['Media & News', 'M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zM8 8h8v2H8V8zm0 4h8v2H8v-2z'],
            'awards'       => ['Awards', 'M12 2a5 5 0 0 1 5 5c0 2-1.2 3.6-2.7 4.4L17 19l-5-2.5L7 19l2.7-7.6A5 5 0 0 1 12 2z'],
            'academics'    => ['Academics', 'M12 3 1 9l4 2.2V16c0 1.7 3 4 7 4s7-2.3 7-4v-4.8L21 10V16h2V9L12 3z'],
            'community'    => ['Community', 'M16 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3zm-8 0a3 3 0 1 0-3-3 3 3 0 0 0 3 3zm0 2c-2.3 0-7 1.2-7 3.5V19h9v-2.5c0-.9.3-1.7.9-2.4A11 11 0 0 0 8 13zm8 0c-.3 0-.6 0-1 .1a4.2 4.2 0 0 1 1 2.9V19h6v-2.5c0-2.3-4.7-3.5-6-3.5z'],
            'insurance'    => ['Insurance', 'M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3z'],
        ],
        'Site' => [
            'pages'        => ['Page Sections', 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z'],
            'navigation'   => ['Navigation', 'M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z'],
            'footer_links' => ['Footer Links', 'M4 15h16v5H4v-5zm0-11h16v7H4V4z'],
            'seo'          => ['SEO Manager', 'M15.5 14h-.8l-.3-.3a6.5 6.5 0 1 0-.7.7l.3.3v.8l5 5 1.5-1.5-5-5zM9.5 14A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14z'],
            'redirects'    => ['Redirects', 'M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z'],
            'media'        => ['Media Library', 'M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 11l2.5 3 3.5-4.5L19 16H5l3.5-5z'],
            'email_templates' => ['Email Templates', 'M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z'],
        ],
        'Administration' => [
            'users'        => ['Users', 'M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z'],
            'roles'        => ['Roles', 'M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3z'],
            'settings'     => ['Settings', 'M19.1 12.9a7 7 0 0 0 0-1.8l2.1-1.6-2-3.5-2.5 1a7 7 0 0 0-1.5-.9L14.8 3.5h-4l-.4 2.6a7 7 0 0 0-1.5.9l-2.5-1-2 3.5 2.1 1.6a7 7 0 0 0 0 1.8l-2.1 1.6 2 3.5 2.5-1a7 7 0 0 0 1.5.9l.4 2.6h4l.4-2.6a7 7 0 0 0 1.5-.9l2.5 1 2-3.5-2.1-1.6zM12 15.5A3.5 3.5 0 1 1 15.5 12 3.5 3.5 0 0 1 12 15.5z'],
            'audit'        => ['Audit Logs', 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z'],
        ],
    ];
}

function admin_header(string $title): void
{
    $admin = current_admin();
    $current = $_GET['module'] ?? 'dashboard';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title) ?> | Admin - <?= e(SITE_NAME) ?></title>
<link rel="icon" type="image/svg+xml" href="<?= asset('img/logo.svg') ?>">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="admin-sidebar__brand">
      <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg>
      <span>Jain Eye Hospital<br><small style="font-weight:600;color:#8FB8A4">Admin CMS</small></span>
    </div>
    <nav class="admin-nav">
      <?php foreach (admin_nav_items() as $group => $items): ?>
      <div class="admin-nav__sep"><?= e($group) ?></div>
      <?php foreach ($items as $key => [$label, $icon]): ?>
      <a href="<?= url('admin/?module=' . $key) ?>" class="<?= $current === $key ? 'is-active' : '' ?>">
        <svg viewBox="0 0 24 24"><path d="<?= e($icon) ?>"/></svg><?= e($label) ?></a>
      <?php endforeach; ?>
      <?php endforeach; ?>
      <div class="admin-nav__sep">Account</div>
      <a href="<?= url('/') ?>" target="_blank"><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/></svg>View Website</a>
      <a href="<?= url('admin/logout.php') ?>"><svg viewBox="0 0 24 24"><path d="M17 7l-1.4 1.4 2.6 2.6H8v2h10.2l-2.6 2.6L17 17l5-5zM4 5h8V3H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8v-2H4V5z"/></svg>Logout</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-topbar">
      <h1><?= e($title) ?></h1>
      <div class="admin-user">Signed in as <strong><?= e($admin['name'] ?? '') ?></strong> (<?= e($admin['role_name'] ?? '') ?>)</div>
    </div>
    <?php if ($msg = flash('success')): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="alert alert--error"><?= e($msg) ?></div><?php endif; ?>
    <?php
}

function admin_footer(): void
{
    echo "</main></div></body></html>";
}
