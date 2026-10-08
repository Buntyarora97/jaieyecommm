<?php
/** Audit & security logs viewer. */
if (!has_permission('settings.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }
$logs = db_all("SELECT l.*, a.name AS admin_name FROM admin_activity_logs l LEFT JOIN admins a ON a.id=l.admin_id ORDER BY l.id DESC LIMIT 200");
$secLogs = db_all("SELECT * FROM security_logs ORDER BY id DESC LIMIT 100");
?>
<div class="card">
  <h2>Admin Activity Log</h2>
  <table class="data">
    <thead><tr><th>Time</th><th>Admin</th><th>Action</th><th>Module</th><th>Details</th><th>IP</th></tr></thead>
    <tbody>
    <?php foreach ($logs as $l): ?>
      <tr>
        <td><?= e(format_date($l['created_at'], 'd M Y, h:i A')) ?></td>
        <td><?= e($l['admin_name'] ?? '—') ?></td>
        <td><span class="badge badge--blue"><?= e($l['action']) ?></span></td>
        <td><?= e($l['module'] ?? '') ?></td>
        <td><?= e(excerpt($l['details'] ?? '', 60)) ?></td>
        <td><?= e($l['ip_address'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$logs): ?><tr><td colspan="6" style="text-align:center;color:var(--grey);padding:20px">No activity logged yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<div class="card">
  <h2>Security Log</h2>
  <table class="data">
    <thead><tr><th>Time</th><th>Event</th><th>Details</th><th>IP</th></tr></thead>
    <tbody>
    <?php foreach ($secLogs as $s): ?>
      <tr>
        <td><?= e(format_date($s['created_at'], 'd M Y, h:i A')) ?></td>
        <td><span class="badge <?= str_contains($s['event'], 'failed') ? 'badge--red' : 'badge--green' ?>"><?= e($s['event']) ?></span></td>
        <td><?= e(excerpt($s['details'] ?? '', 60)) ?></td>
        <td><?= e($s['ip_address'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$secLogs): ?><tr><td colspan="4" style="text-align:center;color:var(--grey);padding:20px">No security events logged.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
