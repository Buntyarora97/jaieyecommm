<?php
/** A photo-led guide to the clinical spaces published by the hospital. */
$route = '/technology';
$rows = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY category, position");
$relatedSpecialities = db_all("SELECT name, slug, short_description, image FROM specialities WHERE status='published' ORDER BY is_featured DESC, position LIMIT 4");
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND context='technology' ORDER BY position LIMIT 8"
);
$galleryPhotos = db_all(
    "SELECT title AS name, image, caption AS description FROM gallery_items
     WHERE category='technology' AND is_active=1 ORDER BY position LIMIT 12"
);
$knownImages = array_column($rows, 'image');
$galleryPhotos = array_values(array_filter(
    $galleryPhotos,
    static fn(array $photo): bool => !in_array($photo['image'], $knownImages, true)
));
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
$photoCount = count($rows) + count($galleryPhotos);
$page_seo = page_seo(
    $route,
    'Hospital Technology & Clinical Spaces | ' . SITE_NAME,
    'Explore hospital-provided photographs of clinical spaces and equipment, and learn what to ask your care team during a visit.'
);
$featuredTech = $rows[0] ?? null;
$fallbackFaqs = [
    [
        'question' => 'Does a photograph identify the exact device or its model?',
        'answer' => 'No. The photographs are included to show the spaces and equipment pictured. They do not confirm a device name, model, specification or clinical capability. Please ask the hospital team if you need information about a particular device.',
    ],
    [
        'question' => 'Will every patient have the same examination?',
        'answer' => 'The purpose and choice of an examination depend on the concern being assessed and the clinician’s evaluation. Ask what a recommended test is intended to check and how the result will be discussed with you.',
    ],
    [
        'question' => 'Should I prepare before an eye examination?',
        'answer' => 'Preparation can depend on the visit and the examinations planned. When you book, ask the hospital team whether you should follow any specific instructions. Bring previous eye reports and your current spectacles if they are available.',
    ],
    [
        'question' => 'Can I ask why a test has been recommended?',
        'answer' => 'Yes. You can ask what the test is intended to assess, what the process involves, whether any preparation is needed and when someone will explain the findings. Your clinician can relate the answers to your own examination.',
    ],
    [
        'question' => 'Do the photographs show every service offered?',
        'answer' => 'No. This page is a selection of published photographs, not a complete service list. Contact the hospital to confirm current facilities, available services and appointment details.',
    ],
];
$displayFaqs = $faqs ?: $fallbackFaqs;
require __DIR__ . '/../includes/header.php';
?>
<main class="technology-redesign">
  <section class="tech-hero">
    <div class="container tech-hero__layout">
      <div class="tech-hero__copy">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Technology</span></nav>
        <span class="tech-kicker"><i aria-hidden="true"></i> A closer look at your visit</span>
        <h1>Care begins with a clearer view.</h1>
        <p>Explore photographs of the clinical spaces and equipment shared by Jain Eye Hospital. Use this guide to prepare questions for your care team—not to identify a device or decide which examination you need.</p>
        <div class="tech-hero__actions">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Plan your visit <span aria-hidden="true">↗</span></a>
          <a class="tech-text-link" href="#technology-explorer">Explore the photographs <span aria-hidden="true">↓</span></a>
        </div>
        <div class="tech-hero__stats" aria-label="Published photo guide">
          <div><strong><?= $photoCount ?></strong><span>hospital photographs</span></div>
          <div><strong><?= count($grouped) ?></strong><span>care-space categories</span></div>
          <span class="tech-hero__stat-note">Images are provided by the hospital</span>
        </div>
      </div>
      <div class="tech-hero__visual">
        <?php if ($featuredTech): ?>
        <figure class="tech-hero__main-photo">
          <?= image_or_placeholder($featuredTech['image'], '', $featuredTech['name'] . ' at ' . SITE_NAME, false) ?>
          <figcaption><span>01 / <?= str_pad((string)count($rows), 2, '0', STR_PAD_LEFT) ?></span><span><?= e($featuredTech['name']) ?></span></figcaption>
        </figure>
        <?php else: ?>
        <div class="tech-hero__photo-empty"><span>Hospital photographs</span><strong>Approved images will appear here.</strong></div>
        <?php endif; ?>
        <?php if (isset($rows[1])): ?>
        <figure class="tech-hero__detail-photo">
          <?= image_or_placeholder($rows[1]['image'], '', $rows[1]['name'] . ' at ' . SITE_NAME) ?>
          <figcaption><span>Inside the hospital</span><strong><?= e($rows[1]['name']) ?></strong></figcaption>
        </figure>
        <?php endif; ?>
        <span class="tech-hero__stamp" aria-hidden="true">JAIN<br>EYE<br>HOSPITAL</span>
      </div>
    </div>
    <div class="tech-hero__bottom">
      <div class="container"><span>See the space.</span><span>Ask what the examination is for.</span><span>Understand the next step.</span></div>
    </div>
  </section>

  <section class="tech-intro section" id="technology-explorer">
    <div class="container">
      <div class="tech-section-heading">
        <div><span class="eyebrow">Inside the hospital</span><h2>Spaces and equipment, shown clearly.</h2></div>
        <p>Each image below comes from the hospital’s published gallery. Captions describe what is pictured; they do not confirm model names, specifications or a particular treatment.</p>
      </div>
      <?php if ($grouped): ?>
      <nav class="tech-category-nav" aria-label="Browse photographs by category">
        <?php foreach ($grouped as $category => $items): ?>
        <a href="#technology-<?= e($category) ?>"><span><?= e($labels[$category] ?? ucfirst($category)) ?></span><b><?= count($items) ?></b></a>
        <?php endforeach; ?>
      </nav>

      <?php foreach ($grouped as $category => $items): ?>
      <section class="tech-category" id="technology-<?= e($category) ?>" aria-labelledby="technology-heading-<?= e($category) ?>">
        <div class="tech-category__heading">
          <span class="tech-category__number"><?= sprintf('%02d', array_search($category, array_keys($grouped), true) + 1) ?></span>
          <div><span class="eyebrow"><?= e($labels[$category] ?? ucfirst($category)) ?></span><h3 id="technology-heading-<?= e($category) ?>"><?= e($labels[$category] ?? ucfirst($category)) ?></h3></div>
          <span class="tech-category__count"><?= count($items) ?> <?= count($items) === 1 ? 'photograph' : 'photographs' ?></span>
        </div>
        <div class="tech-photo-grid<?= count($items) === 1 ? ' tech-photo-grid--single' : '' ?>">
          <?php foreach ($items as $index => $item): ?>
          <article class="tech-photo-card<?= $index === 0 ? ' tech-photo-card--feature' : '' ?>">
            <figure class="tech-photo-card__image">
              <?= image_or_placeholder($item['image'], '', $item['name'] . ' at ' . SITE_NAME) ?>
              <span><?= sprintf('%02d', $index + 1) ?></span>
            </figure>
            <div class="tech-photo-card__body">
              <span class="tech-photo-card__label"><?= e($labels[$category] ?? ucfirst($category)) ?></span>
              <h4><?= e($item['name']) ?></h4>
              <?php if ($item['short_description']): ?><p class="tech-photo-card__summary"><?= e($item['short_description']) ?></p><?php endif; ?>
              <?php if ($item['description']): ?><p><?= nl2br(e($item['description'])) ?></p><?php endif; ?>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endforeach; ?>
      <?php else: ?>
      <div class="tech-empty"><strong>Approved photographs will appear here.</strong><p>Contact the hospital team for current information about facilities and equipment.</p></div>
      <?php endif; ?>

      <?php if ($galleryPhotos): ?>
      <section class="tech-photo-strip" aria-labelledby="techMorePhotosTitle">
        <div class="tech-photo-strip__heading"><div><span class="eyebrow">More from the gallery</span><h3 id="techMorePhotosTitle">A few more views.</h3></div><span><?= count($galleryPhotos) ?> additional photographs</span></div>
        <div class="tech-photo-strip__grid">
          <?php foreach ($galleryPhotos as $photo): ?>
          <figure class="tech-gallery-tile">
            <?= image_or_placeholder($photo['image'], '', $photo['name'] . ' at ' . SITE_NAME) ?>
            <figcaption><strong><?= e($photo['name']) ?></strong><?php if ($photo['description']): ?><span><?= e($photo['description']) ?></span><?php endif; ?></figcaption>
          </figure>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>
    </div>
  </section>

  <section class="tech-visit">
    <div class="container tech-visit__layout">
      <div class="tech-visit__lead"><span class="eyebrow">Make your visit easier</span><h2>Good questions make the details clearer.</h2><p>A photograph can show a room. Your clinician can explain why an examination is being considered and how it fits your individual visit.</p><a href="<?= url('patient-journey') ?>" class="tech-text-link">Read the patient visit guide <span aria-hidden="true">↗</span></a></div>
      <div class="tech-visit__steps">
        <article><span>01</span><div><h3>Before you arrive</h3><p>Ask the hospital team whether your appointment needs any preparation. Bring previous eye reports and your current spectacles if you have them.</p></div></article>
        <article><span>02</span><div><h3>When a test is discussed</h3><p>Ask what it is intended to check, what the process involves and whether you should expect any follow-up instructions.</p></div></article>
        <article><span>03</span><div><h3>After the examination</h3><p>Ask who will explain the findings, when you can expect that conversation and what next step—if any—is being recommended for you.</p></div></article>
      </div>
    </div>
  </section>

  <?php if ($relatedSpecialities): ?>
  <section class="tech-related section">
    <div class="container">
      <div class="tech-section-heading">
        <div><span class="eyebrow">Explore eye care</span><h2>Find a care area to learn about.</h2></div>
        <p>These are the speciality pages currently published by the hospital. They offer general information and are not a substitute for an individual assessment.</p>
      </div>
      <div class="tech-related-grid">
        <?php foreach ($relatedSpecialities as $speciality): ?>
        <a class="tech-related-card" href="<?= url('specialities/' . $speciality['slug']) ?>">
          <span class="tech-related-card__image"><?= image_or_placeholder($speciality['image'], '', $speciality['name']) ?></span>
          <span class="tech-related-card__copy"><strong><?= e($speciality['name']) ?></strong><span><?= e($speciality['short_description']) ?></span><b>Explore care area <i aria-hidden="true">↗</i></b></span>
        </a>
        <?php endforeach; ?>
      </div>
      <a href="<?= url('specialities') ?>" class="tech-all-specialities">Browse all specialities <span aria-hidden="true">→</span></a>
    </div>
  </section>
  <?php endif; ?>

  <section class="tech-faq-section section">
    <div class="container tech-faq-layout">
      <div class="tech-faq-intro"><span class="eyebrow">Questions to take with you</span><h2>Technology, explained without the guesswork.</h2><p>These answers are general guidance. For exact device, facility, appointment or clinical information, ask the hospital team directly.</p><a href="<?= url('contact-us') ?>" class="tech-text-link">Contact the hospital <span aria-hidden="true">↗</span></a></div>
      <div class="tech-faq-list">
        <?php foreach ($displayFaqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?><span aria-hidden="true">+</span></summary><div><?= nl2br(e($faq['answer'])) ?></div></details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tech-final-cta">
    <div class="container"><div><span class="eyebrow">Your next step</span><h2>Questions about a test or your visit?</h2><p>Contact the hospital for practical details. Your clinician can discuss what is relevant to your own care.</p></div><div class="tech-final-cta__actions"><a class="btn btn--primary" href="<?= url('contact-us') ?>">Contact the hospital</a><a class="btn btn--light" href="<?= url('book-appointment') ?>">Request an appointment</a></div></div>
  </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
