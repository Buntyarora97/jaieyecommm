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
$doctor_gallery = db_all(
    "SELECT image, caption FROM doctor_gallery WHERE doctor_id=? ORDER BY position, id",
    [$doctor['id']]
);
$doctor_reels = db_all(
    "SELECT id, title, description, video_path, video_url, thumbnail FROM reels
     WHERE doctor_id=? AND status='published'
       AND ((video_path IS NOT NULL AND video_path <> '') OR (video_url IS NOT NULL AND video_url <> ''))
     ORDER BY position, id",
    [$doctor['id']]
);
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND context=? ORDER BY position LIMIT 8",
    [$doctor['slug']]
);

$extra_head = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org', '@type' => 'Physician',
    'name' => $doctor['name'],
    'medicalSpecialty' => $doctor['specialisation'] ?: 'Ophthalmology',
    'worksFor' => ['@type' => 'Hospital', 'name' => SITE_NAME],
], JSON_UNESCAPED_SLASHES) . '</script>';

require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero doctor-page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><a href="<?= url('doctors') ?>">Our Doctors</a><span class="sep">/</span><span><?= e($doctor['name']) ?></span></nav>
    <h1><?= e($doctor['name']) ?></h1>
    <?php if ($doctor['designation']): ?><p><?= e($doctor['designation']) ?><?= $doctor['specialisation'] ? ' · ' . e($doctor['specialisation']) : '' ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="profile-hero">
      <div class="doctor-profile__portrait reveal">
        <div class="profile-hero__photo"><?= image_or_placeholder($doctor['photo'], '', 'Portrait of ' . $doctor['name'], false) ?></div>
        <span class="doctor-profile__portrait-caption"><span>Jain Eye Hospital</span><strong><?= e($doctor['name']) ?></strong></span>
      </div>
      <div class="doctor-profile__intro reveal">
        <span class="eyebrow">Meet your doctor</span>
        <h2><?= e($doctor['name']) ?></h2>
        <?php if ($doctor['specialisation']): ?><p class="doctor-profile__specialty"><?= e($doctor['specialisation']) ?></p><?php endif; ?>
        <ul class="cred-list">
          <?php if ($doctor['qualifications']): ?><li><?= e($doctor['qualifications']) ?></li><?php endif; ?>
          <?php if ($doctor['designation']): ?><li><?= e($doctor['designation']) ?></li><?php endif; ?>
        </ul>
        <?php if ($doctor['biography']): ?>
        <div class="prose"><?= nl2br(e($doctor['biography'])) ?></div>
        <?php else: ?>
        <div class="doctor-profile__notice"><strong>More profile information is being prepared</strong><p>The hospital has not published a biography for <?= e($doctor['name']) ?> yet. Contact the team to confirm consultation availability and current profile details.</p></div>
        <?php endif; ?>
        <div class="btn-row" style="margin-top:24px">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Consultation</a>
          <a class="btn btn--outline" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a>
        </div>
      </div>
    </div>

    <nav class="doctor-profile-nav" aria-label="On this page">
      <span>Explore this profile</span>
      <a href="#doctor-details">Doctor information</a>
      <a href="#doctor-reels">Videos by <?= e($doctor['name']) ?></a>
      <a href="#doctor-faqs">FAQs</a>
      <?php if ($doctor_gallery): ?><a href="#doctor-gallery">Photo gallery</a><?php endif; ?>
      <?php if ($related_specs): ?><a href="#doctor-specialities">Areas of care</a><?php endif; ?>
      <?php if ($related_treatments): ?><a href="#doctor-treatments">Care information</a><?php endif; ?>
    </nav>

    <div class="prose doctor-profile-details" id="doctor-details">
      <div class="doctor-profile-details__heading"><span class="eyebrow">Published information</span><h2>About <?= e($doctor['name']) ?></h2><p>Profile details are shown as supplied by the hospital.</p></div>
      <?php foreach (['education'=>'Education & Training','expertise'=>'Clinical Expertise','fellowships'=>'Fellowships','memberships'=>'Memberships','awards_text'=>'Awards & Recognition'] as $field => $label): ?>
        <?php if (!empty($doctor[$field])): ?>
        <h2><?= e($label) ?></h2>
        <p><?= nl2br(e($doctor[$field])) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php if (empty($doctor['education']) && empty($doctor['expertise']) && empty($doctor['fellowships']) && empty($doctor['memberships']) && empty($doctor['awards_text'])): ?>
      <div class="doctor-profile__notice"><strong>Verified profile details are not published yet</strong><p>Qualifications, training and areas of practice will be shown here when the hospital provides and approves them.</p></div>
      <?php endif; ?>
    </div>

    <section class="doctor-visit-guide" aria-labelledby="doctorVisitGuideTitle">
      <div class="doctor-visit-guide__intro">
        <span class="eyebrow">Make your appointment count</span>
        <h2 id="doctorVisitGuideTitle">A few details can help you prepare.</h2>
        <p>Every visit is individual. Use these practical prompts to share your concerns and understand the next step.</p>
      </div>
      <div class="doctor-visit-guide__list">
        <article><span>01</span><div><h3>Bring what you already have</h3><p>Previous eye reports, prescriptions and your current spectacles can help you and the care team refer to earlier information.</p></div></article>
        <article><span>02</span><div><h3>Describe what brought you in</h3><p>Note what you have noticed and any questions you want to discuss. Ask the doctor to explain unfamiliar terms.</p></div></article>
        <article><span>03</span><div><h3>Ask about the next step</h3><p>Before you leave, check what happens next, who to contact with questions and whether any follow-up is planned.</p></div></article>
      </div>
    </section>

    <?php if ($related_specs): ?>
    <div class="doctor-related-services" id="doctor-specialities">
      <div class="section-head"><span class="eyebrow">Areas of care</span><h2>Specialities linked to this profile</h2><p>Explore the hospital’s published information for each care area.</p></div>
      <div class="cards cards--2">
        <?php foreach ($related_specs as $spec): ?>
        <a class="card" href="<?= url('specialities/' . $spec['slug']) ?>"><h3><?= e($spec['name']) ?></h3><p><?= e($spec['short_description']) ?></p></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($related_treatments): ?>
    <div class="doctor-related-services" id="doctor-treatments">
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

    <section class="doctor-reels-section" id="doctor-reels" aria-labelledby="doctorReelsHeading">
      <div class="doctor-reels-section__heading">
        <div><span class="eyebrow">Watch &amp; learn</span><h2 id="doctorReelsHeading">Videos by <?= e($doctor['name']) ?></h2><p>Patient education videos linked to this doctor’s profile by the hospital.</p></div>
        <a class="link-arrow" href="<?= url('reels') ?>">View all videos</a>
      </div>
      <?php if ($doctor_reels): ?>
      <div class="doctor-reel-grid">
        <?php foreach ($doctor_reels as $reel): ?>
        <?php $externalReelUrl = safe_https_url($reel['video_url'] ?? null); ?>
        <article class="doctor-reel-card">
          <?php if (!empty($reel['video_path'])): ?>
          <div class="doctor-reel-card__video">
            <video controls playsinline preload="none" <?= $reel['thumbnail'] ? 'poster="' . e(uploads_url($reel['thumbnail'])) . '"' : '' ?>>
              <source src="<?= e(uploads_url($reel['video_path'])) ?>">
              Your browser does not support video playback.
            </video>
          </div>
          <?php elseif ($externalReelUrl): ?>
          <?php
            $reelHost = strtolower((string)parse_url($externalReelUrl, PHP_URL_HOST));
            $reelPlatform = ($reelHost === 'instagram.com' || str_ends_with($reelHost, '.instagram.com'))
                ? 'Watch on Instagram'
                : (($reelHost === 'youtube.com' || str_ends_with($reelHost, '.youtube.com') || $reelHost === 'youtu.be')
                    ? 'Watch on YouTube'
                    : 'Watch this video');
          ?>
          <a class="doctor-reel-card__external" href="<?= e($externalReelUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($reelPlatform . ': ' . $reel['title']) ?>">
            <?= image_or_placeholder($reel['thumbnail'] ?: $doctor['photo'], '', 'Video thumbnail for ' . $reel['title']) ?>
            <span class="doctor-reel-card__external-play" aria-hidden="true">▶</span>
            <span class="doctor-reel-card__external-label"><?= e($reelPlatform) ?> <i aria-hidden="true">↗</i></span>
          </a>
          <?php endif; ?>
          <div class="doctor-reel-card__body"><span class="eyebrow"><?= e($doctor['name']) ?></span><h3><?= e($reel['title']) ?></h3>
            <?php if (!empty($reel['description'])): ?><p><?= e($reel['description']) ?></p><?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="doctor-reels-empty">
        <div class="doctor-reels-empty__portrait"><?= image_or_placeholder($doctor['photo'], '', 'Portrait of ' . $doctor['name']) ?><span aria-hidden="true">▶</span></div>
        <div><span class="eyebrow"><?= e($doctor['name']) ?></span><strong>Doctor reels will appear here.</strong><p>This profile has its own space for the doctor’s real, hospital-approved video links or clips. No videos have been published for this profile yet.</p></div>
        <a class="btn btn--outline" href="<?= url('book-appointment') ?>">Book a consultation</a>
      </div>
      <?php endif; ?>
    </section>

    <?php if ($doctor_gallery): ?>
    <section class="doctor-gallery-section" id="doctor-gallery" aria-labelledby="doctorGalleryHeading">
      <div class="section-head"><span class="eyebrow">At the hospital</span><h2 id="doctorGalleryHeading"><?= e($doctor['name']) ?> photo gallery</h2></div>
      <div class="doctor-photo-grid">
        <?php foreach ($doctor_gallery as $photo): ?>
        <figure><?= image_or_placeholder($photo['image'], '', $photo['caption'] ?: 'Photo of ' . $doctor['name']) ?><?php if ($photo['caption']): ?><figcaption><?= e($photo['caption']) ?></figcaption><?php endif; ?></figure>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <div class="doctor-profile-faq" id="doctor-faqs">
      <div class="section-head"><span class="eyebrow">Doctor FAQs</span><h2>Questions about <?= e($doctor['name']) ?></h2></div>
      <?php if ($faqs): ?>
      <div class="faq">
        <?php foreach ($faqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?><span class="plus">+</span></summary>
          <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="speciality-faq-note"><div class="speciality-faq-note__symbol" aria-hidden="true">?</div><div><strong>Doctor-specific answers are being prepared</strong><p>Contact the hospital team to confirm current profile details or ask a question about booking.</p></div><a class="btn btn--outline" href="<?= url('contact-us') ?>">Contact the hospital</a></div>
      <?php endif; ?>
    </div>
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
