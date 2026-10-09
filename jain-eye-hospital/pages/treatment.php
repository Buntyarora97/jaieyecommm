<?php
/** Individual treatment page. */
$treatment = db_row("SELECT t.*, s.name AS spec_name, s.slug AS spec_slug FROM treatments t LEFT JOIN specialities s ON s.id=t.speciality_id WHERE t.slug = ? AND t.status='published'", [$slug]);
if (!$treatment) { http_response_code(404); require __DIR__ . '/404.php'; return; }

$route = '/treatments/' . $treatment['slug'];
$treatmentSummary = $treatment['short_description'] ?: 'Published information about ' . $treatment['name'] . '.';
$treatmentDescription = excerpt(
    excerpt($treatmentSummary, 92) . ' Learn more at ' . SITE_NAME . ', Shalimar Bagh, Delhi.',
    155
);
$treatmentSeoKeywords = array_filter([
    $treatment['name'] . ' Delhi',
    !empty($treatment['spec_name']) ? $treatment['spec_name'] . ' care' : '',
    'eye treatment Shalimar Bagh',
    SITE_NAME,
]);
$page_seo = page_seo(
    $route,
    $treatment['name'] . ' in Delhi | Jain Eye Hospital',
    $treatmentDescription,
    implode(', ', array_unique($treatmentSeoKeywords))
);
if (empty($page_seo['og_image']) && !empty($treatment['image'])) {
    $page_seo['og_image'] = str_starts_with($treatment['image'], 'assets/')
        ? url($treatment['image'])
        : uploads_url($treatment['image']);
}
$doctors = db_all("SELECT d.* FROM doctors d JOIN treatment_doctors td ON td.doctor_id=d.id WHERE td.treatment_id=? AND d.status='published'", [$treatment['id']]);
$relatedTreatments = $treatment['speciality_id']
    ? db_all("SELECT id, name, slug, short_description, image FROM treatments WHERE speciality_id=? AND status='published' AND id<>? ORDER BY position LIMIT 4", [$treatment['speciality_id'], $treatment['id']])
    : [];
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND (context=? OR context='website')
     ORDER BY CASE WHEN context=? THEN 0 ELSE 1 END, position LIMIT 5",
    [$treatment['slug'], $treatment['slug']]
);
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero care-hero">
  <div class="container care-hero__inner">
    <div class="care-hero__copy">
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><a href="<?= url('treatments') ?>">Treatments</a><span class="sep">/</span><span><?= e($treatment['name']) ?></span></nav>
      <span class="eyebrow">Treatment information</span>
      <h1><?= e($treatment['name']) ?></h1>
      <p><?= e($treatment['short_description']) ?></p>
      <div class="care-hero__actions"><a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request an Appointment</a><a class="care-hero__phone" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a></div>
    </div>
    <?php if (!empty($treatment['image'])): ?>
    <div class="care-hero__media"><?= image_or_placeholder($treatment['image'], '', $treatment['name'] . ' at ' . SITE_NAME, false) ?></div>
    <?php endif; ?>
  </div>
</section>
<section class="section">
  <div class="container split">
    <div class="prose reveal">
      <?php foreach (['overview'=>'About This Treatment','procedure_text'=>'How It Is Performed','benefits'=>'Benefits','recovery'=>'Recovery & After-Care'] as $field => $label): ?>
        <?php if (!empty($treatment[$field])): ?>
        <h2><?= e($label) ?></h2><p><?= nl2br(e($treatment[$field])) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
      <div class="care-prose__notice">
        <strong>Before deciding on a procedure</strong>
        <p>Ask your clinician why it has been recommended for you, what alternatives may be appropriate, how to prepare and what follow-up to expect. Suitability is assessed individually.</p>
        <a class="link-arrow" href="<?= url('patient-safety') ?>">Read patient safety guidance</a>
      </div>
      <?php if (empty($treatment['overview']) && empty($treatment['procedure_text'])): ?>
      <p>Detailed information about <?= e($treatment['name']) ?> is being updated. For personalised guidance, please book a consultation with our specialists or call <?= e(SITE_PHONE_1) ?>.</p>
      <?php endif; ?>
      <p style="font-size:.85rem;color:var(--grey);border-top:1px solid var(--border);padding-top:16px;margin-top:28px">
        Suitability for any procedure is decided only after a detailed eye examination. No outcome can be guaranteed for any medical treatment.
      </p>
    </div>
    <aside class="reveal">
      <div class="contact-card" style="position:sticky;top:110px">
        <h3 style="margin-bottom:14px">Plan Your Treatment</h3>
        <p style="font-size:.9rem;color:var(--grey);margin-bottom:18px">Discuss <?= e($treatment['name']) ?> with our specialists.</p>
        <div class="btn-row" style="flex-direction:column;align-items:stretch">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Request Appointment</a>
          <a class="btn btn--outline" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a>
        </div>
        <?php if ($treatment['spec_slug']): ?>
        <p style="margin-top:20px;font-size:.85rem">Speciality: <a href="<?= url('specialities/' . $treatment['spec_slug']) ?>"><?= e($treatment['spec_name']) ?></a></p>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</section>
<?php if ($doctors): ?>
<section class="section section--pale">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Specialists</span><h2>Consult Our Doctors</h2></div>
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
<?php if ($relatedTreatments): ?>
<section class="section section--pale">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Related services</span><h2>Explore More in <?= e($treatment['spec_name'] ?: 'Eye Care') ?></h2><p>Read about other services published under this speciality.</p></div>
    <div class="cards related-treatment-grid">
      <?php foreach ($relatedTreatments as $related): ?>
      <a class="service-card related-treatment-card reveal" href="<?= url('treatments/' . $related['slug']) ?>">
        <div class="service-card__img"><?= image_or_placeholder($related['image'], '', $related['name']) ?></div>
        <div class="service-card__body"><h3><?= e($related['name']) ?></h3><p><?= e($related['short_description']) ?></p><span class="link-arrow">Read treatment information</span></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php if ($faqs): ?>
<section class="section">
  <div class="container">
    <div class="section-head center reveal"><span class="eyebrow">Helpful answers</span><h2>Questions About <?= e($treatment['name']) ?></h2></div>
    <div class="faq">
      <?php foreach ($faqs as $faq): ?>
      <details><summary><?= e($faq['question']) ?><span class="plus" aria-hidden="true">+</span></summary><div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
