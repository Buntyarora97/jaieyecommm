<?php
/** Technology page grouped by category. */
$route = '/technology';
$page_seo = page_seo($route, 'Technology & Facilities | ' . SITE_NAME, 'View clinical photographs and ask ' . SITE_NAME . ' about equipment used for your care.');
$rows = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY category, position");
$relatedSpecialities = db_all("SELECT name, slug, short_description, image FROM specialities WHERE status='published' ORDER BY is_featured DESC, position LIMIT 4");
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND (context='technology' OR context='website')
     ORDER BY CASE WHEN context='technology' THEN 0 ELSE 1 END, position LIMIT 5"
);
$grouped = [];
$labels = ['diagnostics'=>'Diagnostic Technology','laser'=>'Laser Technology','surgical'=>'Surgical Technology','facility'=>'Hospital Facilities'];
foreach ($rows as $row) { $grouped[$row['category']][] = $row; }
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Technology</span></nav>
    <h1>Technology &amp; Facilities</h1>
    <p>Explore photographs of the hospital’s clinical spaces and equipment. Your clinician can explain which investigations, if any, are relevant to your eye examination.</p>
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
    <section class="technology-guide" aria-labelledby="technologyGuideTitle">
      <div class="technology-guide__intro">
        <span class="eyebrow">Your care, explained</span>
        <h2 id="technologyGuideTitle">How to ask about equipment</h2>
        <p>Seeing a machine or photograph does not tell you whether a test or procedure is needed. The examining clinician can connect each recommendation to your symptoms, examination and questions.</p>
      </div>
      <div class="technology-guide__steps">
        <article><span>01</span><h3>Ask what the test is for</h3><p>Ask what your clinician hopes to learn and how the result will help guide the next discussion.</p></article>
        <article><span>02</span><h3>Discuss your options</h3><p>Ask whether there are alternatives, what preparation is needed and whether you should bring someone with you.</p></article>
        <article><span>03</span><h3>Confirm the next step</h3><p>Before leaving, ask when and how you will receive an explanation of findings and what follow-up is recommended.</p></article>
      </div>
    </section>
    <?php if ($relatedSpecialities): ?>
    <div class="technology-related">
      <div class="section-head"><span class="eyebrow">Explore eye care</span><h2>Browse Our Specialities</h2><p>Read about the eye-care areas published by the hospital and see which questions to discuss at a consultation.</p></div>
      <div class="cards related-treatment-grid">
        <?php foreach ($relatedSpecialities as $speciality): ?>
        <a class="service-card related-treatment-card reveal" href="<?= url('specialities/' . $speciality['slug']) ?>">
          <div class="service-card__img"><?= image_or_placeholder($speciality['image'], '', $speciality['name']) ?></div>
          <div class="service-card__body"><h3><?= e($speciality['name']) ?></h3><p><?= e($speciality['short_description']) ?></p><span class="link-arrow">Explore speciality</span></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if ($faqs): ?>
    <div class="technology-faq">
      <div class="section-head center"><span class="eyebrow">Quick answers</span><h2>Questions About Technology</h2></div>
      <div class="faq">
        <?php foreach ($faqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?><span class="plus" aria-hidden="true">+</span></summary><div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="section section--green">
  <div class="container cta-final">
    <h2>Questions about facilities or equipment?</h2>
    <p>Contact the hospital for practical details or to request an appointment. Device availability and suitability should be confirmed with the clinical team.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
