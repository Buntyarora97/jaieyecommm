<?php
/** Photo gallery with category filters, masonry layout. */
$route = '/gallery';
$page_seo = page_seo($route, 'Photo Gallery | ' . SITE_NAME, 'Photos of ' . SITE_NAME . ' - infrastructure, technology, events and community.');
$activeCat = $_GET['category'] ?? '';
$items = db_all("SELECT * FROM gallery_items WHERE is_active=1 ORDER BY category, position");
$cats = ['infrastructure'=>'Hospital Infrastructure','doctors'=>'Doctors','technology'=>'Technology','events'=>'Events','community'=>'Community','media'=>'Media'];
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Gallery</span></nav>
  <h1>Photo Gallery</h1><p>A glimpse inside <?= e(SITE_NAME) ?>.</p>
</div></section>
<section class="section"><div class="container">
  <div class="pill-nav">
    <a href="#" class="<?= $activeCat ? '' : 'is-active' ?>" data-gallery-filter="">All</a>
    <?php foreach ($cats as $key => $label): ?>
    <a href="#" class="<?= $activeCat === $key ? 'is-active' : '' ?>" data-gallery-filter="<?= e($key) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>
  <?php if ($items): ?>
  <div class="gallery-grid">
    <?php foreach ($items as $item): ?>
    <figure class="gallery-item" data-gallery-item="<?= e($item['category']) ?>">
      <?= image_or_placeholder($item['image'], '', $item['caption'] ?: $item['title'] ?: 'Gallery image') ?>
      <?php if ($item['caption'] || $item['title']): ?><figcaption><?= e($item['caption'] ?: $item['title']) ?></figcaption><?php endif; ?>
    </figure>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><strong>Gallery coming soon</strong>Photos of our hospital will be added here shortly.</div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
