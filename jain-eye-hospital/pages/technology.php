<?php
/** Technology and facilities gallery, grouped from published hospital records. */
$route = '/technology';
$rows = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY category, position");
$relatedSpecialities = db_all("SELECT name, slug, short_description, image FROM specialities WHERE status='published' ORDER BY is_featured DESC, position LIMIT 4");
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND context='technology' ORDER BY position LIMIT 8"
);
$labels = [
    'diagnostics' => 'Diagnostic spaces',
    'laser' => 'Laser technology',
    'surgical' => 'Surgical care',
    'facility' => 'Hospital facilities',
];
$grouped = [];
foreach ($rows as $row) {
    $grouped[$row['category']][] = $row;
}
$page_seo = page_seo(
    $route,
    'Technology & Facilities | ' . SITE_NAME,
    'Explore photographs of the clinical spaces and equipment at ' . SITE_NAME . '.'
);
$featuredTech = $rows[0] ?? null;
require __DIR__ . '/../includes/header.php';
?>
<section class="technology-hero">
  <div class="container technology-hero__inner">
    <div class="technology-hero__copy">
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Technology</span></nav>
      <span class="eyebrow">Inside Jain Eye Hospital</span>
      <h1>Technology, spaces and the people behind your care</h1>
      <p>Take a closer look at hospital-provided photographs of our clinical spaces and equipment. Your clinician can explain what each examination involves and whether it is relevant to you.</p>
      <div class="technology-hero__actions">
        <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Plan a Visit</a>
        <a class="btn btn--light" href="#technology-explorer">Explore the Gallery</a>
      </div>
      <div class="technology-hero__facts" aria-label="Gallery overview">
        <div><strong><?= count($rows) ?></strong><span>published photo entries</span></div>
        <div><strong><?= count($grouped) ?></strong><span>care-space categories</span></div>
      </div>
    </div>
    <div class="technology-hero__visual">
      <?php if ($featuredTech): ?>
      <div class="technology-hero__image"><?= image_or_placeholder($featuredTech['image'], '', $featuredTech['name'], false) ?></div>
      <div class="technology-hero__caption"><span class="eyebrow">A look inside</span><strong><?= e($featuredTech['name']) ?></strong><span><?= e($labels[$featuredTech['category']] ?? ucfirst($featuredTech['category'])) ?></span></div>
      <?php else: ?>
      <div class="technology-hero__empty"><span class="eyebrow">A look inside</span><strong>Hospital photographs will appear here</strong><p>Approved photographs can be added through the hospital’s media library.</p></div>
      <?php endif; ?>
      <span class="technology-hero__index" aria-hidden="true">01 / <?= str_pad((string)count($rows), 2, '0', STR_PAD_LEFT) ?></span>
    </div>
  </div>
</section>

