<?php
/** Appointment manager: list, filter, view, change status with history. */
if (!has_permission('appointments.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

$action = $_GET['action'] ?? 'list';

if ($action === 'status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_GET['id'] ?? 0);
    $new = (string)($_POST['status'] ?? '');
    $note = trim((string)($_POST['note'] ?? ''));
    $appt = db_row("SELECT * FROM appointments WHERE id = ?", [$id]);
    if ($appt && in_array($new, appointment_statuses(), true)) {
        db_exec("UPDATE appointments SET status = ? WHERE id = ?", [$new, $id]);
        db_exec("INSERT INTO appointment_status_history (appointment_id, old_status, new_status, changed_by, note) VALUES (?,?,?,?,?)",
            [$id, $appt['status'], $new, current_admin()['id'], $note ?: null]);
        log_activity('status_change', 'appointments', "Ref {$appt['reference']}: {$appt['status']} -> $new");
        flash('success', 'Appointment status updated.');
    }
    redirect('admin/?module=appointments&action=view&id=' . $id);
}

if ($action === 'view') {
    $id = (int)($_GET['id'] ?? 0);
    $a = db_row("SELECT a.*, d.name AS doctor_name, s.name AS spec_name FROM appointments a
                 LEFT JOIN doctors d ON d.id=a.doctor_id LEFT JOIN specialities s ON s.id=a.speciality_id
                 WHERE a.id = ?", [$id]);
    if (!$a) { echo '<div class="alert alert--error">Appointment not found.</div>'; return; }
    $history = db_all("SELECT h.*, ad.name AS by_name FROM appointment_status_history h LEFT JOIN admins ad ON ad.id=h.changed_by WHERE h.appointment_id=? ORDER BY h.created_at DESC", [$id]);
    ?>
    <div class="btn-row" style="margin-bottom:16px"><a class="btn btn--ghost" href="<?= url('admin/?module=appointments') ?>">&larr; All Appointments</a></div>
    <div class="card">
      <h2>Appointment <?= e($a['reference']) ?> <span class="badge <?= $a['status']==='New' ? 'badge--orange' : 'badge--green' ?>"><?= e($a['status']) ?></span></h2>
      <dl class="detail-list">
        <dt>Patient Name</dt><dd><?= e($a['name']) ?></dd>
        <dt>Mobile</dt><dd><a href="tel:<?= e($a['mobile']) ?>"><?= e($a['mobile']) ?></a></dd>
        <dt>Email</dt><dd><?= e($a['email'] ?: '—') ?></dd>
        <dt>Doctor</dt><dd><?= e($a['doctor_name'] ?: 'No preference') ?></dd>
        <dt>Service</dt><dd><?= e($a['spec_name'] ?: 'General') ?></dd>
        <dt>Preferred</dt><dd><?= e(format_date($a['preferred_date'])) ?> <?= e($a['preferred_time'] ?? '') ?></dd>
        <dt>Message</dt><dd><?= nl2br(e($a['message'] ?? '—')) ?></dd>
        <dt>Submitted</dt><dd><?= e(format_date($a['created_at'], 'd M Y, h:i A')) ?></dd>
      </dl>
    </div>
    <div class="card">
      <h2>Update Status</h2>
      <form method="post" action="<?= url('admin/?module=appointments&action=status&id=' . $id) ?>" class="btn-row">
        <?= csrf_field() ?>
        <select name="status" style="padding:10px 14px;border:1px solid var(--border);border-radius:10px">
          <?php foreach (appointment_statuses() as $st): ?>
          <option <?= $a['status'] === $st ? 'selected' : '' ?>><?= e($st) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="note" placeholder="Note (optional)" style="padding:10px 14px;border:1px solid var(--border);border-radius:10px;flex:1;min-width:180px">
        <button class="btn btn--primary" type="submit">Update</button>
      </form>
    </div>
    <div class="card">
      <h2>Status History</h2>
      <div class="timeline-log">
        <?php foreach ($history as $h): ?>
        <div class="timeline-log__item">
          <strong><?= e($h['old_status'] ?: 'Created') ?> &rarr; <?= e($h['new_status']) ?></strong>
          <?php if ($h['note']): ?> — <?= e($h['note']) ?><?php endif; ?>
          <small><?= e(format_date($h['created_at'], 'd M Y, h:i A')) ?><?= $h['by_name'] ? ' by ' . e($h['by_name']) : '' ?></small>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
    return;
}

// list
$status = $_GET['status'] ?? '';
$q = trim((string)($_GET['q'] ?? ''));
$where = '1=1'; $params = [];
if ($status !== '') { $where .= " AND a.status = ?"; $params[] = $status; }
if ($q !== '') { $where .= " AND (a.name LIKE ? OR a.mobile LIKE ? OR a.reference LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; $params[] = "%$q%"; }
$rows = db_all("SELECT a.*, d.name AS doctor_name FROM appointments a LEFT JOIN doctors d ON d.id=a.doctor_id WHERE $where ORDER BY a.created_at DESC LIMIT 200", $params);
?>
<div class="toolbar">
  <form method="get" action="<?= url('admin/') ?>">
    <input type="hidden" name="module" value="appointments">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Name, mobile or reference…">
    <select name="status">
      <option value="">All statuses</option>
      <?php foreach (appointment_statuses() as $st): ?>
      <option <?= $status === $st ? 'selected' : '' ?>><?= e($st) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn--ghost" type="submit">Filter</button>
  </form>
</div>
<div class="card" style="padding:0;overflow:auto">
  <table class="data">
    <thead><tr><th>Reference</th><th>Patient</th><th>Mobile</th><th>Doctor</th><th>Preferred Date</th><th>Status</th><th>Submitted</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rows as $a): ?>
      <tr>
        <td><strong><?= e($a['reference']) ?></strong></td>
        <td><?= e($a['name']) ?></td>
        <td><?= e($a['mobile']) ?></td>
        <td><?= e($a['doctor_name'] ?? '—') ?></td>
        <td><?= e(format_date($a['preferred_date'])) ?></td>
        <td><span class="badge <?= $a['status']==='New' ? 'badge--orange' : ($a['status']==='Confirmed' ? 'badge--green' : 'badge--grey') ?>"><?= e($a['status']) ?></span></td>
        <td><?= e(format_date($a['created_at'], 'd M Y')) ?></td>
        <td><a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=appointments&action=view&id=' . (int)$a['id']) ?>">View</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="8" style="text-align:center;color:var(--grey);padding:24px">No appointments found.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
