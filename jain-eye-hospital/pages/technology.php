<?php
/** Technology page grouped by category. */
$route = '/technology';
$page_seo = page_seo($route, 'Technology & Facilities | ' . SITE_NAME, 'View clinical photographs and ask ' . SITE_NAME . ' about equipment used for your care.');
$rows = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY category, position");
$grouped = [];
$labels = ['diagnostics'=>'Diagnostic Technology','laser'=>'Laser Technology','surgical'=>'Surgical Technology','facility'=>'Hospital Facilities'];
foreach ($rows as $row) { $grouped[$row['category']][] = $row; }
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Technology</span></nav>
    <h1>Technology &amp; Facilities</h1>
    <p>Explore photographs of clinical spaces and equipment. Contact the hospital to confirm specific device names and their use.</p>
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
            <div class="technology-card__image"><?= image_or_placeholder($item['image'], '', $item['name']) ?></div>
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
    <div class="empty-state"><strong>No equipment details are published yet</strong>Contact the hospital to ask about the facilities or equipment relevant to your consultation.</div>
    <?php endif; ?>
  </div>
</section>
<section class="section section--green">
  <div class="container cta-final">
    <h2>Questions about facilities or equipment?</h2>
    <p>Contact the hospital before planning a visit.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
