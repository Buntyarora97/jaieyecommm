<?php
/** Treatment directory. */
$route = '/treatments';
$page_seo = page_seo($route, 'Eye Treatments & Procedures | ' . SITE_NAME, 'Cataract surgery, LASIK, retinal laser, corneal procedures and more at ' . SITE_NAME . '.');
$treatments = db_all("SELECT t.*, s.name AS spec_name, s.slug AS spec_slug FROM treatments t LEFT JOIN specialities s ON s.id=t.speciality_id WHERE t.status='published' ORDER BY t.position");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Treatments</span></nav>
    <h1>Treatments &amp; Procedures</h1>
    <p>Advanced eye treatments planned around your individual needs.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="cards">
      <?php foreach ($treatments as $t): ?>
      <article class="service-card reveal">
        <div class="service-card__img<?= $t['slug'] === 'cataract-surgery' ? ' service-card__img--wide' : '' ?>"><?= image_or_placeholder($t['image'], '', $t['name']) ?></div>
        <div class="service-card__body">
          <?php if ($t['spec_name']): ?><span style="font-size:.75rem;font-weight:700;color:var(--orange);text-transform:uppercase;letter-spacing:.08em"><?= e($t['spec_name']) ?></span><?php endif; ?>
          <h3><?= e($t['name']) ?></h3>
          <p><?= e($t['short_description']) ?></p>
          <div class="service-card__actions">
            <a class="link-arrow" href="<?= url('treatments/' . $t['slug']) ?>">Learn More</a>
            <a class="link-arrow" href="<?= url('book-appointment') ?>">Book Now</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
