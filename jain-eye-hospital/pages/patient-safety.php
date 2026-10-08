<?php
$route = '/patient-safety';
$page_seo = page_seo($route, 'Patient Safety | ' . SITE_NAME, 'Safety protocols and sterilisation standards at ' . SITE_NAME . '.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Patient Safety</span></nav>
  <h1>Patient Safety</h1><p>Safety is built into every step of your care.</p>
</div></section>
<section class="section"><div class="container">
  <div class="cards">
    <?php
    $items = [
      ['Sterilisation Protocols','Standardised cleaning, disinfection and sterilisation of instruments and clinical areas.'],
      ['Pre-Procedure Checklists','Structured verification before every procedure, including identity, eye and plan confirmation.'],
      ['Informed Consent','Every procedure is explained, along with alternatives and risks, before you consent.'],
      ['Medication Safety','Allergies and current medications are reviewed before any prescription or procedure.'],
      ['Trained Team','Clinical staff follow defined protocols and regular training for safe, consistent care.'],
      ['Emergency Readiness','Protocols and equipment in place to manage urgent situations promptly.'],
    ];
    foreach ($items as $item): ?>
    <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3zm-1.2 14.5-3.3-3.3 1.4-1.4 1.9 1.9 4.6-4.6 1.4 1.4-6 6z"/></svg></div>
      <h3><?= e($item[0]) ?></h3><p><?= e($item[1]) ?></p></div>
    <?php endforeach; ?>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
