<?php
/** Technology page grouped by category. */
$route = '/technology';
$page_seo = page_seo($route, 'Technology | ' . SITE_NAME, 'Diagnostic, laser and surgical technology at ' . SITE_NAME . ', Shalimar Bagh, Delhi.');
$rows = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY category, position");
$grouped = [];
$labels = ['diagnostics'=>'Diagnostic Technology','laser'=>'Laser Technology','surgical'=>'Surgical Technology','facility'=>'Hospital Facilities'];
foreach ($rows as $row) { $grouped[$row['category']][] = $row; }
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Technology</span></nav>
    <h1>Our Technology</h1>
    <p>Contemporary ophthalmic technology supporting accurate diagnosis and gentle treatment.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <?php if ($grouped): ?>
      <?php foreach ($grouped as $cat => $items): ?>
      <div id="<?= e($cat) ?>" style="margin-bottom:56px;scroll-margin-top:120px">
        <div class="section-head reveal" style="margin-bottom:26px"><span class="eyebrow"><?= e($labels[$cat] ?? ucfirst($cat)) ?></span></div>
        <div class="cards">
          <?php foreach ($items as $item): ?>
          <div class="card reveal">
            <div class="card__icon"><svg viewBox="0 0 24 24"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
            <h3><?= e($item['name']) ?></h3>
            <p><?= e($item['short_description']) ?></p>
            <?php if ($item['description']): ?><p style="margin-top:8px"><?= nl2br(e($item['description'])) ?></p><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
    <div class="cards cards--2">
      <div class="card reveal" id="diagnostics"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg></div>
        <h3>Diagnostic Technology</h3><p>Detailed imaging and measurements that help our specialists diagnose accurately and plan treatment precisely.</p></div>
      <div class="card reveal" id="laser"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
        <h3>Laser Technology</h3><p>Laser systems supporting refractive correction and retinal procedures with precision.</p></div>
      <div class="card reveal" id="surgical"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3z"/></svg></div>
        <h3>Surgical Technology</h3><p>Modern microsurgical equipment for cataract and other eye surgeries.</p></div>
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M19 3h-4.2C14.4 1.8 13.3 1 12 1S9.6 1.8 9.2 3H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/></svg></div>
        <h3>Hospital Facilities</h3><p>Comfortable consultation suites, a dedicated operation theatre and structured recovery areas.</p></div>
    </div>
    <p style="color:var(--grey);margin-top:26px;font-size:.9rem">Our detailed equipment list is being updated. Please contact the hospital for specific technology-related questions.</p>
    <?php endif; ?>
  </div>
</section>
<section class="section section--green">
  <div class="container cta-final">
    <h2>Experience Technology-Backed Eye Care</h2>
    <p>Book a consultation at <?= e(SITE_AREA) ?>.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
