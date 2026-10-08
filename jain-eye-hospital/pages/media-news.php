<?php
$route = '/media-news';
$page_seo = page_seo($route, 'Media & News | ' . SITE_NAME, 'News and media updates from ' . SITE_NAME . '.');
$items = db_all("SELECT * FROM media_items WHERE status='published' ORDER BY published_at DESC");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Media &amp; News</span></nav>
  <h1>Media &amp; News</h1><p>Updates from <?= e(SITE_NAME) ?>.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($items): ?>
  <div class="blog-grid">
    <?php foreach ($items as $item): ?>
    <article class="post-card reveal">
      <div class="post-card__img"><?= image_or_placeholder($item['image'], '', $item['title']) ?></div>
      <div class="post-card__body">
        <div class="post-card__meta"><span class="post-card__cat"><?= e(ucfirst($item['type'])) ?></span><span><?= format_date($item['published_at']) ?></span></div>
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['summary'] ?: excerpt($item['content'] ?? '', 130)) ?></p>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><strong>No updates yet</strong>Hospital news and media mentions will appear here.</div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
