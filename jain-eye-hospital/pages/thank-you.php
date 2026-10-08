<?php
/** One-time confirmation page displayed after a successfully saved form. */
$confirmation = $_SESSION['form_success'] ?? null;
unset($_SESSION['form_success']);
if (!is_array($confirmation) || !in_array($confirmation['kind'] ?? '', ['appointment', 'contact'], true)) {
    redirect('/');
}
$isAppointment = $confirmation['kind'] === 'appointment';
$route = '/thank-you';
$page_seo = page_seo($route, 'Thank You | ' . SITE_NAME, 'Your message has been received by ' . SITE_NAME . '.');
$page_seo['robots'] = 'noindex,nofollow';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Thank You</span></nav>
  <h1><?= $isAppointment ? 'Appointment request received' : 'Thank you for contacting us' ?></h1>
  <p><?= $isAppointment ? 'Your request is not a confirmed appointment. Our team will contact you to confirm availability.' : 'Your message has been received. The team can reply using the contact details you provided.' ?></p>
</div></section>
<section class="section section--pale"><div class="container">
  <div class="form-card" style="max-width:680px;margin:0 auto;text-align:center">
    <div class="card__icon" style="margin:0 auto 18px" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1.2 14.5-4-4 1.4-1.4 2.6 2.6 6-6 1.4 1.4-7.4 7.4z"/></svg></div>
    <h2><?= $isAppointment ? 'Request saved' : 'Message saved' ?></h2>
    <?php if ($isAppointment && !empty($confirmation['reference'])): ?>
    <p style="color:var(--grey);margin:14px 0 6px">Keep this reference for your enquiry:</p>
    <p style="font-family:var(--font-head);font-weight:800;color:var(--green-800);font-size:1.3rem"><?= e((string)$confirmation['reference']) ?></p>
    <?php endif; ?>
    <p style="color:var(--grey);margin:14px 0 24px;font-size:.92rem">For urgent eye symptoms, do not wait for a website reply; seek appropriate medical care.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--dark" href="<?= url('/') ?>">Back to Home</a>
      <a class="btn btn--outline" href="<?= url('contact-us') ?>">Contact the Hospital</a>
    </div>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
