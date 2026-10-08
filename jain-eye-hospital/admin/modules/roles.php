<?php
/** Roles & permissions management. */
if (!has_permission('settings.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

if (($_GET['action'] ?? '') === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $roleId = (int)($_POST['role_id'] ?? 0);
    $perms = array_map('intval', $_POST['permissions'] ?? []);
    if ($roleId === 1) {
        flash('error', 'Super Admin always has full access and cannot be modified.');
    } elseif ($roleId) {
        db_exec("DELETE FROM role_permissions WHERE role_id=?", [$roleId]);
        foreach ($perms as $pid) {
            db_exec("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?,?)", [$roleId, $pid]);
        }
        log_activity('update', 'roles', "Role ID $roleId");
        flash('success', 'Role permissions updated.');
    }
    redirect('admin/?module=roles');
}

$roles = db_all("SELECT * FROM roles ORDER BY id");
$permissions = db_all("SELECT * FROM permissions ORDER BY id");
$assigned = [];
foreach (db_all("SELECT * FROM role_permissions") as $rp) {
    $assigned[$rp['role_id']][] = (int)$rp['permission_id'];
}
?>
<?php foreach ($roles as $role): ?>
<div class="card">
  <h2><?= e($role['name']) ?> <small style="color:var(--grey);font-weight:400"><?= e($role['description'] ?? '') ?></small></h2>
  <?php if ((int)$role['id'] === 1): ?>
  <p style="color:var(--grey);font-size:.88rem">Super Admin has unrestricted access to every module.</p>
  <?php else: ?>
  <form method="post" action="<?= url('admin/?module=roles&action=save') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="role_id" value="<?= (int)$role['id'] ?>">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:10px;margin-bottom:16px">
      <?php foreach ($permissions as $perm): ?>
      <label style="display:flex;gap:10px;align-items:center;font-size:.88rem">
        <input type="checkbox" name="permissions[]" value="<?= (int)$perm['id'] ?>"
          <?= in_array((int)$perm['id'], $assigned[$role['id']] ?? [], true) ? 'checked' : '' ?> style="width:auto">
        <?= e($perm['label']) ?>
      </label>
      <?php endforeach; ?>
    </div>
    <button class="btn btn--primary" type="submit">Save Permissions</button>
  </form>
  <?php endif; ?>
</div>
<?php endforeach; ?>
