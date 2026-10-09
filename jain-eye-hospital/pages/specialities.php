<?php
/** Speciality directory grouped by category. */
$route = '/specialities';
$page_seo = page_seo($route, 'Eye Care Specialities | ' . SITE_NAME, 'Cataract, LASIK, retina, cornea, squint, myopia and children\'s eye care in Shalimar Bagh, Delhi.');
$rows = db_all("SELECT * FROM specialities WHERE status='published' ORDER BY category, position");
$grouped = [];
foreach ($rows as $row) { $grouped[$row['category'] ?: 'Specialities'][] = $row; }
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Specialities</span></nav>
    <h1>Eye Care Specialities</h1>
    <p>Comprehensive super-speciality eye care under one roof.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <?php foreach ($grouped as $category => $specs): ?>
    <div style="margin-bottom:56px">
      <div class="section-head reveal" style="margin-bottom:28px">
        <span class="eyebrow"><?= e($category) ?></span>
      </div>
      <div class="cards">
        <?php foreach ($specs as $spec): ?>
        <a class="spec-card reveal" href="<?= url('specialities/' . $spec['slug']) ?>">
          <div class="spec-card__img<?= $spec['slug'] === 'cataract-iol' ? ' spec-card__img--wide' : '' ?>"><?= image_or_placeholder($spec['image'], '', $spec['name']) ?></div>
          <div class="spec-card__body">
            <h3><?= e($spec['name']) ?></h3>
            <p><?= e($spec['short_description']) ?></p>
            <span class="link-arrow">Explore speciality</span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<section class="section section--green">
  <div class="container cta-final">
    <h2>Not Sure Which Speciality You Need?</h2>
    <p>Book a comprehensive eye examination and our specialists will guide you.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book an Eye Check-up</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
