<?php
/** About Us - 8 premium sections. */
$route = '/about-us';
$page_seo = page_seo($route, 'About Us | ' . SITE_NAME, setting('about_intro'));
$doctors = db_all("SELECT * FROM doctors WHERE status='published' ORDER BY position");
$techs   = db_all("SELECT * FROM technologies WHERE status='published' ORDER BY position LIMIT 4");
$aboutSpecialities = db_all("SELECT name, slug, short_description, image FROM specialities WHERE status='published' ORDER BY is_featured DESC, position LIMIT 4");
$aboutFaqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND (context='about-us' OR context='website')
     ORDER BY CASE WHEN context='about-us' THEN 0 ELSE 1 END, position LIMIT 4"
);
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>About Us</span></nav>
    <h1>About <?= e(SITE_NAME) ?></h1>
    <p><?= e(SITE_TAGLINE) ?> — caring for the vision of our community in <?= e(SITE_AREA) ?>.</p>
  </div>
</section>

<!-- 1-2: Intro + Our Story -->
<section class="section">
  <div class="container split">
    <div class="reveal">
      <span class="eyebrow">Our Story</span>
      <h2>Care Built Around Clear Guidance</h2>
      <p style="color:var(--grey);margin-top:16px"><?= e(setting('about_intro')) ?></p>
      <p style="color:var(--grey);margin-top:12px">Located in Shalimar Bagh, Delhi, Jain Eye Hospital &amp; Laser Centre provides a place to discuss eye health, understand evaluation options and plan next steps with the clinical team.</p>
      <a class="btn btn--dark" style="margin-top:26px" href="<?= url('doctors') ?>">Meet Our Doctors</a>
    </div>
    <div class="media-stack reveal">
      <div class="media-stack__main"><img src="<?= asset('img/hospital-pic-2.webp') ?>" alt="Exterior of Jain Eye Hospital in Shalimar Bagh" loading="lazy" decoding="async"></div>
      <div class="media-stack__small"><img src="<?= asset('img/hospital-pic-15.webp') ?>" alt="A patient consultation at the hospital" loading="lazy" decoding="async"></div>
    </div>
  </div>
</section>

<!-- 3: Mission, Vision & Values -->
<section class="section section--green" id="mission-vision">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Mission, Vision &amp; Values</span>
      <h2>What Guides Us Every Day</h2>
    </div>
    <div class="cards">
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 21s-7.5-4.9-9.8-9.2C.6 8.6 2.4 5 5.8 5c2 0 3.4 1.1 4.2 2.3h4C14.8 6.1 16.2 5 18.2 5c3.4 0 5.2 3.6 3.6 6.8C19.5 16.1 12 21 12 21z"/></svg></div>
        <h3>Our Mission</h3><p>To support informed eye-care decisions with specialist evaluation, clear explanations and respectful patient care.</p></div>
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg></div>
        <h3>Our Vision</h3><p>To make specialist eye care easier to understand and access for people in Shalimar Bagh and nearby communities.</p></div>
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3zm-1.2 14.5-3.3-3.3 1.4-1.4 1.9 1.9 4.6-4.6 1.4 1.4-6 6z"/></svg></div>
        <h3>Our Values</h3><p>Listen carefully, explain options clearly and treat each patient with respect.</p></div>
    </div>
  </div>
</section>

<!-- 4: Clinical Approach -->
<section class="section">
  <div class="container split">
    <div class="media-stack reveal">
      <div class="media-stack__main"><img src="<?= asset('img/hospital-pic-16.webp') ?>" alt="An eye examination area at Jain Eye Hospital" loading="lazy" decoding="async"></div>
    </div>
    <div class="reveal">
      <span class="eyebrow">Clinical Approach</span>
      <h2>Thorough Evaluation. Honest Advice.</h2>
      <ul class="check-list">
        <li>Every treatment begins with a comprehensive eye examination</li>
        <li>Findings are explained in simple, clear language</li>
        <li>Non-surgical options are considered first wherever appropriate</li>
        <li>Surgery is advised only when genuinely indicated</li>
        <li>Structured follow-up care after every procedure</li>
      </ul>
      <a class="btn btn--outline" href="<?= url('patient-journey') ?>">See the Patient Journey</a>
    </div>
  </div>
</section>

<!-- 5: Leadership -->
<section class="section section--pale" id="leadership">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Leadership</span>
      <h2>Meet Our Eye Care Specialists</h2>
      <p>Read about the doctors listed by the hospital and contact the team to ask about a consultation.</p>
    </div>
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

