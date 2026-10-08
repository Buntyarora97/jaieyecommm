<?php
$route = '/international-patients';
$page_seo = page_seo($route, 'International Patients | ' . SITE_NAME, 'Guidance for patients travelling to ' . SITE_NAME . ', Delhi for eye care.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>International Patients</span></nav>
  <h1>International Patients</h1><p>Planning your eye care visit to Delhi? We are here to help.</p>
</div></section>
<section class="section"><div class="container split">
  <div class="reveal">
    <span class="eyebrow">Plan Ahead</span>
    <h2>How We Support Visiting Patients</h2>
    <ul class="check-list">
      <li>Pre-visit consultation scheduling and coordination</li>
      <li>Guidance on medical reports to carry for your evaluation</li>
      <li>Clear treatment estimates shared before procedures</li>
      <li>Structured follow-up plans before you travel back</li>
    </ul>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request an Appointment</a>
  </div>
  <div class="contact-card reveal">
    <h3 style="margin-bottom:14px">Reach Our Team</h3>
    <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg></span>
      <div><strong>Phone</strong><a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a></div></div>
    <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
      <div><strong>Email</strong><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></div></div>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
