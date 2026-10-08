<?php
$route = '/accessibility';
$page_seo = page_seo($route, 'Accessibility | ' . SITE_NAME, 'Accessibility information and contact details for the ' . SITE_NAME . ' website.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Accessibility</span></nav>
  <h1>Accessibility</h1>
  <p>We want the information on this website to be easy to use.</p>
</div></section>
<section class="section"><div class="container content-narrow">
  <h2>Using the website</h2>
  <p>The site is designed to work on mobile and desktop screens and includes labelled form fields, keyboard-operable navigation and descriptive text for important images. Some documents or third-party map content may not provide the same experience.</p>
  <h2>Need help?</h2>
  <p>If you have difficulty using a page or submitting a form, contact us and tell us which page you were trying to use. You can call <a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a> or email <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.</p>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
