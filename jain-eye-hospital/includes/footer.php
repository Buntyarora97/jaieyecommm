<?php
/** Global footer with CTA band, editable quick links and published specialities. */
$footer_cols = footer_columns();
$quick_links = $footer_cols['Quick Links'] ?? [];
$patient_resource_links = array_merge($footer_cols['Patient Care'] ?? [], $footer_cols['Resources'] ?? []);
$footer_specialities = db_all("SELECT name, slug FROM specialities WHERE status='published' ORDER BY is_featured DESC, position LIMIT 8");
if (!$quick_links) {
    $quick_links = [
        ['label' => 'About Us', 'url' => '/about-us'],
        ['label' => 'Our Doctors', 'url' => '/doctors'],
        ['label' => 'Technology', 'url' => '/technology'],
        ['label' => 'Patient Journey', 'url' => '/patient-journey'],
        ['label' => 'Photo Gallery', 'url' => '/gallery'],
        ['label' => 'Contact Us', 'url' => '/contact-us'],
    ];
}
if (!$patient_resource_links) {
    foreach ($footer_specialities as $speciality) {
        $patient_resource_links[] = ['label' => $speciality['name'], 'url' => '/specialities/' . $speciality['slug']];
    }
    $patient_resource_links[] = ['label' => 'Book Appointment', 'url' => '/book-appointment'];
    $patient_resource_links[] = ['label' => 'Patient Safety', 'url' => '/patient-safety'];
    $patient_resource_links[] = ['label' => 'FAQs', 'url' => '/faqs'];
}
?>
</main>

