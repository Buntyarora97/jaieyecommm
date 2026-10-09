<?php
/** Dynamic XML sitemap generated from published content. */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

$urls = [
    ['/', '1.0', 'weekly'],
    ['/about-us', '0.9', 'monthly'],
    ['/doctors', '0.9', 'monthly'],
    ['/specialities', '0.9', 'monthly'],
    ['/treatments', '0.8', 'monthly'],
    ['/technology', '0.8', 'monthly'],
    ['/patient-journey', '0.6', 'yearly'],
    ['/patient-safety', '0.6', 'yearly'],
    ['/insurance-payment', '0.6', 'yearly'],
    ['/international-patients', '0.6', 'yearly'],
    ['/patient-education', '0.6', 'monthly'],
    ['/faqs', '0.6', 'monthly'],
    ['/blog', '0.8', 'weekly'],
    ['/gallery', '0.5', 'monthly'],
    ['/reels', '0.5', 'monthly'],
    ['/awards', '0.5', 'yearly'],
    ['/academics', '0.5', 'yearly'],
    ['/community', '0.5', 'yearly'],
    ['/media-news', '0.5', 'monthly'],
    ['/contact-us', '0.8', 'yearly'],
    ['/book-appointment', '0.9', 'yearly'],
    ['/privacy-policy', '0.3', 'yearly'],
    ['/terms-and-conditions', '0.3', 'yearly'],
    ['/medical-disclaimer', '0.3', 'yearly'],
    ['/accessibility', '0.3', 'yearly'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as [$loc, $priority, $freq]): ?>
  <url><loc><?= SITE_URL . $loc ?></loc><changefreq><?= $freq ?></changefreq><priority><?= $priority ?></priority></url>
<?php endforeach; ?>
<?php foreach (db_all("SELECT slug, updated_at FROM doctors WHERE status='published'") as $d): ?>
  <url><loc><?= SITE_URL ?>/doctors/<?= htmlspecialchars($d['slug']) ?></loc><lastmod><?= date('Y-m-d', strtotime($d['updated_at'])) ?></lastmod><priority>0.8</priority></url>
<?php endforeach; ?>
<?php foreach (db_all("SELECT slug, updated_at FROM specialities WHERE status='published'") as $s): ?>
  <url><loc><?= SITE_URL ?>/specialities/<?= htmlspecialchars($s['slug']) ?></loc><lastmod><?= date('Y-m-d', strtotime($s['updated_at'])) ?></lastmod><priority>0.8</priority></url>
<?php endforeach; ?>
<?php foreach (db_all("SELECT slug, updated_at FROM treatments WHERE status='published'") as $t): ?>
  <url><loc><?= SITE_URL ?>/treatments/<?= htmlspecialchars($t['slug']) ?></loc><lastmod><?= date('Y-m-d', strtotime($t['updated_at'])) ?></lastmod><priority>0.7</priority></url>
<?php endforeach; ?>
<?php foreach (db_all("SELECT slug, updated_at FROM blog_posts WHERE status='published'") as $b): ?>
  <url><loc><?= SITE_URL ?>/blog/<?= htmlspecialchars($b['slug']) ?></loc><lastmod><?= date('Y-m-d', strtotime($b['updated_at'])) ?></lastmod><priority>0.7</priority></url>
<?php endforeach; ?>
</urlset>
