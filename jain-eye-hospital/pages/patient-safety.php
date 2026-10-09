<?php
$route = '/patient-safety';
$page_seo = page_seo($route, 'Patient Safety | ' . SITE_NAME, 'Questions patients can ask about safety and preparation for eye care.');
$faqs = db_all(
    "SELECT * FROM faqs WHERE is_active=1 AND (context='patient-safety' OR context='website')
     ORDER BY CASE WHEN context='patient-safety' THEN 0 ELSE 1 END, position LIMIT 5"
);
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Patient Safety</span></nav>
  <h1>Patient Safety</h1><p>Use these questions to take part in informed conversations about your care.</p>
</div></section>
<section class="section"><div class="container">
  <div class="cards">
    <?php
    $items = [
      ['Before an examination','Tell your clinician about current symptoms, medicines, eye drops, allergies and relevant health conditions.'],
      ['Before a procedure','Ask what will be done, which eye is involved, what alternatives exist, and what risks may apply to you.'],
      ['Informed decisions','Take the time you need to understand the explanation and ask questions before making a decision.'],
      ['Medicines and after-care','Ask which medicines or drops to use, how to use them and what follow-up is recommended.'],
      ['Who to contact','Before leaving, ask how to reach the care team if you have questions or new concerns.'],
      ['Urgent symptoms','Sudden vision loss, severe pain or an eye injury may require urgent medical assessment; do not wait for a website reply.'],
    ];
    foreach ($items as $item): ?>
    <div class="card reveal"><div class="card__icon"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.1 3.4 9.9 8 11 4.6-1.1 8-5.9 8-11V5l-8-3zm-1.2 14.5-3.3-3.3 1.4-1.4 1.9 1.9 4.6-4.6 1.4 1.4-6 6z"/></svg></div>
      <h3><?= e($item[0]) ?></h3><p><?= e($item[1]) ?></p></div>
    <?php endforeach; ?>
  </div>
  <p style="color:var(--grey);margin-top:24px">This page offers general information and does not describe or certify specific hospital protocols. Ask your clinician about the precautions relevant to your care.</p>
  <?php if ($faqs): ?>
  <div class="patient-resource-faq">
    <div class="section-head center"><span class="eyebrow">More information</span><h2>Patient Safety Questions</h2></div>
    <div class="faq">
      <?php foreach ($faqs as $faq): ?>
      <details><summary><?= e($faq['question']) ?><span class="plus" aria-hidden="true">+</span></summary><div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="patient-safety-cta"><strong>Need information about an appointment?</strong><a class="link-arrow" href="<?= url('contact-us') ?>">Contact the hospital team</a></div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
