<?php
$route = '/patient-education';
$page_seo = page_seo($route, 'Patient Education | ' . SITE_NAME, 'Eye health education resources from ' . SITE_NAME . '.');
$posts = db_all("SELECT p.*, c.name AS cat_name FROM blog_posts p LEFT JOIN blog_categories c ON c.id=p.category_id WHERE p.status='published' ORDER BY p.published_at DESC LIMIT 9");
$faqs = db_all("SELECT * FROM faqs WHERE is_active=1 ORDER BY position LIMIT 8");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Patient Education</span></nav>
  <h1>Patient Education</h1><p>Understand your eyes, treatments and recovery with our resources.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($posts): ?>
  <div class="section-head reveal"><span class="eyebrow">Articles</span><h2>Eye Health Resources</h2></div>
  <div class="blog-grid">
    <?php foreach ($posts as $post): ?>
    <article class="post-card reveal">
      <div class="post-card__img"><?= image_or_placeholder($post['featured_image'], '', $post['title']) ?></div>
      <div class="post-card__body">
        <h3><a href="<?= url('blog/' . $post['slug']) ?>"><?= e($post['title']) ?></a></h3>
        <p><?= e($post['excerpt'] ?: excerpt($post['content'] ?? '', 120)) ?></p>
        <a class="link-arrow" href="<?= url('blog/' . $post['slug']) ?>">Read article</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <?php if ($faqs): ?>
  <div class="section-head center reveal" style="margin-top:70px"><span class="eyebrow">FAQs</span><h2>Common Questions</h2></div>
  <div class="faq">
    <?php foreach ($faqs as $faq): ?>
    <details><summary><?= e($faq['question']) ?><span class="plus">+</span></summary>
      <div class="faq__answer"><?= nl2br(e($faq['answer'])) ?></div></details>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
