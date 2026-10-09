<?php
$route = '/academics';
$page_seo = page_seo($route, 'Academics & Training | ' . SITE_NAME, 'Academic activities and training at ' . SITE_NAME . '.');
$items = db_all("SELECT * FROM academics WHERE status='published' ORDER BY event_date DESC");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Academics</span></nav>
  <h1>Academics &amp; Training</h1><p>Confirmed academic activities and training updates will be listed here when details are available.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($items): ?>
  <div class="cards cards--2">
    <?php foreach ($items as $item): ?>
    <div class="card reveal">
      <h3><?= e($item['title']) ?></h3>
      <p style="color:var(--orange);font-weight:600;font-size:.82rem"><?= e($item['type'] ?? '') ?><?php if ($item['event_date']): ?> · <?= format_date($item['event_date']) ?><?php endif; ?></p>
      <?php if ($item['description']): ?><p style="margin-top:8px"><?= nl2br(e($item['description'])) ?></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><strong>Updates coming soon</strong>Academic activities and training programmes will be listed here.</div>
  <?php endif; ?>
  <div class="listing-guide">
    <div><span class="eyebrow">For learners and visitors</span><h2>Check the details before you plan</h2><p>When a programme is listed, look for its subject, date, intended participants and contact instructions. Programme availability and eligibility should be confirmed directly with the organiser.</p></div>
    <div class="listing-guide__action"><strong>Have a question about a programme?</strong><p>Ask the hospital team whether current information is available.</p><a class="btn btn--outline" href="<?= url('contact-us') ?>">Ask the Hospital</a></div>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
