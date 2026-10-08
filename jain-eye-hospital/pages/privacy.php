<?php
$route = '/privacy-policy';
$page_seo = page_seo($route, 'Privacy Policy | ' . SITE_NAME, 'Privacy policy of ' . SITE_NAME . '.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Privacy Policy</span></nav>
  <h1>Privacy Policy</h1>
</div></section>
<section class="section"><div class="container"><div class="prose" style="max-width:800px">
  <p><?= e(SITE_NAME) ?> respects your privacy. This policy explains how information submitted through this website is handled.</p>
  <h2>Information We Collect</h2>
  <p>When you submit an appointment request or contact form, we collect the details you provide, such as your name, phone number, email address and message, solely to respond to your request.</p>
  <h2>How We Use It</h2>
  <p>Your information is used only to schedule and manage appointments, respond to enquiries and provide requested services. We do not sell or share your personal information with third parties for marketing.</p>
  <h2>Data Security</h2>
  <p>Access to submitted information is restricted to authorised staff, and reasonable technical measures are in place to protect it.</p>
  <h2>Contact</h2>
  <p>For any privacy-related questions, contact us at <?= e(SITE_EMAIL) ?> or call <?= e(SITE_PHONE_1) ?>.</p>
</div></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
