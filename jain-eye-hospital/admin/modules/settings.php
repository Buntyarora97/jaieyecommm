<?php
/** Site settings key-value editor. */
if (!has_permission('settings.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($_POST['settings'] ?? [] as $key => $value) {
        db_exec("INSERT INTO site_settings (setting_key, setting_value) VALUES (?,?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
            [trim((string)$key), trim((string)$value)]);
    }
    log_activity('update', 'settings', 'Site settings saved');
    flash('success', 'Settings saved.');
    redirect('admin/?module=settings');
}
$settings = db_all("SELECT * FROM site_settings ORDER BY setting_key");
?>
<div class="card">
  <h2>Site Settings</h2>
  <form method="post" action="<?= url('admin/?module=settings') ?>">
    <?= csrf_field() ?>
    <div class="form-grid">
      <?php foreach ($settings as $s): ?>
      <div class="field <?= strlen((string)$s['setting_value']) > 80 ? 'full' : '' ?>">
        <label><?= e($s['setting_key']) ?></label>
        <?php if (strlen((string)$s['setting_value']) > 80): ?>
        <textarea name="settings[<?= e($s['setting_key']) ?>]"><?= e($s['setting_value']) ?></textarea>
        <?php else: ?>
        <input name="settings[<?= e($s['setting_key']) ?>]" value="<?= e($s['setting_value']) ?>">
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-row" style="margin-top:18px"><button class="btn btn--primary" type="submit">Save All Settings</button></div>
  </form>
</div>
<div class="card">
  <h2>Add New Setting</h2>
  <form method="post" action="<?= url('admin/?module=settings') ?>" class="btn-row">
    <?= csrf_field() ?>
    <input name="new_key" placeholder="setting_key" style="padding:10px 14px;border:1px solid var(--border);border-radius:10px" onkeydown="event.key==='Enter'&&event.preventDefault()">
  </form>
  <div class="hint" style="margin-top:8px">To add a new key, include it via your developer or create it in the database. Keys shown above can be edited freely.</div>
</div>
