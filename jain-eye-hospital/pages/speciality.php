<?php
/** Individual speciality page: overview, symptoms, diagnostics, treatments, doctors, FAQs. */
$spec = db_row("SELECT * FROM specialities WHERE slug = ? AND status='published'", [$slug]);
if (!$spec) { http_response_code(404); require __DIR__ . '/404.php'; return; }

$guideLibrary = require __DIR__ . '/../includes/speciality-guides.php';
$guide = $guideLibrary[$spec['slug']] ?? null;
$route = '/specialities/' . $spec['slug'];
$specialitySeoTopics = [
    'cataract-iol' => ['cataract care Delhi', 'cataract surgery information', 'intraocular lens options'],
    'lasik-refractive' => ['LASIK information Delhi', 'refractive surgery', 'vision correction assessment'],
    'retina-uvea' => ['retina care Delhi', 'uvea care information', 'retina evaluation'],
    'macular-conditions' => ['macular condition information', 'macular eye care Delhi', 'retina care'],
    'cornea-care' => ['cornea care Delhi', 'corneal condition information', 'cornea evaluation'],
    'squint' => ['squint evaluation Delhi', 'eye alignment information', 'children eye care'],
    'myopia-clinic' => ['myopia clinic Delhi', 'myopia information', 'children eye care'],
    'childrens-eye-health' => ['children eye health Delhi', 'paediatric eye care', 'child eye examination information'],
];
$specialitySummary = $spec['short_description'] ?: $spec['overview'] ?: 'Published care information for ' . $spec['name'] . '.';
$specialityDescription = excerpt(
    excerpt($specialitySummary, 92) . ' Learn more at ' . SITE_NAME . ', Shalimar Bagh, Delhi.',
    155
);
$specialityKeywords = array_merge(
    [$spec['name'] . ' Delhi'],
    $specialitySeoTopics[$spec['slug']] ?? [],
    ['eye care Shalimar Bagh', SITE_NAME]
);
$page_seo = page_seo(
    $route,
    $spec['name'] . ' in Delhi | Jain Eye Hospital',
    $specialityDescription,
    implode(', ', array_unique($specialityKeywords))
);
if (empty($page_seo['og_image']) && !empty($spec['image'])) {
    $page_seo['og_image'] = str_starts_with($spec['image'], 'assets/')
        ? url($spec['image'])
        : uploads_url($spec['image']);
}
$treatments = db_all("SELECT * FROM treatments WHERE speciality_id = ? AND status='published' ORDER BY position", [$spec['id']]);
$doctors = db_all("SELECT d.* FROM doctors d JOIN doctor_specialities ds ON ds.doctor_id=d.id WHERE ds.speciality_id=? AND d.status='published'", [$spec['id']]);
$faqs = db_all("SELECT * FROM faqs WHERE is_active=1 AND context=? ORDER BY position LIMIT 8", [$spec['slug']]);
$faqCategoryBySlug = [
    'cataract-iol' => 'Cataract',
    'lasik-refractive' => 'LASIK',
    'retina-uvea' => 'Retina',
    'macular-conditions' => 'Retina',
    'squint' => 'Children',
    'myopia-clinic' => 'Children',
    'childrens-eye-health' => 'Children',
];
$faqCategory = $faqCategoryBySlug[$spec['slug']] ?? 'General';
$directoryFaqs = db_all(
    "SELECT * FROM faqs
     WHERE is_active=1 AND context='website' AND category IN (?, 'General')
     ORDER BY CASE WHEN category=? THEN 0 ELSE 1 END, position LIMIT 8",
    [$faqCategory, $faqCategory]
);
$faqIds = array_fill_keys(array_map(static fn(array $faq): int => (int)$faq['id'], $faqs), true);
foreach ($directoryFaqs as $faq) {
    if (!isset($faqIds[(int)$faq['id']])) {
        $faqs[] = $faq;
        $faqIds[(int)$faq['id']] = true;
    }
}
$faqList = array_merge($guide['faqs'] ?? [], $faqs);
$seenQuestions = [];
$faqs = [];
foreach ($faqList as $faq) {
    $questionKey = mb_strtolower(trim((string)$faq['question']));
    if (isset($seenQuestions[$questionKey])) continue;
    $seenQuestions[$questionKey] = true;
    $faqs[] = $faq;
    if (count($faqs) === 8) break;
}
$posts = db_all("SELECT * FROM blog_posts WHERE status='published' ORDER BY published_at DESC LIMIT 3");

