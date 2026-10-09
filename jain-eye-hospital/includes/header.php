<?php
/** Global header: SEO meta, utility bar, main nav with dropdowns + mega menus. */
$nav = nav_tree();
$seo = $page_seo ?? page_seo($route ?? '/', $seo_title ?? SITE_NAME, $seo_desc ?? setting('hero_description'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title><?= e($seo['title']) ?></title>
<meta name="description" content="<?= e($seo['description']) ?>">
<meta name="robots" content="<?= e($seo['robots']) ?>">
<link rel="canonical" href="<?= e($seo['canonical']) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($seo['title']) ?>">
<meta property="og:description" content="<?= e($seo['description']) ?>">
<meta property="og:url" content="<?= e($seo['canonical']) ?>">
<?php if ($seo['og_image']): ?>
<meta property="og:image" content="<?= e($seo['og_image']) ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/webp" href="<?= asset('img/logo-official.webp') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600&family=Noto+Sans+Devanagari:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('css/style.css') . '?v=' . filemtime(__DIR__ . '/../assets/css/style.css')) ?>">
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => ['Hospital', 'MedicalOrganization'],
    'name' => SITE_NAME,
    'url' => SITE_URL,
    'logo' => SITE_URL . '/assets/img/logo-official.webp',
    'telephone' => [SITE_PHONE_1, SITE_PHONE_2, SITE_PHONE_3],
    'email' => SITE_EMAIL,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => SITE_ADDRESS,
        'addressLocality' => 'Shalimar Bagh, Delhi',
        'postalCode' => '110088',
        'addressCountry' => 'IN',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?php if (!empty($extra_head)) echo $extra_head; ?>
</head>
<body>
<a class="skip-link" href="#main">Skip to main content</a>

<div class="utility-bar">
  <div class="container">
    <span class="utility-bar__left"><?= e(SITE_AREA) ?></span>
    <div class="utility-bar__right">
      <a href="tel:+911143784377">
        <svg class="icon" viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg>
        <?= e(SITE_PHONE_1) ?></a>
      <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>
      <span class="utility-bar__social">
        <a href="<?= e(SITE_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1s-3.6 0-4.9-.1c-3.3-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8C2.4 4 4 2.4 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2zm0 3.6a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.4-10.4a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9z"/></svg></a>
        <a href="<?= e(SITE_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2.1-.1-2.1 0-3.6 1.3-3.6 3.7V11H8.2v3h2.5v7h2.8z"/></svg></a>
        <a href="<?= e(SITE_YOUTUBE) ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8c1.6.4 7.8.4 7.8.4s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3L10 15z"/></svg></a>
      </span>
    </div>
  </div>
</div>

<header class="site-header">
  <div class="container">
    <nav class="nav" aria-label="Main navigation">
      <a class="nav__logo" href="<?= url('/') ?>" aria-label="<?= e(SITE_NAME) ?> - Home">
        <img src="<?= asset('img/logo-official.webp') ?>" alt="<?= e(SITE_NAME) ?> — <?= e(SITE_TAGLINE) ?>" width="256" height="64">
      </a>

      <ul class="nav__menu">
        <?php foreach ($nav['top'] as $item):
          $children = $nav['children'][$item['id']] ?? [];
          $mega = $item['mega'] ?? [];
          $hasSub = $children || $mega;
        ?>
        <li class="nav__item<?= $hasSub ? ' nav__item--has-sub' : '' ?>">
          <a class="nav__link" href="<?= url(ltrim($item['url'], '/')) ?>"
             <?php if ($hasSub): ?>aria-haspopup="true" aria-expanded="false"<?php endif; ?>>
            <?= e($item['label']) ?><?php if ($hasSub): ?><span class="caret" aria-hidden="true"></span><?php endif; ?>
          </a>

          <?php if ($mega): ?>
          <div class="mega mega--4" role="menu">
            <?php foreach ($mega as $col): ?>
              <?php if ($col['column_type'] === 'featured'): ?>
              <div class="mega__featured">
                <h4><?= e($col['heading']) ?></h4>
                <p><?= e($col['text']) ?></p>
                <?php if ($col['cta_url']): ?>
                <a class="btn btn--primary" href="<?= url(ltrim($col['cta_url'], '/')) ?>"><?= e($col['cta_label']) ?></a>
                <?php endif; ?>
              </div>
              <?php else: ?>
              <div class="mega__col">
                <h4><?= e($col['title']) ?></h4>
                <ul>
                  <?php foreach ($col['links'] as $link): ?>
                  <li><a href="<?= url(ltrim($link['url'], '/')) ?>"><?= e($link['label']) ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <?php elseif ($children): ?>
          <div class="dropdown" role="menu">
            <?php foreach ($children as $child): ?>
            <a href="<?= url(ltrim($child['url'], '/')) ?>"><?= e($child['label']) ?></a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="nav__cta">
        <a class="nav__call" href="tel:+911143784377" aria-label="Call <?= e(SITE_PHONE_1) ?>">
          <svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg>
        </a>
        <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
        <button class="nav__toggle" id="navToggle" aria-label="Open menu" aria-controls="drawer"><span></span></button>
      </div>
    </nav>
  </div>
</header>

<!-- Mobile drawer -->
<div class="drawer" id="drawer" aria-hidden="true">
  <div class="drawer__overlay" data-close-drawer></div>
  <div class="drawer__panel">
    <div class="drawer__head">
      <img src="<?= asset('img/logo-official.webp') ?>" alt="<?= e(SITE_NAME) ?> — <?= e(SITE_TAGLINE) ?>">
      <button class="drawer__close" data-close-drawer aria-label="Close menu">&times;</button>
    </div>
    <?php foreach ($nav['top'] as $item):
      $children = $nav['children'][$item['id']] ?? [];
      $mega = $item['mega'] ?? [];
      $hasSub = $children || $mega;
    ?>
      <?php if (!$hasSub): ?>
      <details><summary style="list-style:none"><a href="<?= url(ltrim($item['url'],'/')) ?>" style="color:inherit"><?= e($item['label']) ?></a></summary></details>
      <?php else: ?>
      <details>
        <summary><?= e($item['label']) ?></summary>
        <div class="drawer__links">
          <a href="<?= url(ltrim($item['url'], '/')) ?>"><strong><?= e($item['label']) ?> — Overview</strong></a>
          <?php foreach ($children as $child): ?>
          <a href="<?= url(ltrim($child['url'], '/')) ?>"><?= e($child['label']) ?></a>
          <?php endforeach; ?>
          <?php foreach ($mega as $col): ?>
            <?php if ($col['column_type'] === 'links'): ?>
            <h5><?= e($col['title']) ?></h5>
            <?php foreach ($col['links'] as $link): ?>
            <a href="<?= url(ltrim($link['url'], '/')) ?>"><?= e($link['label']) ?></a>
            <?php endforeach; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </details>
      <?php endif; ?>
    <?php endforeach; ?>
    <div class="drawer__cta">
      <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
      <a class="btn btn--outline" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a>
    </div>
  </div>
</div>

<main id="main">
