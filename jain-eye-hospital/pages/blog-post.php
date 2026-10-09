<?php
/** Blog article detail. */
$post = db_row("SELECT p.*, c.name AS cat_name FROM blog_posts p LEFT JOIN blog_categories c ON c.id=p.category_id WHERE p.slug=? AND p.status='published'", [$slug]);
if (!$post) { http_response_code(404); require __DIR__ . '/404.php'; return; }

$route = '/blog/' . $post['slug'];
$postDescription = excerpt($post['excerpt'] ?: $post['content'] ?: 'Eye-health information from ' . SITE_NAME . '.', 155);
$postSeoKeywords = array_filter([
    $post['title'],
    !empty($post['cat_name']) ? $post['cat_name'] . ' eye health' : '',
    'eye health information',
    'eye care Delhi',
    SITE_NAME,
]);
$page_seo = page_seo(
    $route,
    excerpt($post['title'], 48) . ' | Jain Eye Hospital',
    $postDescription,
    implode(', ', array_unique($postSeoKeywords))
);
$page_seo['og_type'] = 'article';
if (empty($page_seo['og_image']) && !empty($post['featured_image'])) {
    $page_seo['og_image'] = str_starts_with($post['featured_image'], 'assets/')
        ? url($post['featured_image'])
        : uploads_url($post['featured_image']);
}
$related = db_all("SELECT * FROM blog_posts WHERE status='published' AND id<>? ORDER BY published_at DESC LIMIT 3", [$post['id']]);

$extra_head = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org', '@type' => 'BlogPosting',
    'headline' => $post['title'],
    'datePublished' => $post['published_at'],
    'dateModified' => $post['updated_at'],
    'publisher' => ['@type' => 'Organization', 'name' => SITE_NAME],
], JSON_UNESCAPED_SLASHES) . '</script>';

require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><a href="<?= url('blog') ?>">Blog</a><span class="sep">/</span><span><?= e($post['title']) ?></span></nav>
  <h1 style="max-width:820px"><?= e($post['title']) ?></h1>
</div></section>
<section class="section"><div class="container">
  <article class="article">
    <div class="article-meta">
      <?php if ($post['cat_name']): ?><span class="post-card__cat"><?= e($post['cat_name']) ?></span><?php endif; ?>
      <span>Published: <?= format_date($post['published_at']) ?></span>
      <?php if ($post['updated_at'] && $post['updated_at'] !== $post['published_at']): ?><span>Updated: <?= format_date($post['updated_at']) ?></span><?php endif; ?>
      <?php if ($post['medical_reviewer']): ?><span>Medically reviewed by <?= e($post['medical_reviewer']) ?></span><?php endif; ?>
    </div>
    <?= $post['content'] /* trusted admin-authored HTML */ ?>
    <p style="font-size:.85rem;color:var(--grey);border-top:1px solid var(--border);padding-top:18px;margin-top:30px">
      Disclaimer: This article is for general education only and does not replace a personal consultation with an eye specialist.
    </p>
    <div style="margin-top:34px"><a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book an Eye Consultation</a></div>
  </article>
  <?php if ($related): ?>
  <div style="margin-top:80px">
    <div class="section-head"><span class="eyebrow">Keep Reading</span><h2>Related Articles</h2></div>
    <div class="blog-grid">
      <?php foreach ($related as $rel): ?>
      <article class="post-card">
        <div class="post-card__img"><?= image_or_placeholder($rel['featured_image'], '', $rel['title']) ?></div>
        <div class="post-card__body">
          <h3><a href="<?= url('blog/' . $rel['slug']) ?>"><?= e($rel['title']) ?></a></h3>
          <p><?= e($rel['excerpt'] ?: excerpt($rel['content'] ?? '', 110)) ?></p>
          <a class="link-arrow" href="<?= url('blog/' . $rel['slug']) ?>">Read article</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