<!-- 6: Team -->
<section class="section" id="our-team">
  <div class="container split split--wide-right">
    <div class="reveal">
      <span class="eyebrow">Our Team</span>
      <h2>A Complete Eye Care Team</h2>
      <p style="color:var(--grey);margin-top:16px">A hospital visit involves clinical care and practical support. The team can help you understand where to go, what information to bring and how to ask questions about your care.</p>
      <ul class="check-list">
        <li>Ask questions about your evaluation and next steps</li>
        <li>Request an explanation of the options discussed with you</li>
        <li>Contact the hospital about practical visit information</li>
      </ul>
    </div>
    <div class="media-stack reveal">
      <div class="media-stack__main"><img src="<?= asset('img/hospital-pic-7.webp') ?>" alt="Hospital staff at the reception desk" loading="lazy" decoding="async"></div>
      <div class="media-stack__small"><img src="<?= asset('img/hospital-pic-15.webp') ?>" alt="A clinician consulting with a patient" loading="lazy" decoding="async"></div>
    </div>
  </div>
</section>

<!-- 7: Facilities, Safety & Technology -->
<section class="section section--pale">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Facilities &amp; Safety</span>
      <h2>Inside the Hospital</h2>
      <p>Explore actual photographs of the hospital’s clinical, diagnostic and surgical spaces. Ask the clinical team for details about equipment and procedures relevant to your care.</p>
    </div>
    <div class="cards">
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3z"/></svg></div>
        <h3>Clinical spaces</h3><p>View the areas used for eye-care consultations and examinations.</p></div>
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M19 3h-4.2C14.4 1.8 13.3 1 12 1S9.6 1.8 9.2 3H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zM10 17l-3-3 1.4-1.4L10 14.2l5.6-5.6L17 10l-7 7z"/></svg></div>
        <h3>Ophthalmic equipment</h3><p>Equipment shown in hospital photographs. The care team can confirm device names and their role in an individual evaluation.</p></div>
      <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
        <h3>Surgical environment</h3><p>See the hospital’s operating-room photographs and ask the team about procedures and preparation.</p></div>
    </div>
    <div style="margin-top:34px"><a class="btn btn--dark" href="<?= url('technology') ?>">Explore Technology</a></div>
  </div>
</section>

<!-- 8: Location & Appointment -->
<section class="section section--green">
  <div class="container cta-final">
    <span class="eyebrow">Visit Us</span>
    <h2><?= e(setting('final_headline')) ?></h2>
    <p><?= e(SITE_ADDRESS) ?><br>
      <a href="tel:+911143784377" style="color:var(--orange-warm)"><?= e(SITE_PHONE_1) ?></a> ·
      <a href="tel:+919643536373" style="color:var(--orange-warm)"><?= e(SITE_PHONE_2) ?></a> ·
      <a href="tel:+919643900900" style="color:var(--orange-warm)"><?= e(SITE_PHONE_3) ?></a> ·
      <a href="mailto:<?= e(SITE_EMAIL) ?>" style="color:var(--orange-warm)"><?= e(SITE_EMAIL) ?></a></p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
      <a class="btn btn--light" href="<?= url('contact-us') ?>">Contact &amp; Directions</a>
    </div>
  </div>
  <div class="container about-resource-links">
    <?php if ($aboutSpecialities): ?>
    <div class="about-resource-links__services">
      <div class="section-head"><span class="eyebrow">Explore eye care</span><h2>Browse Our Specialities</h2></div>
      <div class="cards related-treatment-grid">
        <?php foreach ($aboutSpecialities as $speciality): ?>
        <a class="service-card related-treatment-card reveal" href="<?= url('specialities/' . $speciality['slug']) ?>">
          <div class="service-card__img"><?= image_or_placeholder($speciality['image'], '', $speciality['name']) ?></div>
          <div class="service-card__body"><h3><?= e($speciality['name']) ?></h3><p><?= e($speciality['short_description']) ?></p><span class="link-arrow">Explore speciality</span></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if ($aboutFaqs): ?>
    <div class="about-resource-links__faq">
      <div class="section-head center"><span class="eyebrow">Helpful answers</span><h2>Questions About the Hospital</h2></div>
      <div class="faq">
        <?php foreach ($aboutFaqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?><span class="plus" aria-hidden="true">+</span></summary><div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
