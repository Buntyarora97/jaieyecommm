<?php
/** Contact page with enquiry form. */
$route = '/contact-us';
$page_seo = page_seo($route, 'Contact Us | ' . SITE_NAME, 'Contact ' . SITE_NAME . ', AG-152 Shalimar Bagh, Delhi 110088. Call ' . SITE_PHONE_1 . '.');
$errors = [];
$old = ['name'=>'','email'=>'','phone'=>'','subject'=>'','message'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (!empty($_POST['website'])) { exit('Thank you.'); }
    $now = time();
    if (!empty($_SESSION['contact_last']) && ($now - $_SESSION['contact_last']) < 60) {
        $errors[] = 'Please wait a minute before sending another message.';
    }
    foreach ($old as $k => $v) { $old[$k] = trim((string)($_POST[$k] ?? '')); }

    if ($old['name'] === '' || mb_strlen($old['name']) > 160) { $errors[] = 'Please enter your name (up to 160 characters).'; }
    if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 160) { $errors[] = 'Please enter a valid email address.'; }
    if ($old['phone'] !== '' && (!preg_match('/^[0-9+\-\s]{8,15}$/', $old['phone']) || mb_strlen($old['phone']) > 30)) { $errors[] = 'Please enter a valid phone number.'; }
    if (mb_strlen($old['subject']) > 200) { $errors[] = 'Please keep the subject under 200 characters.'; }
    if (mb_strlen($old['message']) < 10 || mb_strlen($old['message']) > 5000) { $errors[] = 'Please write a message between 10 and 5,000 characters.'; }
    if (empty($_POST['consent'])) { $errors[] = 'Please confirm your consent to be contacted.'; }

    if (!$errors) {
        db_exec("INSERT INTO contact_enquiries (name, email, phone, subject, message, consent) VALUES (?,?,?,?,?,1)",
            [$old['name'], $old['email'], $old['phone'] ?: null, $old['subject'] ?: 'Website Enquiry', $old['message']]);
        notify_staff('New Website Enquiry - ' . ($old['subject'] ?: 'General'),
            "Name: {$old['name']}\nEmail: {$old['email']}\nPhone: {$old['phone']}\n\n{$old['message']}\n");
        $_SESSION['contact_last'] = $now;
        $_SESSION['form_success'] = ['kind' => 'contact'];
        redirect('thank-you', 303);
    }
}
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Contact Us</span></nav>
  <h1>Contact Us</h1><p>We are here to help with appointments, queries and directions.</p>
</div></section>
<section class="section section--pale"><div class="container">
  <div class="location-grid" style="align-items:start">
    <div>
      <div class="contact-card" style="margin-bottom:24px">
        <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg></span>
          <div><strong>Address</strong><span><?= e(SITE_ADDRESS) ?></span></div></div>
        <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg></span>
          <div><strong>Phone</strong><a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a><br><a href="tel:+919643536373"><?= e(SITE_PHONE_2) ?></a><br><a href="tel:+919643900900"><?= e(SITE_PHONE_3) ?></a></div></div>
        <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
          <div><strong>Email</strong><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></div></div>
        <div class="btn-row" style="margin-top:18px">
          <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
          <a class="btn btn--outline" href="<?= e(SITE_MAPS_URL) ?>" target="_blank" rel="noopener">Get Directions</a>
        </div>
      </div>
      <div class="map-embed"><iframe src="<?= e(SITE_MAPS_EMBED) ?>" loading="lazy" title="Map - <?= e(SITE_NAME) ?>" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
    </div>
    <form class="form-card" method="post" action="<?= url('contact-us') ?>">
      <h2 style="margin-bottom:20px">Send Us a Message</h2>
      <?php if ($errors): ?>
      <div class="alert alert--error"><ul style="margin-left:18px;list-style:disc"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
      <?php endif; ?>
      <?= csrf_field() ?>
      <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
      <div class="form-grid">
        <div class="field"><label for="cname">Name <span class="req">*</span></label>
          <input id="cname" name="name" type="text" required maxlength="120" value="<?= e($old['name']) ?>"></div>
        <div class="field"><label for="cemail">Email <span class="req">*</span></label>
          <input id="cemail" name="email" type="email" required maxlength="120" value="<?= e($old['email']) ?>"></div>
        <div class="field"><label for="cphone">Phone</label>
          <input id="cphone" name="phone" type="tel" maxlength="15" value="<?= e($old['phone']) ?>"></div>
        <div class="field"><label for="csubject">Subject</label>
          <input id="csubject" name="subject" type="text" maxlength="160" value="<?= e($old['subject']) ?>"></div>
        <div class="field full"><label for="cmessage">Message <span class="req">*</span></label>
          <textarea id="cmessage" name="message" required minlength="10" maxlength="5000"><?= e($old['message']) ?></textarea></div>
        <div class="field field--consent full">
          <input type="checkbox" id="contact-consent" name="consent" value="1" required>
          <label for="contact-consent">I consent to being contacted by <?= e(SITE_NAME) ?> about this enquiry. Please see the <a href="<?= url('privacy-policy') ?>">Privacy Policy</a>. <span class="req">*</span></label>
        </div>
        <div class="full"><button class="btn btn--primary" type="submit">Send Message</button></div>
      </div>
    </form>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
