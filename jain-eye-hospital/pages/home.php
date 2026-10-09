<?php
/** Homepage - 12 signature sections, content pulled from MySQL. */
$route = '/';
$page_seo = page_seo('/', setting('hero_title') . ' | ' . SITE_NAME, setting('hero_description'));

$specialities = db_all("SELECT * FROM specialities WHERE status='published' ORDER BY is_featured DESC, position LIMIT 8");
$services     = db_all("SELECT t.*, s.name AS spec_name FROM treatments t LEFT JOIN specialities s ON s.id=t.speciality_id WHERE t.status='published' ORDER BY t.position LIMIT 8");
$doctors      = db_all("SELECT * FROM doctors WHERE status='published' ORDER BY is_featured DESC, position LIMIT 3");
$techs        = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY position LIMIT 3");
$testimonials = db_all("SELECT * FROM testimonials WHERE status='published' ORDER BY position LIMIT 3");
$faqs         = db_all("SELECT * FROM faqs WHERE is_active=1 ORDER BY position LIMIT 6");
$reel         = db_row("SELECT r.*, d.name AS doctor_name, d.photo AS doctor_photo
                        FROM reels r LEFT JOIN doctors d ON d.id=r.doctor_id
                        WHERE r.status='published'
                          AND ((r.video_path IS NOT NULL AND r.video_path <> '') OR (r.video_url IS NOT NULL AND r.video_url <> ''))
                        ORDER BY r.position LIMIT 1");
$reelAvailable = (bool) ($reel && (!empty($reel['video_path']) || safe_https_url($reel['video_url'] ?? null)));
$reelsTopics   = array_slice($specialities, 0, 4);
$posts         = db_all("SELECT p.*, c.name AS cat_name FROM blog_posts p LEFT JOIN blog_categories c ON c.id=p.category_id WHERE p.status='published' ORDER BY p.published_at DESC LIMIT 3");

require __DIR__ . '/../includes/header.php';
?>

<!-- SECTION 1: HERO -->
<section class="hero">
  <div class="container">
    <div class="hero__content">
      <p class="hero__place"><?= e(SITE_AREA) ?><span>Eye care, with a human touch</span></p>
      <span class="eyebrow"><?= e(setting('hero_eyebrow')) ?></span>
      <h1><?= e(setting('hero_title')) ?></h1>
      <p class="hero__secondary"><?= e(setting('hero_secondary')) ?></p>
      <p class="hindi"><?= e(setting('hero_hindi')) ?></p>
      <p class="hero__desc"><?= e(setting('hero_description')) ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
        <a class="btn btn--outline" href="<?= url('specialities') ?>">Explore Specialities</a>
      </div>
    </div>
    <div class="hero__media reveal">
      <div class="hero__frame">
        <img src="<?= asset('img/hospital-pic-15.webp') ?>" alt="A patient speaking with a Jain Eye Hospital clinician during a consultation" fetchpriority="high">
      </div>
      <span class="hero__arc" aria-hidden="true"></span>
      <div class="hero__card">
        <span class="dot"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 16H5V10h14v10zM9 14H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z"/></svg></span>
        <div>
          <strong>Book an Eye Consultation</strong>
          <span>Call <?= e(SITE_PHONE_1) ?> or request online</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 2: SPECIALITY DISCOVERY -->
<section class="section" id="home-specialities">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our Specialities</span>
      <h2>Find the Right Eye Care for You</h2>
      <p>From routine eye examinations to advanced surgical care, explore our super-speciality services.</p>
    </div>
    <div class="spec-strip">
      <?php foreach ($specialities as $spec): ?>
      <a class="spec-card reveal" href="<?= url('specialities/' . $spec['slug']) ?>">
        <div class="spec-card__img<?= $spec['slug'] === 'cataract-iol' ? ' spec-card__img--wide' : '' ?>"><?= image_or_placeholder($spec['image'], '', $spec['name']) ?></div>
        <div class="spec-card__body">
          <h3><?= e($spec['name']) ?></h3>
          <p><?= e($spec['short_description']) ?></p>
          <span class="link-arrow">Learn more</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SECTION 3: HOSPITAL INTRODUCTION -->
<section class="section section--pale" id="home-about">
  <div class="container split">
    <div class="media-stack reveal">
      <div class="media-stack__main"><img src="<?= asset('img/hospital-pic-8.webp') ?>" alt="Reception and waiting area at Jain Eye Hospital" loading="lazy" decoding="async"></div>
      <div class="media-stack__small"><img src="<?= asset('img/hospital-pic-15.webp') ?>" alt="A clinician consulting with a patient" loading="lazy" decoding="async"></div>
    </div>
    <div class="reveal">
      <span class="eyebrow">About the Hospital</span>
      <h2>Super-Speciality Eye Care, Close to Home</h2>
      <p style="color:var(--grey);margin-top:16px"><?= e(setting('about_intro')) ?></p>
      <p class="home-about__detail">Bring your questions and any previous eye-care reports to your consultation. Your clinician can explain the examination findings, relevant options and the next steps for your visit.</p>
      <ul class="check-list">
        <li>Comprehensive eye evaluation under one roof</li>
        <li>Specialist-led diagnosis and treatment planning</li>
        <li>Modern diagnostic, laser and surgical facilities</li>
        <li>Personalised guidance at every step of care</li>
      </ul>
      <a class="btn btn--dark" href="<?= url('about-us') ?>">Know More About Us</a>
    </div>
  </div>
</section>

<!-- SECTION 4: SERVICES CAROUSEL -->
<?php if ($services): ?>
<section class="section" id="home-treatments">
  <div class="container" data-carousel>
    <div class="section-head reveal">
      <span class="eyebrow">Treatments &amp; Procedures</span>
      <h2>Advanced Eye Treatments</h2>
      <p>Explore the treatments and procedures available at our centre.</p>
    </div>
    <div class="carousel">
      <div class="carousel__track">
        <?php foreach ($services as $srv): ?>
        <article class="service-card reveal">
          <div class="service-card__img<?= $srv['slug'] === 'cataract-surgery' ? ' service-card__img--wide' : '' ?>"><?= image_or_placeholder($srv['image'], '', $srv['name']) ?></div>
          <div class="service-card__body">
            <h3><?= e($srv['name']) ?></h3>
            <p><?= e($srv['short_description']) ?></p>
            <div class="service-card__actions">
              <a class="link-arrow" href="<?= url('treatments/' . $srv['slug']) ?>">Learn More</a>
              <a class="link-arrow" href="<?= url('book-appointment') ?>">Book Now</a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <div class="carousel__nav">
        <button class="carousel__btn" data-carousel-prev aria-label="Previous">&#8592;</button>
        <button class="carousel__btn" data-carousel-next aria-label="Next">&#8594;</button>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- SECTION 5: WHY CHOOSE JAIN EYE -->
<section class="section section--green" id="home-why-care">
  <div class="container split split--wide-right">
    <div class="reveal">
      <span class="eyebrow">Why Jain Eye</span>
      <h2>Care That Puts Your Vision First</h2>
      <p style="color:#BFD9CC;margin-top:16px">An eye-care decision starts with understanding your concerns, reviewing relevant information and discussing the options appropriate to your examination.</p>
      <div class="media-stack" style="margin-top:34px">
        <div class="media-stack__main" style="aspect-ratio:4/2.9"><img src="<?= asset('img/ophthalmic-microsurgery-in-a-clinical-theatre.webp') ?>" alt="Ophthalmic care in a clinical theatre" loading="lazy" decoding="async"></div>
      </div>
    </div>
    <div class="why-grid reveal">
      <?php
      $why = [
        ['Specialist-Led Care','Your evaluation and treatment are guided by experienced eye specialists.'],
        ['Personalised Treatment Planning','Recommendations are tailored to your eyes, lifestyle and visual needs.'],
        ['Comprehensive Services','Diagnosis, medical care, laser and surgery available under one roof.'],
        ['Patient-Focused Approach','Clear communication and comfortable care at every visit.'],
        ['Clear Treatment Guidance','We explain findings and options so you can make informed decisions.'],
        ['Commitment to Clinical Safety','Structured protocols and sterilisation standards across all procedures.'],
      ];
      foreach ($why as $i => $w): ?>
      <div class="why-item">
        <span class="why-item__num"><?= sprintf('%02d', $i + 1) ?></span>
        <div><h3><?= e($w[0]) ?></h3><p><?= e($w[1]) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SECTION 6: DOCTOR SHOWCASE -->
<?php if ($doctors): ?>
<section class="section" id="home-doctors">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Our Specialists</span>
      <h2>Meet Our Doctors</h2>
      <p>Consult with our experienced eye specialists.</p>
    </div>
    <div class="cards">
      <?php foreach ($doctors as $doc): ?>
      <article class="doctor-card reveal">
        <div class="doctor-card__photo">
          <?= image_or_placeholder($doc['photo'] ?? null, '', 'Portrait of ' . $doc['name']) ?>
        </div>
        <div class="doctor-card__body">
          <h3><?= e($doc['name']) ?></h3>
          <?php if ($doc['designation']): ?><p class="doctor-card__role"><?= e($doc['designation']) ?></p><?php endif; ?>
          <?php if ($doc['specialisation']): ?><p class="doctor-card__spec"><?= e($doc['specialisation']) ?></p><?php endif; ?>
          <div class="doctor-card__actions">
            <a class="btn btn--outline" href="<?= url('doctors/' . $doc['slug']) ?>">View Profile</a>
            <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Consultation</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- SECTION 7: TECHNOLOGY SHOWCASE -->
<section class="section section--pale" id="home-technology">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our Technology</span>
      <h2>Modern Diagnostics &amp; Surgical Care</h2>
      <p>Accurate diagnosis and precise treatment supported by contemporary ophthalmic technology.</p>
    </div>
    <?php if ($techs): ?>
    <div class="tech-grid">
      <?php foreach ($techs as $tech): ?>
      <a class="tech-card reveal" href="<?= url('technology') ?>#<?= e($tech['category']) ?>">
        <?= image_or_placeholder($tech['image'], '', $tech['name']) ?>
        <div class="tech-card__label">
          <span><?= e(ucfirst($tech['category'])) ?></span>
          <h3><?= e($tech['name']) ?></h3>
          <?php if (!empty($tech['short_description'])): ?><p><?= e($tech['short_description']) ?></p><?php endif; ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="cards cards--2">
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg></div><h3>Advanced Diagnostics</h3><p>Detailed imaging and evaluation support accurate diagnosis and treatment planning.</p></div>
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg></div><h3>Laser &amp; Surgical Precision</h3><p>Contemporary laser and microsurgical systems for cataract, refractive and retinal care.</p></div>
    </div>
    <?php endif; ?>
    <div style="margin-top:34px"><a class="btn btn--dark" href="<?= url('technology') ?>">Explore Our Technology</a></div>
  </div>
</section>

<!-- SECTION 8: PATIENT JOURNEY -->
<section class="section">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Your Visit, Simplified</span>
      <h2>Your Patient Journey</h2>
      <p>A clear, comfortable process from your first request to follow-up care.</p>
    </div>
    <div class="timeline">
      <?php
      $steps = [
        ['Request Appointment','Book online or call us - our team confirms your consultation slot.'],
        ['Eye Evaluation','A comprehensive examination and any required diagnostic tests.'],
        ['Treatment Discussion','Your specialist explains the findings and discusses suitable options.'],
        ['Follow-Up Guidance','Structured after-care instructions and scheduled reviews.'],
      ];
      foreach ($steps as $i => $s): ?>
      <div class="timeline__item reveal">
        <div class="timeline__dot"><?= $i + 1 ?></div>
        <h3><?= e($s[0]) ?></h3>
        <p><?= e($s[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SECTION 9: PATIENT EXPERIENCES & MEDIA -->
<section class="section section--pale">
  <div class="container">
    <?php if ($testimonials): ?>
    <div class="section-head reveal">
      <span class="eyebrow">Patient Experiences</span>
      <h2>What Our Patients Say</h2>
    </div>
    <div class="testi-grid" style="margin-bottom:56px">
      <?php foreach ($testimonials as $t): ?>
      <figure class="testi reveal">
        <span class="testi__quote">&ldquo;</span>
        <p><?= e($t['content']) ?></p>
        <figcaption class="testi__by"><?= e($t['patient_name']) ?><?php if ($t['context']): ?><span><?= e($t['context']) ?></span><?php endif; ?></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($posts): ?>
    <div class="section-head reveal">
      <span class="eyebrow">From Our Blog</span>
      <h2>Latest Articles &amp; Updates</h2>
    </div>
    <div class="blog-grid">
      <?php foreach ($posts as $post): ?>
      <article class="post-card reveal">
        <div class="post-card__img"><?= image_or_placeholder($post['featured_image'], '', $post['title']) ?></div>
        <div class="post-card__body">
          <div class="post-card__meta">
            <?php if ($post['cat_name']): ?><span class="post-card__cat"><?= e($post['cat_name']) ?></span><?php endif; ?>
            <span><?= format_date($post['published_at']) ?></span>
          </div>
          <h3><a href="<?= url('blog/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h3>
          <p><?= e($post['excerpt'] ?: excerpt($post['content'] ?? '', 120)) ?></p>
          <a class="link-arrow" href="<?= url('blog/' . $post['slug']) ?>">Read article</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if (!$testimonials && !$posts): ?>
      <div class="patient-care-panel reveal" id="home-patient-resources">
      <div>
        <span class="eyebrow">Patient Care</span>
        <h3>Plan your visit with clear, practical information</h3>
        <p>Know what to bring, how to request an appointment and where to find answers before you arrive. For personal medical advice, speak directly with your eye-care professional.</p>
      </div>
      <div class="patient-care-panel__links">
        <a href="<?= url('patient-journey') ?>"><strong>Before your visit</strong><span>Appointments, records and what to bring</span><b>Plan your visit <i aria-hidden="true">→</i></b></a>
        <a href="<?= url('patient-education') ?>"><strong>Patient education</strong><span>Read general eye-care information</span><b>Explore resources <i aria-hidden="true">→</i></b></a>
        <a href="<?= url('contact-us') ?>"><strong>Questions for our team?</strong><span>Contact details and directions</span><b>Get in touch <i aria-hidden="true">→</i></b></a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- SECTION 10: REELS EXPERIENCE -->
<section class="section" id="home-reels">
  <div class="container reels-wrap">
    <div class="reels-wrap__copy reveal">
      <span class="eyebrow">Watch &amp; Learn</span>
      <h2>Expert Eye Care Insights, One Reel at a Time</h2>
      <p class="reels-wrap__intro">
        <?php if ($reelAvailable): ?>
          Watch a hospital-published video, then explore more information about the eye-care areas below.
        <?php else: ?>
          No patient-education videos are available yet. You can still explore these published speciality pages and patient resources.
        <?php endif; ?>
      </p>
      <div class="reels-wrap__actions">
        <a class="btn btn--dark" href="<?= url($reelAvailable ? 'reels' : 'patient-education') ?>">
          <?= $reelAvailable ? 'View All Videos' : 'Explore Patient Education' ?>
        </a>
        <?php if (!$reelAvailable): ?>
        <a class="reels-wrap__library-link" href="<?= url('reels') ?>">Visit Videos &amp; Reels <span aria-hidden="true">→</span></a>
        <?php endif; ?>
      </div>
    </div>
    <div class="phone reveal" data-phone>
      <span class="phone__notch" aria-hidden="true"></span>
      <div class="phone__screen">
        <?php if ($reel && $reel['video_path']): ?>
        <video src="<?= e(uploads_url($reel['video_path'])) ?>" <?= $reel['thumbnail'] ? 'poster="' . e(uploads_url($reel['thumbnail'])) . '"' : '' ?> preload="none" muted playsinline loop></video>
        <div class="phone__controls">
          <button class="phone__btn" data-play aria-label="Play or pause video"><span class="phone__icon phone__icon--play" aria-hidden="true"></span></button>
          <button class="phone__btn" data-mute aria-label="Mute or unmute video"><span class="phone__icon phone__icon--sound" aria-hidden="true"></span></button>
        </div>
        <?php elseif ($reel && ($featuredReelUrl = safe_https_url($reel['video_url'] ?? null))): ?>
        <a class="phone__external-reel" href="<?= e($featuredReelUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Open <?= e($reel['title']) ?> on its original video platform">
          <?= image_or_placeholder($reel['thumbnail'] ?: $reel['doctor_photo'], 'phone__external-reel-image', $reel['thumbnail'] ? 'Thumbnail for ' . $reel['title'] : 'Portrait of ' . ($reel['doctor_name'] ?: 'the featured doctor')) ?>
          <span class="phone__external-reel-play" aria-hidden="true">▶</span>
          <span class="phone__external-reel-label"><?= e($reel['title']) ?> <i aria-hidden="true">↗</i></span>
        </a>
        <?php else: ?>
        <div class="phone__empty phone__empty--poster">
          <img src="<?= asset('img/hospital-pic-15.webp') ?>" alt="A clinician speaking with a patient at Jain Eye Hospital" loading="lazy" decoding="async">
          <div class="phone__empty-caption">
            <span class="eyebrow">From the Hospital</span>
            <p>Patient education videos will appear here when available.</p>
            <a href="<?= url('reels') ?>">Visit Videos &amp; Reels &rarr;</a>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="reels-wrap__topics reveal">
      <div class="reels-wrap__topics-head">
        <div>
          <span class="eyebrow">Explore by speciality</span>
          <h3>Find information by care area</h3>
        </div>
        <a class="reels-wrap__all-topics" href="<?= url('specialities') ?>">View all specialities <span aria-hidden="true">→</span></a>
      </div>
      <?php if ($reelsTopics): ?>
      <div class="reels-topic-grid" aria-label="Featured eye-care specialities">
        <?php foreach ($reelsTopics as $index => $topic): ?>
        <a class="reels-topic-card" href="<?= url('specialities/' . $topic['slug']) ?>">
          <span class="reels-topic-card__number" aria-hidden="true"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
          <span class="reels-topic-card__body">
            <strong><?= e($topic['name']) ?></strong>
            <span class="reels-topic-card__description"><?= e(excerpt($topic['short_description'] ?? '', 105)) ?></span>
            <span class="reels-topic-card__link">Explore speciality <i aria-hidden="true">→</i></span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="reels-wrap__topics-empty">Browse all published eye-care specialities to explore the hospital’s available information.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- SECTION 11: FAQ -->
<?php if ($faqs): ?>
<section class="section section--pale">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Common Questions</span>
      <h2>Frequently Asked Questions</h2>
    </div>
    <div class="faq">
      <?php foreach ($faqs as $faq): ?>
      <details class="reveal">
        <summary><?= e($faq['question']) ?><span class="plus" aria-hidden="true">+</span></summary>
        <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div>
      </details>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:30px"><a class="link-arrow" href="<?= url('faqs') ?>">View all FAQs</a></div>
  </div>
</section>
<?php endif; ?>

<!-- SECTION 12: LOCATION & CONVERSION -->
<section class="section" id="visit">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Visit Us</span>
      <h2><?= e(setting('final_headline')) ?></h2>
    </div>
    <div class="location-grid">
      <div class="contact-card reveal">
        <div class="contact-row">
          <span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg></span>
          <div><strong>Address</strong><span><?= e(SITE_ADDRESS) ?></span></div>
        </div>
        <div class="contact-row">
          <span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg></span>
          <div><strong>Phone</strong>
            <a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a> &nbsp;·&nbsp;
            <a href="tel:+919643536373"><?= e(SITE_PHONE_2) ?></a> &nbsp;·&nbsp;
            <a href="tel:+919643900900"><?= e(SITE_PHONE_3) ?></a>
          </div>
        </div>
        <div class="contact-row">
          <span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
          <div><strong>Email</strong><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></div>
        </div>
        <div class="btn-row" style="margin-top:22px">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
          <a class="btn btn--outline" href="<?= e(SITE_MAPS_URL) ?>" target="_blank" rel="noopener">Get Directions</a>
        </div>
      </div>
      <div class="map-embed reveal">
        <iframe src="<?= e(SITE_MAPS_EMBED) ?>" loading="lazy" title="Map - <?= e(SITE_NAME) ?>, Shalimar Bagh, Delhi" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
