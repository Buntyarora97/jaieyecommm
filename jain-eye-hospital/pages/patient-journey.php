<?php
$route = '/patient-journey';
$page_seo = page_seo($route, 'Patient Journey | ' . SITE_NAME, 'What to expect when you visit ' . SITE_NAME . ' - from booking to follow-up care.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Patient Journey</span></nav>
  <h1>Your Patient Journey</h1><p>A clear, comfortable process designed around you.</p>
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
  <div class="cards cards--2">
    <div class="card reveal"><h3>Before Your Visit</h3><p>Carry your current spectacles, previous eye reports and a list of medications you take. If you have diabetes or blood pressure concerns, bring recent records.</p></div>
    <div class="card reveal"><h3>Dilatation Advice</h3><p>Some retinal evaluations require pupil dilatation, which can blur near vision for a few hours. It is advisable to bring an attendant and avoid driving immediately after.</p></div>
  </div>
  <div style="text-align:center;margin-top:44px"><a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a></div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
