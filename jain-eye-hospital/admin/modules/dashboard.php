<?php
/** Admin dashboard with live counts and recent activity. */
$stats = [
    ['New Appointments', (int)db_val("SELECT COUNT(*) FROM appointments WHERE status='New'"), true],
    ['Total Appointments', (int)db_val("SELECT COUNT(*) FROM appointments"), false],
    ['New Enquiries', (int)db_val("SELECT COUNT(*) FROM contact_enquiries WHERE status='New'"), true],
    ['Doctors', (int)db_val("SELECT COUNT(*) FROM doctors WHERE status='published'"), false],
    ['Specialities', (int)db_val("SELECT COUNT(*) FROM specialities WHERE status='published'"), false],
    ['Blog Posts', (int)db_val("SELECT COUNT(*) FROM blog_posts WHERE status='published'"), false],
];
$recentAppts = db_all("SELECT a.*, d.name AS doctor_name FROM appointments a LEFT JOIN doctors d ON d.id=a.doctor_id ORDER BY a.created_at DESC LIMIT 8");
$recentEnq = db_all("SELECT * FROM contact_enquiries ORDER BY created_at DESC LIMIT 6");
?>
<div class="stats">
  <?php foreach ($stats as [$label, $num, $orange]): ?>
  <div class="stat<?= $orange ? ' stat--orange' : '' ?>">
    <div class="stat__num"><?= $num ?></div>
    <div class="stat__label"><?= e($label) ?></div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card">
  <h2>Recent Appointment Requests</h2>
  <table class="data">
    <thead><tr><th>Reference</th><th>Patient</th><th>Mobile</th><th>Doctor</th><th>Preferred</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($recentAppts as $a): ?>
      <tr>
        <td><strong><?= e($a['reference']) ?></strong></td>
        <td><?= e($a['name']) ?></td>
        <td><?= e($a['mobile']) ?></td>
        <td><?= e($a['doctor_name'] ?? '—') ?></td>
        <td><?= e(format_date($a['preferred_date'])) ?> <?= e($a['preferred_time'] ?? '') ?></td>
        <td><span class="badge <?= $a['status']==='New' ? 'badge--orange' : ($a['status']==='Confirmed' ? 'badge--green' : 'badge--grey') ?>"><?= e($a['status']) ?></span></td>
        <td><a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=appointments&action=view&id=' . (int)$a['id']) ?>">View</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$recentAppts): ?><tr><td colspan="7" style="text-align:center;color:var(--grey);padding:24px">No appointment requests yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Recent Contact Enquiries</h2>
  <table class="data">
    <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Status</th><th>Received</th></tr></thead>
    <tbody>
    <?php foreach ($recentEnq as $en): ?>
      <tr>
        <td><?= e($en['name']) ?></td>
        <td><?= e($en['email']) ?></td>
        <td><?= e($en['subject']) ?></td>
        <td><span class="badge <?= $en['status']==='New' ? 'badge--orange' : 'badge--grey' ?>"><?= e($en['status']) ?></span></td>
        <td><?= e(format_date($en['created_at'], 'd M Y, h:i A')) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$recentEnq): ?><tr><td colspan="5" style="text-align:center;color:var(--grey);padding:24px">No enquiries yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
