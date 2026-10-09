<?php
$route = '/patient-journey';
$page_seo = page_seo($route, 'Patient Journey | ' . SITE_NAME, 'What to expect when you visit ' . SITE_NAME . ' - from booking to follow-up care.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Patient Journey</span></nav>
  <h1>Your Patient Journey</h1><p>Practical guidance for planning an appointment, preparing for a consultation and understanding what to ask next.</p>
</div></section>
<section class="section"><div class="container">
  <div class="timeline" style="margin-bottom:60px">
    <?php
    $steps = [
      ['Request Appointment','Book online or call us. Our team confirms your slot and shares any preparation instructions.'],
      ['Eye Evaluation','Meet your specialist for a detailed history, vision testing and any required diagnostics.'],
      ['Treatment Discussion','Understand your diagnosis and all suitable options, with time for your questions.'],
      ['Follow-Up Guidance','Receive clear after-care instructions and scheduled review visits.'],
    ];
    foreach ($steps as $i => $s): ?>
    <div class="timeline__item reveal"><div class="timeline__dot"><?= $i+1 ?></div><h3><?= e($s[0]) ?></h3><p><?= e($s[1]) ?></p></div>
    <?php endforeach; ?>
  </div>
  <div class="patient-prep">
    <div class="patient-prep__heading">
      <span class="eyebrow">A little preparation helps</span>
      <h2>Before you come in</h2>
      <p>Appointment needs vary. If you have been asked to prepare for a test or procedure, follow the instructions given to you by the hospital team.</p>
    </div>
    <div class="patient-prep__grid">
      <article><span class="patient-prep__icon" aria-hidden="true">01</span><h3>Bring useful records</h3><p>Bring your current spectacles, previous eye reports and a list of medicines or eye drops you use. Relevant health records can help the clinician understand your history.</p></article>
      <article><span class="patient-prep__icon" aria-hidden="true">02</span><h3>Plan your questions</h3><p>Note what has changed in your vision, when you noticed it and what you would like to understand. You can ask the clinician to explain terms in simpler language.</p></article>
      <article><span class="patient-prep__icon" aria-hidden="true">03</span><h3>Ask about the visit</h3><p>Call ahead if you need help with directions, appointment details or preparation. Ask the team whether an attendant may be useful for your planned visit.</p></article>
      <article><span class="patient-prep__icon" aria-hidden="true">04</span><h3>Understand follow-up</h3><p>Before leaving, check what happens next, how to use any prescribed medicines and how to contact the care team with a question.</p></article>
    </div>
    <p class="patient-prep__note">These are general planning suggestions, not personal medical instructions. Follow the advice given for your own examination or treatment.</p>
  </div>
  <div class="patient-journey-cta"><div><h2>Need help arranging a visit?</h2><p>Contact our team for appointment information or directions to the hospital.</p></div><div class="btn-row"><a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request Appointment</a><a class="btn btn--outline" href="<?= url('contact-us') ?>">Contact &amp; Directions</a></div></div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