<footer class="site-footer">
  <div class="footer-cta">
    <div class="container">
      <h2><?= e(setting('final_headline', 'Take the Next Step Towards Better Eye Care')) ?></h2>
      <div class="btn-row">
        <a class="btn btn--primary" href="<?= url('book-appointment') ?>">Book Appointment</a>
        <a class="btn btn--light" href="tel:+911143784377">Call <?= e(SITE_PHONE_1) ?></a>
      </div>
    </div>
  </div>

  <div class="footer-main">
    <div class="container footer-grid">
      <div class="footer-brand">
        <a class="footer-brand__logo" href="<?= url('/') ?>" aria-label="<?= e(SITE_NAME) ?> home">
          <img src="<?= asset('img/logo-official-transparent.png') ?>" alt="<?= e(SITE_NAME) ?> — <?= e(SITE_TAGLINE) ?>">
        </a>
        <p><?= e(SITE_TAGLINE) ?>. Specialist consultations, clear guidance and patient-centred eye care in <?= e(SITE_AREA) ?>.</p>
        <div class="footer-social">
          <a href="<?= e(SITE_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1s-3.6 0-4.9-.1c-3.3-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8C2.4 4 4 2.4 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2zm0 3.6a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.4-10.4a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9z"/></svg></a>
          <a href="<?= e(SITE_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2.1-.1-2.1 0-3.6 1.3-3.6 3.7V11H8.2v3h2.5v7h2.8z"/></svg></a>
          <a href="<?= e(SITE_YOUTUBE) ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8c1.6.4 7.8.4 7.8.4s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3L10 15z"/></svg></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <?php foreach ($quick_links as $link): ?>
          <li><a href="<?= url(ltrim($link['url'], '/')) ?>"><?= e($link['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Patient Care &amp; Resources</h4>
        <ul>
          <?php foreach ($patient_resource_links as $link): ?>
          <li><a href="<?= url(ltrim($link['url'], '/')) ?>"><?= e($link['label']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="<?= url('specialities') ?>">All specialities</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Contact</h4>
        <address>
          <?= e(SITE_ADDRESS) ?><br><br>
          <a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a><br>
          <a href="tel:+919643536373"><?= e(SITE_PHONE_2) ?></a><br>
          <a href="tel:+919643900900"><?= e(SITE_PHONE_3) ?></a><br>
          <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>
          <p><a href="<?= e(SITE_MAPS_URL) ?>" target="_blank" rel="noopener">Get directions</a></p>
        </address>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</span>
      <span>
        <a href="<?= url('privacy-policy') ?>" style="color:#7FA894">Privacy</a> &nbsp;·&nbsp;
        <a href="<?= url('terms-and-conditions') ?>" style="color:#7FA894">Terms</a> &nbsp;·&nbsp;
        <a href="<?= url('medical-disclaimer') ?>" style="color:#7FA894">Medical disclaimer</a> &nbsp;·&nbsp;
        <a href="<?= url('accessibility') ?>" style="color:#7FA894">Accessibility</a> &nbsp;·&nbsp;
        <a href="<?= url('sitemap') ?>" style="color:#7FA894">Sitemap</a>
      </span>
    </div>
  </div>
</footer>

<nav class="floating-actions" aria-label="Quick contact links">
  <a class="floating-actions__link floating-actions__link--social floating-actions__instagram"
     href="<?= e(SITE_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" aria-label="Follow Jain Eye Hospital on Instagram" title="Instagram">
    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.8" r="1.2" fill="currentColor"/></svg><span>Instagram</span>
  </a>
  <a class="floating-actions__link floating-actions__link--social floating-actions__facebook"
     href="<?= e(SITE_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="Follow Jain Eye Hospital on Facebook" title="Facebook">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.3 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.5-1.5h1.8V3.9c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3V10H7v3h2.9v8z"/></svg><span>Facebook</span>
  </a>
  <a class="floating-actions__link floating-actions__link--social floating-actions__youtube"
     href="<?= e(SITE_YOUTUBE) ?>" target="_blank" rel="noopener noreferrer" aria-label="Watch Jain Eye Hospital on YouTube" title="YouTube">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12a31 31 0 0 0 .5 4.8 3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8ZM9.5 15.4V8.6l6 3.4-6 3.4Z"/></svg><span>YouTube</span>
  </a>
  <a class="floating-actions__link floating-actions__whatsapp"
     href="https://wa.me/919643536373?text=Namaste%2C%20I%20would%20like%20to%20contact%20Jain%20Eye%20Hospital."
     target="_blank" rel="noopener noreferrer" aria-label="Message Jain Eye Hospital on WhatsApp" title="WhatsApp">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a9.8 9.8 0 0 0-8.4 14.8L2.3 22l5.4-1.4A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4-1.1l-.3-.2-3.2.8.9-3.1-.2-.4A8 8 0 1 1 12 20Zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.4.1-.5l.4-.4.3-.5c.1-.2 0-.3 0-.5-.1-.1-.5-1.3-.7-1.7-.2-.5-.4-.4-.5-.4h-.5c-.2 0-.5.1-.7.3-.2.2-.9.9-.9 2.1 0 1.2.9 2.4 1 2.6.1.2 1.8 2.8 4.4 3.8.6.3 1.1.4 1.5.5.6.2 1.1.2 1.5.1.5-.1 1.4-.6 1.6-1.2.2-.6.2-1.1.1-1.2-.1-.2-.3-.3-.5-.4Z"/></svg><span>WhatsApp</span>
  </a>
  <a class="floating-actions__link floating-actions__enquiry" href="<?= url('contact-us') ?>" data-open-enquiry aria-haspopup="dialog" aria-label="Open the enquiry form" title="Send an enquiry">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z"/></svg><span>Enquiry</span>
  </a>
  <a class="floating-actions__link floating-actions__call" href="tel:+911143784377" aria-label="Call Jain Eye Hospital at <?= e(SITE_PHONE_1) ?>" title="Call">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg><span>Call</span>
  </a>
  <a class="floating-actions__link floating-actions__maps" href="<?= e(SITE_MAPS_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Find Jain Eye Hospital on Google Maps" title="Google Maps">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg><span>Directions</span>
  </a>
</nav>

<dialog class="enquiry-dialog" id="enquiryDialog" aria-labelledby="enquiryTitle">
  <div class="enquiry-dialog__inner">
    <button class="enquiry-dialog__close" type="button" data-close-enquiry aria-label="Close enquiry form">&times;</button>
    <span class="eyebrow">We’re here to help</span>
    <h2 id="enquiryTitle">Send an enquiry</h2>
    <p class="enquiry-dialog__intro">Share your contact details and a brief message. Our team can follow up about your enquiry.</p>
    <form class="enquiry-form" method="post" action="<?= url('contact-us') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="subject" value="Website Enquiry">
      <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
      <label>Name <span class="req">*</span><input name="name" type="text" required maxlength="120" autocomplete="name"></label>
      <label>Email <span class="req">*</span><input name="email" type="email" required maxlength="120" autocomplete="email"></label>
      <label>Phone <span class="req">*</span><input name="phone" type="tel" required maxlength="15" autocomplete="tel" inputmode="tel"></label>
      <label>How can we help? <span class="req">*</span><textarea name="message" required minlength="10" maxlength="5000" rows="4"></textarea></label>
      <label class="enquiry-form__consent"><input type="checkbox" name="consent" value="1" required><span>I consent to being contacted about this enquiry and have read the <a href="<?= url('privacy-policy') ?>">Privacy Policy</a>.</span></label>
      <p class="enquiry-form__note">Please do not include medical records, payment details or sensitive health information.</p>
      <button class="btn btn--primary" type="submit">Send Enquiry</button>
    </form>
  </div>
</dialog>

<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
