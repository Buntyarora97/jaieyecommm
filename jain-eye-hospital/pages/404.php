<?php
$page_seo = ['title'=>'Page Not Found | ' . SITE_NAME,'description'=>'The page you are looking for could not be found.','canonical'=>SITE_URL . '/404','og_image'=>'','robots'=>'noindex,follow'];
require __DIR__ . '/../includes/header.php';
?>
<section class="section"><div class="container" style="text-align:center;padding-block:80px">
  <span class="eyebrow" style="justify-content:center">Error 404</span>
  <h1 style="margin:16px 0">Page Not Found</h1>
  <p style="color:var(--grey);max-width:480px;margin:0 auto 30px">The page you are looking for may have been moved or no longer exists. Let us help you find your way back.</p>
  <div class="btn-row" style="justify-content:center">
    <a class="btn btn--primary" href="<?= url('/') ?>">Go to Homepage</a>
    <a class="btn btn--outline" href="<?= url('book-appointment') ?>">Book Appointment</a>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