<section class="section technology-page">
  <div class="container">
    <div class="technology-intro" id="technology-explorer">
      <div>
        <span class="eyebrow">The hospital, in focus</span>
        <h2>Explore our clinical environment</h2>
      </div>
      <p>Each image below comes from the hospital’s published gallery. A photograph alone does not identify a device or determine whether a test or procedure is right for you.</p>
    </div>

    <?php if ($grouped): ?>
    <nav class="technology-nav" aria-label="Technology gallery categories">
      <?php foreach ($grouped as $category => $items): ?>
      <a href="#technology-<?= e($category) ?>"><?= e($labels[$category] ?? ucfirst($category)) ?><span><?= count($items) ?></span></a>
      <?php endforeach; ?>
    </nav>

    <?php foreach ($grouped as $category => $items): ?>
    <section class="technology-category" id="technology-<?= e($category) ?>" aria-labelledby="technology-heading-<?= e($category) ?>">
      <div class="technology-category__heading">
        <span class="technology-category__number"><?= sprintf('%02d', array_search($category, array_keys($grouped), true) + 1) ?></span>
        <div><span class="eyebrow"><?= e($labels[$category] ?? ucfirst($category)) ?></span><h2 id="technology-heading-<?= e($category) ?>"><?= e($labels[$category] ?? ucfirst($category)) ?></h2></div>
        <span class="technology-category__count"><?= count($items) ?> <?= count($items) === 1 ? 'photograph' : 'photographs' ?></span>
      </div>
      <div class="technology-gallery">
        <?php foreach ($items as $index => $item): ?>
        <article class="technology-gallery-card<?= $index === 0 ? ' technology-gallery-card--feature' : '' ?>">
          <div class="technology-gallery-card__image">
            <?= image_or_placeholder($item['image'], '', $item['name'] . ' at ' . SITE_NAME) ?>
            <span><?= sprintf('%02d', $index + 1) ?></span>
          </div>
          <div class="technology-gallery-card__body">
            <span class="technology-gallery-card__label"><?= e($labels[$category] ?? ucfirst($category)) ?></span>
            <h3><?= e($item['name']) ?></h3>
            <?php if ($item['short_description']): ?><p class="technology-gallery-card__summary"><?= e($item['short_description']) ?></p><?php endif; ?>
            <?php if ($item['description']): ?><p><?= nl2br(e($item['description'])) ?></p><?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-state"><strong>No equipment details are published yet</strong>Contact the hospital to ask about the facilities or equipment relevant to your consultation.</div>
    <?php endif; ?>

    <section class="technology-guide" aria-labelledby="technologyGuideTitle">
      <div class="technology-guide__intro">
        <span class="eyebrow">Your care, explained</span>
        <h2 id="technologyGuideTitle">Make the most of your conversation</h2>
        <p>The clinical team can explain the purpose of a recommended examination, what to expect and how the findings inform your next step.</p>
      </div>
      <div class="technology-guide__steps">
        <article><span>01</span><h3>Ask what it checks</h3><p>Ask what your clinician hopes to learn and how the result relates to your concerns.</p></article>
        <article><span>02</span><h3>Discuss your options</h3><p>Ask whether there are alternatives, what preparation may be needed and how long your visit may take.</p></article>
        <article><span>03</span><h3>Confirm what happens next</h3><p>Before leaving, ask when your findings will be explained and whether a follow-up visit is recommended.</p></article>
      </div>
    </section>

    <?php if ($relatedSpecialities): ?>
    <section class="technology-related">
      <div class="section-head"><span class="eyebrow">Explore eye care</span><h2>Browse our specialities</h2><p>Explore the care areas listed by the hospital and learn what to discuss at a consultation.</p></div>
      <div class="cards related-treatment-grid">
        <?php foreach ($relatedSpecialities as $speciality): ?>
        <a class="service-card related-treatment-card reveal" href="<?= url('specialities/' . $speciality['slug']) ?>">
          <div class="service-card__img"><?= image_or_placeholder($speciality['image'], '', $speciality['name']) ?></div>
          <div class="service-card__body"><h3><?= e($speciality['name']) ?></h3><p><?= e($speciality['short_description']) ?></p><span class="link-arrow">Explore speciality</span></div>
        </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <section class="technology-faq">
      <div class="section-head center"><span class="eyebrow">Quick answers</span><h2>Questions about technology</h2></div>
      <?php if ($faqs): ?>
      <div class="faq">
        <?php foreach ($faqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?><span class="plus" aria-hidden="true">+</span></summary><div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="technology-faq-note"><span class="technology-faq-note__icon" aria-hidden="true">?</span><div><strong>Hospital-specific answers are being prepared</strong><p>For questions about a particular test, device or facility, contact the hospital or ask your clinician during a consultation.</p></div><a href="<?= url('contact-us') ?>">Contact the hospital <span aria-hidden="true">→</span></a></div>
      <?php endif; ?>
    </section>
  </div>
</section>
<section class="section section--green">
  <div class="container cta-final">
    <h2>Questions about a test or facility?</h2>
    <p>Contact the hospital for practical details. Your clinician can explain whether a particular examination is suitable for your individual needs.</p>
    <a class="btn btn--primary" href="<?= url('contact-us') ?>">Contact the Hospital</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
