<?php
$route = '/medical-disclaimer';
$page_seo = page_seo($route, 'Medical Disclaimer | ' . SITE_NAME, 'Important information about medical content on the ' . SITE_NAME . ' website.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Medical Disclaimer</span></nav>
  <h1>Medical Disclaimer</h1>
  <p>Read this information before relying on health content on this website.</p>
</div></section>
<section class="section"><div class="container content-narrow">
  <h2>General information only</h2>
  <p>Information on this website is educational and general in nature. It cannot diagnose an eye condition, recommend treatment for an individual, or replace advice from a qualified clinician who has examined you.</p>
  <h2>Care decisions</h2>
  <p>Symptoms and treatment choices vary from person to person. Contact an eye-care professional for advice about your circumstances. Do not delay or discontinue medical care because of information on this website.</p>
  <h2>Urgent symptoms</h2>
  <p>Sudden vision loss, severe eye pain, a new curtain or shadow in your vision, or an eye injury may require urgent medical assessment. Seek appropriate emergency care rather than waiting for a website response.</p>
  <h2>Appointments</h2>
  <p>Online appointment forms are requests, not confirmation of an appointment or a substitute for urgent care. The hospital team will contact you about availability.</p>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
