<?php
$route = '/insurance-payment';
$page_seo = page_seo($route, 'Insurance & Payment | ' . SITE_NAME, 'Insurance guidance and payment options at ' . SITE_NAME . '.');
$partners = db_all("SELECT * FROM insurance_partners WHERE is_active=1 ORDER BY position");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Insurance &amp; Payment</span></nav>
  <h1>Insurance &amp; Payment</h1><p>Transparent guidance on billing, insurance and payment options.</p>
</div></section>
<section class="section"><div class="container">
  <div class="cards cards--2">
    <div class="card reveal"><h3>Insurance &amp; Cashless</h3><p>Many eye procedures are covered under health insurance policies. Please share your policy or TPA details with our front desk before your procedure, and our team will guide you through the approval and documentation process.</p></div>
    <div class="card reveal"><h3>Payment Options</h3><p>We accept standard payment methods including cash, cards and UPI. A detailed estimate is shared before any planned procedure so you can decide with clarity.</p></div>
  </div>
  <?php if ($partners): ?>
  <div class="section-head reveal" style="margin-top:56px"><span class="eyebrow">Insurance Partners</span><h2>TPAs &amp; Insurance Networks</h2></div>
  <div class="cards cards--4">
    <?php foreach ($partners as $p): ?>
    <div class="card reveal"><h3 style="font-size:1rem"><?= e($p['name']) ?></h3><?php if ($p['notes']): ?><p><?= e($p['notes']) ?></p><?php endif; ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <p style="color:var(--grey);margin-top:30px;font-size:.9rem">Coverage depends on your individual policy terms. Please confirm eligibility directly with your insurer or with our team at <?= e(SITE_PHONE_1) ?>.</p>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
