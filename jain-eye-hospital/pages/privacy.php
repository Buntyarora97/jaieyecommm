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
  <p>This page describes the information this website’s appointment and contact forms are designed to collect. The hospital should review and approve this policy before launch.</p>
  <h2>Information You Submit</h2>
  <p>Appointment requests may include your name, contact details, preferred date and time, and selected doctor or speciality. Contact requests may include your name, contact details, subject and message. The forms also record your consent. Please do not submit medical records, payment information or detailed health information through these forms.</p>
  <h2>How It Is Used</h2>
  <p>Submissions are stored in the website database so authorised hospital administrators can review and respond. If email notifications are configured and enabled, submission details may also be sent to the hospital’s designated email address.</p>
  <h2>Website Operation</h2>
  <p>The site uses a session cookie for form security and administrator sign-in. Pages load fonts from Google Fonts and the contact page may display a Google Maps embed; those services may receive technical connection information when your browser loads them. Please see their own privacy information for details.</p>
  <h2>Access and Retention</h2>
  <p>Hospital administrators with website access and the hosting provider needed to operate the site may process submitted information. The hospital should define and communicate its retention and deletion practices before launch.</p>
  <h2>Data Security</h2>
  <p>Administrator access requires an account, and the site uses request protections and access controls. No website can guarantee absolute security; contact the hospital promptly if you believe information was submitted in error.</p>
  <h2>Contact</h2>
  <p>For any privacy-related questions, contact us at <?= e(SITE_EMAIL) ?> or call <?= e(SITE_PHONE_1) ?>.</p>
</div></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
