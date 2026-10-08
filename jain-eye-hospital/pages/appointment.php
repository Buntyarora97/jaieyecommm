<?php
/** Appointment request workflow: validate -> save -> reference -> notify -> confirm. */
$route = '/book-appointment';
$page_seo = page_seo($route, 'Book an Appointment | ' . SITE_NAME, 'Request an appointment at ' . SITE_NAME . ', Shalimar Bagh, Delhi.');
$doctors = db_all("SELECT id, name FROM doctors WHERE status='published' ORDER BY position");
$services = db_all("SELECT id, name FROM specialities WHERE status='published' ORDER BY position");
$errors = [];
$old = ['name'=>'','mobile'=>'','email'=>'','doctor_id'=>'','speciality_id'=>'','preferred_date'=>'','preferred_time'=>'','message'=>''];
$timeWindows = ['Morning', 'Afternoon', 'Evening', 'Flexible'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    // Honeypot + simple per-session rate limit
    if (!empty($_POST['website'])) { exit('Thank you.'); }
    $now = time();
    if (!empty($_SESSION['appt_last']) && ($now - $_SESSION['appt_last']) < 60) {
        $errors[] = 'Please wait a minute before submitting another request.';
    }

    foreach ($old as $k => $v) { $old[$k] = trim((string)($_POST[$k] ?? '')); }

    if ($old['name'] === '' || mb_strlen($old['name']) < 2 || mb_strlen($old['name']) > 160) { $errors[] = 'Please enter a valid full name.'; }
    if (!preg_match('/^[0-9+\-\s]{8,15}$/', $old['mobile'])) { $errors[] = 'Please enter a valid mobile number.'; }
    if ($old['email'] !== '' && (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 160)) { $errors[] = 'Please enter a valid email address.'; }
    if ($old['preferred_date'] !== '') {
        $preferredDate = DateTimeImmutable::createFromFormat('!Y-m-d', $old['preferred_date']);
        if (!$preferredDate || $preferredDate->format('Y-m-d') !== $old['preferred_date'] || $preferredDate < new DateTimeImmutable('today')) {
            $errors[] = 'Please choose a valid date that is today or later.';
        }
    }
    if ($old['preferred_time'] !== '' && !in_array($old['preferred_time'], $timeWindows, true)) { $errors[] = 'Please choose a valid time window.'; }
    if ($old['doctor_id'] !== '' && !in_array((int)$old['doctor_id'], array_column($doctors, 'id'), true)) { $errors[] = 'Please choose a listed doctor or select no preference.'; }
    if ($old['speciality_id'] !== '' && !in_array((int)$old['speciality_id'], array_column($services, 'id'), true)) { $errors[] = 'Please choose a listed speciality or select no preference.'; }
    if (mb_strlen($old['message']) > 1000) { $errors[] = 'Please keep your message under 1,000 characters.'; }
    if (empty($_POST['consent'])) { $errors[] = 'Please confirm your consent to be contacted.'; }

    if (!$errors) {
        $reference = generate_reference();
        db_exec(
            "INSERT INTO appointments (reference, name, mobile, email, doctor_id, speciality_id, preferred_date, preferred_time, message, consent, status)
             VALUES (?,?,?,?,?,?,?,?,?,1,'New')",
            [
                $reference, $old['name'], $old['mobile'], $old['email'] ?: null,
                $old['doctor_id'] ? (int)$old['doctor_id'] : null,
                $old['speciality_id'] ? (int)$old['speciality_id'] : null,
                $old['preferred_date'] ?: null, $old['preferred_time'] ?: null,
                $old['message'] ?: null,
            ]
        );
        $apptId = (int)db()->lastInsertId();
        db_exec("INSERT INTO appointment_status_history (appointment_id, old_status, new_status, note) VALUES (?,NULL,'New','Request submitted online')", [$apptId]);

        notify_staff('New Appointment Request - ' . $reference,
            "Reference: $reference\nName: {$old['name']}\nMobile: {$old['mobile']}\nEmail: {$old['email']}\nDate: {$old['preferred_date']} {$old['preferred_time']}\n");

        $_SESSION['appt_last'] = $now;
        $_SESSION['form_success'] = ['kind' => 'appointment', 'reference' => $reference];
        redirect('thank-you', 303);
    }
}
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span class="sep">/</span><span>Book Appointment</span></nav>
  <h1>Book an Appointment</h1>
  <p>Request a consultation — our team will call to confirm your slot.</p>
