<?php
/** Blog listing with categories, search and pagination. */
$route = '/blog';
$page_seo = page_seo($route, 'Eye Care Blog | ' . SITE_NAME, 'Eye health articles and hospital updates from ' . SITE_NAME . ', Delhi.');
$cat = (int)($_GET['cat'] ?? 0);
$q = trim((string)($_GET['q'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 9;
$where = "p.status='published'";
$params = [];
if ($cat) { $where .= " AND p.category_id=?"; $params[] = $cat; }
if ($q !== '') { $where .= " AND (p.title LIKE ? OR p.content LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
$total = (int)db_val("SELECT COUNT(*) FROM blog_posts p WHERE $where", $params);
$pages = max(1, (int)ceil($total / $per));
$page = min($page, $pages);
$posts = db_all("SELECT p.*, c.name AS cat_name FROM blog_posts p LEFT JOIN blog_categories c ON c.id=p.category_id WHERE $where ORDER BY p.published_at DESC LIMIT $per OFFSET " . (($page-1)*$per), $params);
$cats = db_all("SELECT * FROM blog_categories ORDER BY position");
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Blog</span></nav>
  <h1>Eye Care Blog</h1><p>Articles and updates from our specialists.</p>
</div></section>
<section class="section"><div class="container">
  <form class="filter-bar" method="get" action="<?= url('blog') ?>">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search articles…" aria-label="Search articles">
    <button class="btn btn--dark" type="submit">Search</button>
  </form>
  <?php if ($cats): ?>
  <div class="pill-nav">
    <a href="<?= url('blog') ?>" class="<?= $cat ? '' : 'is-active' ?>">All</a>
    <?php foreach ($cats as $c): ?>
    <a href="<?= url('blog') ?>?cat=<?= (int)$c['id'] ?>" class="<?= $cat === (int)$c['id'] ? 'is-active' : '' ?>"><?= e($c['name']) ?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <?php if ($posts): ?>
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
        <p><?= e($post['excerpt'] ?: excerpt($post['content'] ?? '', 140)) ?></p>
        <a class="link-arrow" href="<?= url('blog/' . $post['slug']) ?>">Read article</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php if ($pages > 1): ?>
  <nav class="pagination" aria-label="Pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <?php if ($i === $page): ?><span class="is-current"><?= $i ?></span>
      <?php else: ?><a href="<?= url('blog') ?>?page=<?= $i ?><?= $cat ? "&cat=$cat" : '' ?><?= $q ? '&q=' . urlencode($q) : '' ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
  </nav>
  <?php endif; ?>
  <?php else: ?>
  <div class="empty-state"><strong>No articles found</strong>New articles are being prepared. Please check back soon.</div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
