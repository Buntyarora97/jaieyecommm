<?php
$route = '/community';
$page_seo = page_seo($route, 'Community Initiatives | ' . SITE_NAME, 'Community eye care initiatives by ' . SITE_NAME . '.');
$items = db_all("SELECT * FROM community_initiatives WHERE status='published' ORDER BY event_date DESC");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Community</span></nav>
  <h1>Community Initiatives</h1><p>Verified, dated information about hospital community initiatives will appear here when available.</p>
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
  <div class="listing-guide">
    <div><span class="eyebrow">Community information</span><h2>Find clear, current programme details</h2><p>For a community initiative, useful information includes when and where it takes place, who it is intended for and whom to contact with questions. Please confirm availability and eligibility with the hospital before making plans.</p></div>
    <div class="listing-guide__action"><strong>Need information for your area?</strong><p>Contact the hospital team to ask about currently published updates.</p><a class="btn btn--outline" href="<?= url('contact-us') ?>">Contact the Hospital</a></div>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
