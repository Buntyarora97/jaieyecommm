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
    <span class="eyebrow">Find your care pathway</span>
    <h1>Eye Care Specialities</h1>
    <p>Explore the eye-care areas published by Jain Eye Hospital. Each page brings together available service information, hospital photographs, related doctors and practical next steps.</p>
    <div class="speciality-directory__facts"><span><strong><?= count($rows) ?></strong> care areas</span><span><strong><?= count($grouped) ?></strong> categories</span><a href="<?= url('book-appointment') ?>">Need help choosing? Request a consultation&nbsp; →</a></div>
  </div>
</section>
<section class="section">
  <div class="container">
    <?php foreach ($grouped as $category => $specs): ?>
    <?php $categoryId = 'speciality-' . slugify((string)$category); ?>
    <section class="speciality-directory__group" id="<?= e($categoryId) ?>">
      <div class="section-head reveal" style="margin-bottom:28px">
        <span class="eyebrow">Care area <?= sprintf('%02d', array_search($category, array_keys($grouped), true) + 1) ?></span>
        <h2><?= e($category) ?></h2>
        <p>Browse the available services in this group and open a speciality page for more detail.</p>
      </div>
      <div class="speciality-directory__grid">
        <?php foreach ($specs as $spec): ?>
        <a class="speciality-directory-card reveal" href="<?= url('specialities/' . $spec['slug']) ?>">
          <div class="speciality-directory-card__image"><?= image_or_placeholder($spec['image'], '', $spec['name']) ?><span><?= e($category) ?></span></div>
          <div class="speciality-directory-card__body">
            <span class="speciality-directory-card__index"><?= e(strtoupper(str_replace('-', ' ', $spec['slug']))) ?></span>
            <h3><?= e($spec['name']) ?></h3>
            <p><?= e($spec['short_description']) ?></p>
            <span class="link-arrow">Explore this care area</span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endforeach; ?>
  </div>
</section>
<section class="section section--green">
  <div class="container cta-final">
    <span class="eyebrow">A clear next step</span>
    <h2>Not sure where to begin?</h2>
    <p>Tell the hospital team what you would like help with. A consultation request lets the team guide you to the appropriate appointment information.</p>
    <div class="btn-row" style="justify-content:center"><a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request an Appointment</a><a class="btn btn--light" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a></div>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
