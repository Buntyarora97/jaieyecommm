<?php
/** Individual doctor profile. */
$doctor = db_row("SELECT * FROM doctors WHERE slug = ? AND status='published'", [$slug]);
if (!$doctor) { http_response_code(404); require __DIR__ . '/404.php'; return; }

$route = '/doctors/' . $doctor['slug'];
$page_seo = page_seo($route, $doctor['name'] . ' | ' . SITE_NAME,
    excerpt(($doctor['specialisation'] ?? '') . ' at ' . SITE_NAME . '. ' . ($doctor['biography'] ?? ''), 155));
$related_specs = db_all("SELECT s.* FROM specialities s JOIN doctor_specialities ds ON ds.speciality_id=s.id WHERE ds.doctor_id=? AND s.status='published'", [$doctor['id']]);
$related_treatments = db_all(
    "SELECT DISTINCT t.* FROM treatments t JOIN treatment_doctors td ON td.treatment_id=t.id
     WHERE td.doctor_id=? AND t.status='published' ORDER BY t.position LIMIT 6",
    [$doctor['id']]
);
$related_posts = db_all("SELECT * FROM blog_posts WHERE status='published' ORDER BY published_at DESC LIMIT 3");
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND (context=? OR context='website')
     ORDER BY CASE WHEN context=? THEN 0 ELSE 1 END, position LIMIT 4",
    [$doctor['slug'], $doctor['slug']]
);

$extra_head = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org', '@type' => 'Physician',
    'name' => $doctor['name'],
    'medicalSpecialty' => $doctor['specialisation'] ?: 'Ophthalmology',
    'worksFor' => ['@type' => 'Hospital', 'name' => SITE_NAME],
], JSON_UNESCAPED_SLASHES) . '</script>';

require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><a href="<?= url('doctors') ?>">Our Doctors</a><span class="sep">/</span><span><?= e($doctor['name']) ?></span></nav>
    <h1><?= e($doctor['name']) ?></h1>
    <?php if ($doctor['designation']): ?><p><?= e($doctor['designation']) ?><?= $doctor['specialisation'] ? ' · ' . e($doctor['specialisation']) : '' ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="profile-hero">
      <div class="profile-hero__photo reveal"><?= image_or_placeholder($doctor['photo'], '', 'Portrait of ' . $doctor['name'], false) ?></div>
      <div class="reveal">
        <span class="eyebrow">Profile</span>
        <h2><?= e($doctor['name']) ?></h2>
        <ul class="cred-list">
          <?php if ($doctor['qualifications']): ?><li><?= e($doctor['qualifications']) ?></li><?php endif; ?>
          <?php if ($doctor['designation']): ?><li><?= e($doctor['designation']) ?></li><?php endif; ?>
          <?php if ($doctor['specialisation']): ?><li>Specialisation: <?= e($doctor['specialisation']) ?></li><?php endif; ?>
        </ul>
        <?php if ($doctor['biography']): ?>
        <div class="prose"><?= nl2br(e($doctor['biography'])) ?></div>
        <?php else: ?>
        <p style="color:var(--grey)">Detailed profile for <?= e($doctor['name']) ?> is being updated. Please call <?= e(SITE_PHONE_1) ?> for appointment availability.</p>
        <?php endif; ?>
        <div class="btn-row" style="margin-top:24px">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Consultation</a>
          <a class="btn btn--outline" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a>
        </div>
      </div>
    </div>

    <div class="prose" style="max-width:840px;margin-top:60px">
      <?php foreach (['education'=>'Education & Training','expertise'=>'Clinical Expertise','fellowships'=>'Fellowships','memberships'=>'Memberships','awards_text'=>'Awards & Recognition'] as $field => $label): ?>
        <?php if (!empty($doctor[$field])): ?>
        <h2><?= e($label) ?></h2>
        <p><?= nl2br(e($doctor[$field])) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <?php if ($related_specs): ?>
    <div style="margin-top:56px">
      <h3 style="margin-bottom:18px">Areas of Care</h3>
      <div class="cards cards--2">
        <?php foreach ($related_specs as $spec): ?>
        <a class="card" href="<?= url('specialities/' . $spec['slug']) ?>"><h3><?= e($spec['name']) ?></h3><p><?= e($spec['short_description']) ?></p></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($related_treatments): ?>
    <div class="doctor-related-services">
      <div class="section-head"><span class="eyebrow">Published services</span><h2>Care Information</h2><p>These are the services linked to this profile in the hospital’s published directory.</p></div>
      <div class="cards related-treatment-grid">
        <?php foreach ($related_treatments as $treatment): ?>
        <a class="service-card related-treatment-card reveal" href="<?= url('treatments/' . $treatment['slug']) ?>">
          <div class="service-card__img"><?= image_or_placeholder($treatment['image'], '', $treatment['name']) ?></div>
          <div class="service-card__body"><h3><?= e($treatment['name']) ?></h3><p><?= e($treatment['short_description']) ?></p><span class="link-arrow">Read treatment information</span></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($related_posts): ?>
    <div class="doctor-related-services">
      <div class="section-head"><span class="eyebrow">Patient education</span><h2>From the Eye-Care Library</h2></div>
      <div class="blog-grid">
        <?php foreach ($related_posts as $post): ?>
        <article class="post-card reveal">
          <a class="post-card__img" href="<?= url('blog/' . $post['slug']) ?>"><?= image_or_placeholder($post['featured_image'], '', $post['title']) ?></a>
          <div class="post-card__body"><h3><a href="<?= url('blog/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h3>
            <?php if (!empty($post['excerpt'])): ?><p><?= e($post['excerpt']) ?></p><?php endif; ?>
            <a class="link-arrow" href="<?= url('blog/' . $post['slug']) ?>">Read article</a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($faqs): ?>
    <div style="margin-top:60px">
      <div class="section-head"><span class="eyebrow">FAQs</span><h2>Common Questions</h2></div>
      <div class="faq">
        <?php foreach ($faqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?><span class="plus">+</span></summary>
          <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="section section--green">
  <div class="container cta-final">
    <h2>Consult <?= e($doctor['name']) ?></h2>
    <p>Request an appointment and our team will confirm your consultation.</p>
    <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
