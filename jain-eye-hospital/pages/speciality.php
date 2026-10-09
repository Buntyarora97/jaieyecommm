<?php
/** Individual speciality page: overview, symptoms, diagnostics, treatments, doctors, FAQs. */
$spec = db_row("SELECT * FROM specialities WHERE slug = ? AND status='published'", [$slug]);
if (!$spec) { http_response_code(404); require __DIR__ . '/404.php'; return; }

$route = '/specialities/' . $spec['slug'];
$page_seo = page_seo($route, $spec['name'] . ' in Shalimar Bagh, Delhi | ' . SITE_NAME,
    excerpt($spec['short_description'] ?: $spec['overview'] ?: '', 155));
$treatments = db_all("SELECT * FROM treatments WHERE speciality_id = ? AND status='published' ORDER BY position", [$spec['id']]);
$doctors = db_all("SELECT d.* FROM doctors d JOIN doctor_specialities ds ON ds.doctor_id=d.id WHERE ds.speciality_id=? AND d.status='published'", [$spec['id']]);
$faqs = db_all("SELECT * FROM faqs WHERE is_active=1 AND (context=? OR context='website') ORDER BY position LIMIT 5", [$spec['slug']]);
$posts = db_all("SELECT * FROM blog_posts WHERE status='published' ORDER BY published_at DESC LIMIT 3");

$sections = [
    'overview'        => 'Overview',
    'symptoms'        => 'Symptoms',
    'conditions'      => 'Conditions We Manage',
    'diagnostics'     => 'Diagnostic Evaluation',
    'treatments_text' => 'Treatment Options',
    'technology_text' => 'Technology',
    'what_to_expect'  => 'What to Expect',
    'recovery'        => 'Recovery & Follow-Up',
];
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

<section class="section">
  <div class="container split">
    <div class="prose reveal">
      <?php foreach ($sections as $field => $label): ?>
        <?php if (!empty($spec[$field])): ?>
        <h2><?= e($label) ?></h2>
        <p><?= nl2br(e($spec[$field])) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
      <div class="care-prose__notice">
        <strong>Planning your consultation?</strong>
        <p>Bring any previous eye reports, your current spectacles and a list of medicines or eye drops you use. Your clinician can explain which examinations or options are relevant to you.</p>
        <a class="link-arrow" href="<?= url('patient-journey') ?>">Read the patient visit guide</a>
      </div>
      <p style="font-size:.85rem;color:var(--grey);border-top:1px solid var(--border);padding-top:16px;margin-top:28px">
        This information is for general education and does not replace a personal consultation. Treatment recommendations vary from patient to patient and are made only after a detailed eye examination.
      </p>
    </div>
    <aside class="reveal">
      <div class="contact-card" style="position:sticky;top:110px">
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

<?php if ($doctors): ?>
<section class="section section--pale">
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

<?php if ($faqs): ?>
<section class="section">
  <div class="container">
    <div class="section-head center reveal"><span class="eyebrow">FAQs</span><h2>Questions About <?= e($spec['name']) ?></h2></div>
    <div class="faq">
      <?php foreach ($faqs as $faq): ?>
      <details><summary><?= e($faq['question']) ?><span class="plus">+</span></summary>
        <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section--green">
  <div class="container cta-final">
    <h2><?= e(setting('final_headline')) ?></h2>
    <p>Book an evaluation for <?= e($spec['name']) ?> today.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