$fallbackSections = [
    'overview'        => 'Overview',
    'symptoms'        => 'Symptoms',
    'conditions'      => 'Conditions We Manage',
    'diagnostics'     => 'Diagnostic Evaluation',
    'treatments_text' => 'Treatment Options',
    'technology_text' => 'Technology',
    'what_to_expect'  => 'What to Expect',
    'recovery'        => 'Recovery & Follow-Up',
];
$sections = $guide['sections'] ?? [];
if (!$sections) {
    foreach ($fallbackSections as $field => $label) {
        if (!empty($spec[$field])) {
            $sections[] = ['id' => $field, 'title' => $label, 'body' => $spec[$field]];
        }
    }
}
$articleText = implode(' ', array_column($sections, 'body'));
$guideWordCount = str_word_count(strip_tags($articleText)) + array_sum(array_map(
    static fn(array $faq): int => str_word_count(strip_tags((string)$faq['answer'])),
    $guide['faqs'] ?? []
));
$readingMinutes = max(1, (int)ceil($guideWordCount / 220));
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero care-hero">
  <div class="container care-hero__inner">
    <div class="care-hero__copy">
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><a href="<?= url('specialities') ?>">Specialities</a><span class="sep">/</span><span><?= e($spec['name']) ?></span></nav>
      <span class="eyebrow">Eye care at <?= e(SITE_AREA) ?></span>
      <h1><?= e($spec['name']) ?></h1>
      <p><?= e($spec['short_description']) ?></p>
      <div class="care-hero__actions"><a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request an Appointment</a><a class="care-hero__phone" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a></div>
    </div>
    <?php if (!empty($spec['image'])): ?>
    <div class="care-hero__media"><?= image_or_placeholder($spec['image'], '', $spec['name'] . ' care at ' . SITE_NAME, false) ?></div>
    <?php endif; ?>
  </div>
</section>

<nav class="speciality-page-nav" aria-label="On this page">
  <div class="container">
    <span>Explore this care area</span>
    <?php foreach ($sections as $section): ?><a href="#speciality-section-<?= e($section['id']) ?>"><?= e($section['title']) ?></a><?php endforeach; ?>
    <a href="#speciality-visit-guide">Visit questions</a>
    <?php if ($treatments): ?><a href="#speciality-treatments">Related services</a><?php endif; ?>
    <?php if ($doctors): ?><a href="#speciality-doctors">Doctors</a><?php endif; ?>
    <a href="#speciality-faqs">FAQs</a>
  </div>
</nav>

