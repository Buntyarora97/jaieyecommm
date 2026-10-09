<?php
/** Videos & Reels page - 9:16 cards with native playback. */
$route = '/reels';
$page_seo = page_seo($route, 'Videos & Reels | ' . SITE_NAME, 'Educational eye care videos from the doctors at ' . SITE_NAME . '.');
$reels = db_all(
    "SELECT r.*, d.name AS doctor_name, d.photo AS doctor_photo FROM reels r LEFT JOIN doctors d ON d.id=r.doctor_id
     WHERE r.status='published'
       AND ((r.video_path IS NOT NULL AND r.video_path <> '') OR (r.video_url IS NOT NULL AND r.video_url <> ''))
     ORDER BY r.position"
);
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Videos &amp; Reels</span></nav>
  <h1>Videos &amp; Reels</h1><p>Expert eye care insights, one reel at a time.</p>
</div></section>
<section class="section"><div class="container">
  <?php if ($reels): ?>
  <div class="reel-grid">
    <?php foreach ($reels as $reel): ?>
    <div class="reel-card<?= empty($reel['video_path']) ? ' reel-card--external' : '' ?>" data-phone>
      <?php if (!empty($reel['video_path'])): ?>
      <video src="<?= e(uploads_url($reel['video_path'])) ?>" <?= $reel['thumbnail'] ? 'poster="' . e(uploads_url($reel['thumbnail'])) . '"' : '' ?> preload="none" muted playsinline loop></video>
      <div class="reel-card__play"><button class="phone__btn" data-play aria-label="Play <?= e($reel['title']) ?>"><span>▶</span></button></div>
      <?php elseif ($externalReelUrl = safe_https_url($reel['video_url'] ?? null)): ?>
      <a class="reel-card__external" href="<?= e($externalReelUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Watch <?= e($reel['title']) ?> on the original platform">
        <?= image_or_placeholder($reel['thumbnail'] ?: $reel['doctor_photo'], '', $reel['thumbnail'] ? 'Thumbnail for ' . $reel['title'] : 'Portrait of ' . ($reel['doctor_name'] ?: 'the featured doctor')) ?>
        <span aria-hidden="true">↗</span>
      </a>
      <?php else: ?>
      <img src="<?= asset('img/hospital-pic-15.webp') ?>" alt="<?= e($reel['title'] . ' — a consultation at ' . SITE_NAME) ?>" loading="lazy" decoding="async">
      <?php endif; ?>
      <div class="reel-card__meta">
        <?= e($reel['title']) ?>
        <?php if ($reel['doctor_name']): ?><br><small style="opacity:.8"><?= e($reel['doctor_name']) ?></small><?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><strong>Videos coming soon</strong>Educational reels from our doctors will appear here. Follow us on
    <a href="<?= e(SITE_INSTAGRAM) ?>" target="_blank" rel="noopener">Instagram</a> and
    <a href="<?= e(SITE_YOUTUBE) ?>" target="_blank" rel="noopener">YouTube</a> in the meantime.</div>
  <?php endif; ?>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
