<?php
$route = '/sitemap';
$page_seo = page_seo($route, 'Sitemap | ' . SITE_NAME, 'Browse all pages of the ' . SITE_NAME . ' website.');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Sitemap</span></nav>
  <h1>Sitemap</h1>
</div></section>
<section class="section"><div class="container"><div class="cards cards--2">
  <div class="card"><h3>Main Pages</h3><ul style="list-style:disc;margin-left:20px;display:grid;gap:6px;margin-top:10px">
    <?php foreach (['/'=>'Home','/about-us'=>'About Us','/doctors'=>'Our Doctors','/specialities'=>'Specialities','/treatments'=>'Treatments','/technology'=>'Technology','/blog'=>'Blog','/gallery'=>'Gallery','/reels'=>'Videos & Reels','/contact-us'=>'Contact Us','/book-appointment'=>'Book Appointment'] as $u=>$l): ?>
    <li><a href="<?= url(ltrim($u,'/')) ?>"><?= e($l) ?></a></li>
    <?php endforeach; ?>
  </ul></div>
  <div class="card"><h3>Patient Resources</h3><ul style="list-style:disc;margin-left:20px;display:grid;gap:6px;margin-top:10px">
    <?php foreach (['/patient-journey'=>'Patient Journey','/patient-safety'=>'Patient Safety','/insurance-payment'=>'Insurance & Payment','/international-patients'=>'International Patients','/patient-education'=>'Patient Education','/faqs'=>'FAQs','/awards'=>'Awards','/academics'=>'Academics','/community'=>'Community','/media-news'=>'Media & News'] as $u=>$l): ?>
    <li><a href="<?= url(ltrim($u,'/')) ?>"><?= e($l) ?></a></li>
    <?php endforeach; ?>
  </ul></div>
</div></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
