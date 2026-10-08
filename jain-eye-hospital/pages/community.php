<?php
$route = '/community';
$page_seo = page_seo($route, 'Community Initiatives | ' . SITE_NAME, 'Community eye care initiatives by ' . SITE_NAME . '.');
$items = db_all("SELECT * FROM community_initiatives WHERE status='published' ORDER BY event_date DESC");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Community</span></nav>
  <h1>Community Initiatives</h1><p>Eye care beyond our hospital walls.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($items): ?>
  <div class="cards cards--2">
    <?php foreach ($items as $item): ?>
    <div class="card reveal">
      <h3><?= e($item['title']) ?></h3>
      <?php if ($item['event_date']): ?><p style="color:var(--orange);font-weight:600;font-size:.82rem"><?= format_date($item['event_date']) ?></p><?php endif; ?>
      <?php if ($item['description']): ?><p style="margin-top:8px"><?= nl2br(e($item['description'])) ?></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><strong>Updates coming soon</strong>Our community eye care initiatives will be shared here.</div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
