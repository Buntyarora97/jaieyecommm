<?php
$route = '/insurance-payment';
$page_seo = page_seo($route, 'Insurance & Payment | ' . SITE_NAME, 'Contact ' . SITE_NAME . ' to confirm insurance coverage, payment methods and estimates before care.');
$partners = db_all("SELECT * FROM insurance_partners WHERE is_active=1 ORDER BY position");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Insurance &amp; Payment</span></nav>
  <h1>Insurance &amp; Payment</h1><p>Confirm coverage, payment methods and estimates directly with the hospital and your insurer.</p>
</div></section>
<section class="section"><div class="container">
  <div class="cards cards--2">
    <div class="card reveal"><h3>Insurance &amp; Cashless</h3><p>Coverage, network participation and cashless approval depend on your policy and the hospital’s current arrangements. Contact the hospital and your insurer before making plans; this page does not confirm eligibility or approval.</p></div>
    <div class="card reveal"><h3>Payment Information</h3><p>Ask the hospital’s billing team to confirm accepted payment methods and request an estimate for any planned care. Do not send policy numbers, banking details or payment information through the website forms.</p></div>
  </div>
  <?php if ($partners): ?>
  <div class="section-head reveal" style="margin-top:56px"><span class="eyebrow">Insurance Partners</span><h2>TPAs &amp; Insurance Networks</h2></div>
  <div class="cards cards--4">
    <?php foreach ($partners as $p): ?>
    <div class="card reveal"><h3 style="font-size:1rem"><?= e($p['name']) ?></h3><?php if ($p['notes']): ?><p><?= e($p['notes']) ?></p><?php endif; ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <p style="color:var(--grey);margin-top:30px;font-size:.9rem">No insurer or cashless network is confirmed on this page. For current information, call <a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a> and verify coverage with your insurer.</p>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
