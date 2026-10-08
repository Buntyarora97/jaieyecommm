<?php
/** Global footer with CTA band, link columns (DB driven), contact info. */
$footer_cols = footer_columns();
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
        <img src="<?= asset('img/logo.svg') ?>" alt="<?= e(SITE_NAME) ?> logo" style="filter:brightness(0) invert(1)">
        <p><?= e(SITE_TAGLINE) ?>. Comprehensive eye care, specialist consultations and personalised treatment in <?= e(SITE_AREA) ?>.</p>
        <div class="footer-social">
          <a href="<?= e(SITE_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1s-3.6 0-4.9-.1c-3.3-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8C2.4 4 4 2.4 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2zm0 3.6a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.4-10.4a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9z"/></svg></a>
          <a href="<?= e(SITE_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2.1-.1-2.1 0-3.6 1.3-3.6 3.7V11H8.2v3h2.5v7h2.8z"/></svg></a>
          <a href="<?= e(SITE_YOUTUBE) ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8c1.6.4 7.8.4 7.8.4s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3L10 15z"/></svg></a>
        </div>
      </div>

      <?php foreach ($footer_cols as $group => $links): ?>
      <div class="footer-col">
        <h4><?= e($group) ?></h4>
        <ul>
          <?php foreach ($links as $link): ?>
          <li><a href="<?= url(ltrim($link['url'], '/')) ?>"><?= e($link['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>

      <div class="footer-col">
        <h4>Contact</h4>
        <address>
          <?= e(SITE_ADDRESS) ?><br><br>
          <a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a><br>
          <a href="tel:+919643536373"><?= e(SITE_PHONE_2) ?></a><br>
          <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>
        </address>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</span>
      <span><a href="<?= url('privacy-policy') ?>" style="color:#7FA894">Privacy Policy</a> &nbsp;·&nbsp; <a href="<?= url('sitemap') ?>" style="color:#7FA894">Sitemap</a></span>
    </div>
  </div>
</footer>

<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