</div></section>
<section class="section section--pale"><div class="container">
  <div class="location-grid" style="align-items:start">
    <form class="form-card" method="post" action="<?= url('book-appointment') ?>">
      <h2 style="margin-bottom:20px">Request an Appointment</h2>
      <?php if ($errors): ?>
      <div class="alert alert--error"><strong>Please correct the following:</strong><ul style="margin:8px 0 0 18px;list-style:disc"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
      <?php endif; ?>
      <?= csrf_field() ?>
      <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
      <div class="form-grid">
        <div class="field"><label for="name">Full Name <span class="req">*</span></label>
          <input id="name" name="name" type="text" required maxlength="120" value="<?= e($old['name']) ?>"></div>
        <div class="field"><label for="mobile">Mobile Number <span class="req">*</span></label>
          <input id="mobile" name="mobile" type="tel" required maxlength="15" value="<?= e($old['mobile']) ?>" placeholder="10-digit mobile number"></div>
        <div class="field full"><label for="email">Email</label>
          <input id="email" name="email" type="email" maxlength="120" value="<?= e($old['email']) ?>"></div>
        <div class="field"><label for="doctor_id">Preferred Doctor</label>
          <select id="doctor_id" name="doctor_id">
            <option value="">No preference</option>
            <?php foreach ($doctors as $d): ?>
            <option value="<?= (int)$d['id'] ?>" <?= (string)$d['id'] === $old['doctor_id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="field"><label for="speciality_id">Service / Speciality</label>
          <select id="speciality_id" name="speciality_id">
            <option value="">General eye check-up</option>
            <?php foreach ($services as $s): ?>
            <option value="<?= (int)$s['id'] ?>" <?= (string)$s['id'] === $old['speciality_id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="field"><label for="preferred_date">Preferred Date</label>
          <input id="preferred_date" name="preferred_date" type="date" min="<?= date('Y-m-d') ?>" value="<?= e($old['preferred_date']) ?>"></div>
        <div class="field"><label for="preferred_time">Preferred Time Window</label>
          <select id="preferred_time" name="preferred_time">
            <option value="">Any time</option>
            <?php foreach ($timeWindows as $slot): ?>
            <option <?= $old['preferred_time'] === $slot ? 'selected' : '' ?>><?= e($slot) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="field full"><label for="message">Message (optional)</label>
          <textarea id="message" name="message" maxlength="1000" placeholder="Briefly describe your concern"><?= e($old['message']) ?></textarea></div>
        <div class="field field--consent full">
          <input type="checkbox" id="consent" name="consent" value="1" required>
          <label for="consent">I consent to being contacted by <?= e(SITE_NAME) ?> regarding this appointment request. Please see the <a href="<?= url('privacy-policy') ?>">Privacy Policy</a>. <span class="req">*</span></label>
        </div>
        <div class="full"><button class="btn btn--primary" type="submit">Submit Request</button></div>
      </div>
    </form>
    <div class="contact-card">
      <h3 style="margin-bottom:8px">Prefer to Call?</h3>
      <p style="color:var(--grey);font-size:.92rem;margin-bottom:14px">Speak directly with our front desk to schedule your visit.</p>
      <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg></span>
        <div><strong>Phone</strong><a href="tel:+911143784377"><?= e(SITE_PHONE_1) ?></a><br><a href="tel:+919643536373"><?= e(SITE_PHONE_2) ?></a><br><a href="tel:+919643900900"><?= e(SITE_PHONE_3) ?></a></div></div>
      <div class="contact-row"><span class="contact-row__icon"><svg viewBox="0 0 24 24"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg></span>
        <div><strong>Address</strong><span><?= e(SITE_ADDRESS) ?></span></div></div>
      <p style="font-size:.82rem;color:var(--grey);margin-top:16px">Online submissions are requests, not confirmed appointments. Confirmation is given by our team after reviewing availability.</p>
    </div>
  </div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
