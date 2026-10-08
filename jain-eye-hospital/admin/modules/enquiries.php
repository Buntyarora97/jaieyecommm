<?php
/** Contact enquiries: list, view, status, internal notes. */
if (!has_permission('enquiries.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

$action = $_GET['action'] ?? 'list';

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_GET['id'] ?? 0);
    $status = (string)($_POST['status'] ?? '');
    $note = trim((string)($_POST['note'] ?? ''));
    if (in_array($status, ['New','In Progress','Resolved','Closed'], true)) {
        db_exec("UPDATE contact_enquiries SET status=? WHERE id=?", [$status, $id]);
    }
    if ($note !== '') {
        db_exec("INSERT INTO enquiry_notes (enquiry_id, admin_id, note) VALUES (?,?,?)", [$id, current_admin()['id'], $note]);
    }
    log_activity('update', 'enquiries', "ID $id");
    flash('success', 'Enquiry updated.');
    redirect('admin/?module=enquiries&action=view&id=' . $id);
}

if ($action === 'view') {
    $id = (int)($_GET['id'] ?? 0);
    $en = db_row("SELECT * FROM contact_enquiries WHERE id=?", [$id]);
    if (!$en) { echo '<div class="alert alert--error">Enquiry not found.</div>'; return; }
    $notes = db_all("SELECT n.*, a.name AS by_name FROM enquiry_notes n LEFT JOIN admins a ON a.id=n.admin_id WHERE n.enquiry_id=? ORDER BY n.created_at DESC", [$id]);
    ?>
    <div class="btn-row" style="margin-bottom:16px"><a class="btn btn--ghost" href="<?= url('admin/?module=enquiries') ?>">&larr; All Enquiries</a></div>
    <div class="card">
      <h2>Enquiry from <?= e($en['name']) ?> <span class="badge badge--grey"><?= e($en['status']) ?></span></h2>
      <dl class="detail-list">
        <dt>Email</dt><dd><a href="mailto:<?= e($en['email']) ?>"><?= e($en['email']) ?></a></dd>
        <dt>Phone</dt><dd><?= e($en['phone'] ?: '—') ?></dd>
        <dt>Subject</dt><dd><?= e($en['subject'] ?: '—') ?></dd>
        <dt>Message</dt><dd><?= nl2br(e($en['message'])) ?></dd>
        <dt>Contact consent</dt><dd><?= (int)$en['consent'] === 1 ? 'Yes' : 'No' ?></dd>
        <dt>Received</dt><dd><?= e(format_date($en['created_at'], 'd M Y, h:i A')) ?></dd>
      </dl>
    </div>
    <div class="card">
      <h2>Update &amp; Add Note</h2>
      <form method="post" action="<?= url('admin/?module=enquiries&action=update&id=' . $id) ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
          <div class="field"><label>Status</label>
            <select name="status">
              <?php foreach (['New','In Progress','Resolved','Closed'] as $st): ?>
              <option <?= $en['status'] === $st ? 'selected' : '' ?>><?= e($st) ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="field"><label>Internal Note</label><input type="text" name="note" placeholder="Add a note for the team"></div>
        </div>
        <div class="btn-row" style="margin-top:14px"><button class="btn btn--primary" type="submit">Save</button></div>
      </form>
    </div>
    <?php if ($notes): ?>
    <div class="card"><h2>Notes</h2><div class="timeline-log">
      <?php foreach ($notes as $n): ?>
      <div class="timeline-log__item"><?= nl2br(e($n['note'])) ?><small><?= e(format_date($n['created_at'], 'd M Y, h:i A')) ?><?= $n['by_name'] ? ' by ' . e($n['by_name']) : '' ?></small></div>
      <?php endforeach; ?>
    </div></div>
    <?php endif; ?>
    <?php
    return;
}

$rows = db_all("SELECT * FROM contact_enquiries ORDER BY created_at DESC LIMIT 300");
?>
<div class="card" style="padding:0;overflow:auto">
  <table class="data">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Status</th><th>Received</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rows as $en): ?>
      <tr>
        <td><?= (int)$en['id'] ?></td>
        <td><?= e($en['name']) ?></td>
        <td><?= e($en['email']) ?></td>
        <td><?= e(excerpt($en['subject'] ?? '', 40)) ?></td>
        <td><span class="badge <?= $en['status']==='New' ? 'badge--orange' : 'badge--grey' ?>"><?= e($en['status']) ?></span></td>
        <td><?= e(format_date($en['created_at'], 'd M Y')) ?></td>
        <td><a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=enquiries&action=view&id=' . (int)$en['id']) ?>">View</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="7" style="text-align:center;color:var(--grey);padding:24px">No enquiries yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