<section class="section">
  <div class="container speciality-layout">
    <article class="prose reveal speciality-guide">
      <?php if ($guide): ?><p class="speciality-guide__meta">Patient education <span aria-hidden="true">·</span> <?= $readingMinutes ?> min read</p><?php endif; ?>
      <?php foreach ($sections as $section): ?>
        <section class="speciality-guide__section" aria-labelledby="speciality-section-<?= e($section['id']) ?>">
          <h2 id="speciality-section-<?= e($section['id']) ?>"><?= e($section['title']) ?></h2>
          <?php foreach (preg_split('/\n\s*\n/', trim((string)$section['body'])) as $paragraph): ?>
          <?php if (trim($paragraph) !== ''): ?><p><?= e(trim($paragraph)) ?></p><?php endif; ?>
          <?php endforeach; ?>
        </section>
      <?php endforeach; ?>
      <?php if (!$guide && count($sections) < 4): ?>
      <div class="speciality-content-note">
        <span class="eyebrow">More information</span>
        <strong>Additional details are being prepared</strong>
        <p>Contact the care team with questions about a personal concern or treatment option.</p>
      </div>
      <?php endif; ?>
      <div class="care-prose__notice">
        <strong>Planning your consultation?</strong>
        <p>Bring any previous eye reports, your current spectacles and a list of medicines or eye drops you use. Your clinician can explain which examinations or options are relevant to you.</p>
        <a class="link-arrow" href="<?= url('patient-journey') ?>">Read the patient visit guide</a>
      </div>
      <?php if (!empty($guide['sources'])): ?>
      <aside class="speciality-guide__sources" aria-label="Sources for this patient guide">
        <span class="eyebrow">Further reading</span>
        <h2>Trusted eye-health references</h2>
        <p>This guide is general education, not a substitute for an examination or personalised medical advice.</p>
        <ul>
          <?php foreach ($guide['sources'] as $source): ?>
          <li><a href="<?= e($source['url']) ?>" target="_blank" rel="noopener noreferrer"><?= e($source['label']) ?><span aria-hidden="true">↗</span></a></li>
          <?php endforeach; ?>
        </ul>
      </aside>
      <?php endif; ?>
      <p style="font-size:.85rem;color:var(--grey);border-top:1px solid var(--border);padding-top:16px;margin-top:28px">
        This information is for general education and does not replace a personal consultation. Treatment recommendations vary from patient to patient and are made only after a detailed eye examination.
      </p>
    </article>
    <aside class="reveal">
      <div class="contact-card speciality-layout__card">
        <h3 style="margin-bottom:14px">Book a Consultation</h3>
        <p style="font-size:.9rem;color:var(--grey);margin-bottom:18px">Get a personalised evaluation for <?= e($spec['name']) ?> at <?= e(SITE_AREA) ?>.</p>
        <div class="btn-row" style="flex-direction:column;align-items:stretch">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request Appointment</a>
          <a class="btn btn--outline" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a>
        </div>
        <?php if ($treatments): ?>
        <h3 style="margin:26px 0 10px;font-size:1rem">Related Treatments</h3>
        <ul style="display:grid;gap:6px">
          <?php foreach ($treatments as $t): ?>
          <li><a class="link-arrow" href="<?= url('treatments/' . $t['slug']) ?>"><?= e($t['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</section>

<section class="section speciality-visit-questions" id="speciality-visit-guide">
  <div class="container">
    <div class="speciality-visit-questions__heading">
      <div><span class="eyebrow">Plan your conversation</span><h2>Questions you can ask about <?= e($spec['name']) ?></h2></div>
      <p>Your care is individual. These prompts can help you discuss your concerns and understand the information shared at your appointment.</p>
    </div>
    <div class="speciality-visit-questions__grid">
      <article><span>01</span><h3>What will help clarify my concern?</h3><p>Ask what information, examination or records may be useful for your situation.</p></article>
      <article><span>02</span><h3>What do my findings mean?</h3><p>Invite the clinician to explain any results or terms in plain language and how they relate to you.</p></article>
      <article><span>03</span><h3>What choices should I understand?</h3><p>Ask about the purpose, expected next steps and important considerations for any option discussed.</p></article>
      <article><span>04</span><h3>What happens after this visit?</h3><p>Before you leave, check whether follow-up is needed and whom to contact if you have questions.</p></article>
    </div>
    <p class="speciality-visit-questions__note">These are general discussion prompts, not treatment advice. Your clinician can explain what is relevant to you.</p>
  </div>
</section>

<?php if ($treatments): ?>
<section class="section section--pale" id="speciality-treatments">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Related services</span><h2>Explore published treatment information</h2><p>These services are linked to <?= e($spec['name']) ?> in the hospital directory. A clinician can discuss which, if any, may apply to your care.</p></div>
    <div class="cards related-treatment-grid">
      <?php foreach ($treatments as $t): ?>
      <a class="service-card related-treatment-card reveal" href="<?= url('treatments/' . $t['slug']) ?>">
        <div class="service-card__img"><?= image_or_placeholder($t['image'], '', $t['name']) ?></div>
        <div class="service-card__body"><span class="eyebrow">Treatment information</span><h3><?= e($t['name']) ?></h3><p><?= e($t['short_description']) ?></p><span class="link-arrow">Read more</span></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($doctors): ?>
<section class="section section--pale" id="speciality-doctors">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Specialists</span><h2>Doctors for <?= e($spec['name']) ?></h2></div>
    <div class="cards">
      <?php foreach (array_slice($doctors, 0, 3) as $doc): ?>
      <article class="doctor-card reveal">
        <div class="doctor-card__photo"><?= image_or_placeholder($doc['photo'], '', 'Portrait of ' . $doc['name']) ?></div>
        <div class="doctor-card__body">
          <h3><?= e($doc['name']) ?></h3>
          <?php if ($doc['designation']): ?><p class="doctor-card__role"><?= e($doc['designation']) ?></p><?php endif; ?>
          <div class="doctor-card__actions"><a class="btn btn--outline" href="<?= url('doctors/' . $doc['slug']) ?>">View Profile</a></div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section" id="speciality-faqs">
  <div class="container">
    <div class="section-head center reveal"><span class="eyebrow">FAQs</span><h2>Questions About <?= e($spec['name']) ?></h2></div>
    <?php if ($faqs): ?>
    <div class="faq speciality-guide__faq">
      <?php foreach ($faqs as $faq): ?>
      <details><summary><?= e($faq['question']) ?><span class="plus">+</span></summary>
        <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="speciality-faq-note"><div class="speciality-faq-note__symbol" aria-hidden="true">?</div><div><strong>Answers for this speciality are being prepared</strong><p>For advice about <?= e($spec['name']) ?> or your own eye-care needs, speak with the hospital team. Personal recommendations are made after a consultation.</p></div><a class="btn btn--outline" href="<?= url('contact-us') ?>">Ask the hospital</a></div>
    <?php endif; ?>
  </div>
</section>

<section class="section section--green">
  <div class="container cta-final">
    <h2><?= e(setting('final_headline')) ?></h2>
    <p>Book an evaluation for <?= e($spec['name']) ?> today.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
