<?php
/** Doctor directory with search + speciality filters. */
$route = '/doctors';
$page_seo = page_seo($route, 'Our Doctors | ' . SITE_NAME, 'Meet the eye specialists at ' . SITE_NAME . ', Shalimar Bagh, Delhi.');
$doctors = db_all("SELECT * FROM doctors WHERE status='published' ORDER BY position");
$specs = db_all("SELECT DISTINCT specialisation FROM doctors WHERE status='published' AND specialisation IS NOT NULL AND specialisation<>'' ORDER BY specialisation");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Our Doctors</span></nav>
    <h1>Our Doctors</h1>
    <p>Consult with experienced eye specialists at <?= e(SITE_AREA) ?>.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="filter-bar">
      <input type="search" placeholder="Search doctors by name…" data-filter-search aria-label="Search doctors">
      <?php if ($specs): ?>
      <div class="pill-nav" style="margin:0">
        <a href="#" class="is-active" data-filter-spec="">All</a>
        <?php foreach ($specs as $s): ?>
        <a href="#" data-filter-spec="<?= e($s['specialisation']) ?>"><?= e($s['specialisation']) ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php if ($doctors): ?>
    <div class="cards">
      <?php foreach ($doctors as $doc): ?>
      <article class="doctor-card reveal" data-filter-item data-spec="<?= e($doc['specialisation'] ?? '') ?>">
        <div class="doctor-card__photo"><?= image_or_placeholder($doc['photo'], '', 'Portrait of ' . $doc['name']) ?></div>
        <div class="doctor-card__body">
          <h3><?= e($doc['name']) ?></h3>
          <?php if ($doc['designation']): ?><p class="doctor-card__role"><?= e($doc['designation']) ?></p><?php endif; ?>
          <?php if ($doc['qualifications']): ?><p class="doctor-card__spec"><?= e($doc['qualifications']) ?></p><?php endif; ?>
          <div class="doctor-card__actions">
            <a class="btn btn--outline" href="<?= url('doctors/' . $doc['slug']) ?>">View Profile</a>
            <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state"><strong>Doctor profiles are being updated</strong>Please call <?= e(SITE_PHONE_1) ?> to book a consultation.</div>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
