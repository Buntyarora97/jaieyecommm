<?php
$route = '/awards';
$page_seo = page_seo($route, 'Awards & Recognition | ' . SITE_NAME, 'Awards and recognition received by ' . SITE_NAME . '.');
$awards = db_all("SELECT * FROM awards WHERE is_active=1 ORDER BY position");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Awards</span></nav>
  <h1>Awards &amp; Recognition</h1><p>Milestones that reflect our commitment to quality eye care.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($awards): ?>
  <div class="cards">
    <?php foreach ($awards as $a): ?>
    <div class="card reveal">
      <div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 2a5 5 0 0 1 5 5c0 2-1.2 3.6-2.7 4.4L17 19l-5-2.5L7 19l2.7-7.6A5 5 0 0 1 12 2z"/></svg></div>
      <h3><?= e($a['title']) ?></h3>
      <p><?php if ($a['organisation']): ?><?= e($a['organisation']) ?><?php endif; ?><?php if ($a['award_year']): ?> · <?= e($a['award_year']) ?><?php endif; ?></p>
      <?php if ($a['description']): ?><p style="margin-top:8px"><?= e($a['description']) ?></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><strong>Updates coming soon</strong>Our awards and recognition will be listed here.</div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
