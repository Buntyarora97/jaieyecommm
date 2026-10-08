<?php
$route = '/terms-and-conditions';
$page_seo = page_seo($route, 'Terms & Conditions | ' . SITE_NAME, 'Terms for using the ' . SITE_NAME . ' website and its enquiry forms.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Terms &amp; Conditions</span></nav>
  <h1>Terms &amp; Conditions</h1>
  <p>Information about using this website and submitting a request.</p>
</div></section>
<section class="section"><div class="container content-narrow">
  <h2>Website information</h2>
  <p>Website content is provided for general information. It does not create a doctor–patient relationship, replace an examination or constitute individual medical advice. A clinician can discuss recommendations after reviewing your situation.</p>
  <h2>Appointment requests</h2>
  <p>Submitting an online form sends a request only. It does not reserve or confirm a date or time. The hospital team must contact you to confirm availability. Please call the hospital if you need to discuss a request.</p>
  <h2>Use of this website</h2>
  <p>Please provide accurate information in enquiry forms and do not submit sensitive medical records or payment details through the website. Website text, photographs and branding may not be reused without permission.</p>
  <h2>Contact</h2>
  <p>For questions about these terms, contact <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a> or call <a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a>.</p>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
