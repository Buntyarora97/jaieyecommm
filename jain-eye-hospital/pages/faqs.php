<?php
$route = '/faqs';
$page_seo = page_seo($route, 'FAQs | ' . SITE_NAME, 'Frequently asked questions about eye care at ' . SITE_NAME . '.');
$rows = db_all("SELECT * FROM faqs WHERE is_active=1 ORDER BY category, position");
$grouped = [];
foreach ($rows as $row) { $grouped[$row['category']][] = $row; }
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>FAQs</span></nav>
  <h1>Frequently Asked Questions</h1><p>Answers to common questions about eye care and visiting our hospital.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($grouped): foreach ($grouped as $cat => $faqs): ?>
  <div style="margin-bottom:40px">
    <h3 style="margin-bottom:16px"><?= e($cat) ?></h3>
    <div class="faq" style="max-width:100%">
      <?php foreach ($faqs as $faq): ?>
      <details><summary><?= e($faq['question']) ?><span class="plus">+</span></summary>
        <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; else: ?>
  <div class="empty-state"><strong>FAQs coming soon</strong>Have a question? Call <?= e(SITE_PHONE_1) ?> or send us a message.</div>
  <?php endif; ?>
</div></section>
<section class="section section--green"><div class="container cta-final">
  <h2>Still Have a Question?</h2><p>Our team is happy to help.</p>
  <a class="btn btn--primary" href="<?= url('contact-us') ?>">Contact Us</a>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
