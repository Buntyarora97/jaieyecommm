<?php
/** Admin user management. */
if (!has_permission('settings.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

$action = $_GET['action'] ?? 'list';

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)$_POST['name']);
    $email = trim((string)$_POST['email']);
    $roleId = (int)$_POST['role_id'] ?: null;
    $active = !empty($_POST['is_active']) ? 1 : 0;
    $password = (string)$_POST['password'];

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Valid name and email are required.');
        redirect('admin/?module=users');
    }
    if ($id) {
        db_exec("UPDATE admins SET name=?, email=?, role_id=?, is_active=? WHERE id=?", [$name, $email, $roleId, $active, $id]);
        if ($password !== '') {
            if (strlen($password) < 8) { flash('error', 'Password must be at least 8 characters.'); redirect('admin/?module=users'); }
            db_exec("UPDATE admins SET password_hash=? WHERE id=?", [password_hash($password, PASSWORD_DEFAULT), $id]);
        }
        log_activity('update', 'users', "ID $id");
        flash('success', 'User updated.');
    } else {
        if (strlen($password) < 8) { flash('error', 'Password must be at least 8 characters.'); redirect('admin/?module=users'); }
        db_exec("INSERT INTO admins (name, email, role_id, password_hash, is_active) VALUES (?,?,?,?,?)",
            [$name, $email, $roleId, password_hash($password, PASSWORD_DEFAULT), $active]);
        log_activity('create', 'users', $email);
        flash('success', 'User created.');
    }
    redirect('admin/?module=users');
}
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_GET['id'] ?? 0);
    if ($id === (int)current_admin()['id']) {
        flash('error', 'You cannot delete your own account.');
    } else {
        db_exec("DELETE FROM admins WHERE id=?", [$id]);
        log_activity('delete', 'users', "ID $id");
        flash('success', 'User deleted.');
    }
    redirect('admin/?module=users');
}

$edit = null;
if ($action === 'edit') {
    $edit = db_row("SELECT * FROM admins WHERE id=?", [(int)($_GET['id'] ?? 0)]);
}
$roles = db_all("SELECT * FROM roles ORDER BY id");
$users = db_all("SELECT a.*, r.name AS role_name FROM admins a LEFT JOIN roles r ON r.id=a.role_id ORDER BY a.id");
?>
<div class="card">
  <h2><?= $edit ? 'Edit User' : 'Add User' ?></h2>
  <form method="post" action="<?= url('admin/?module=users&action=save') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
    <div class="form-grid">
      <div class="field"><label>Name *</label><input name="name" required value="<?= e($edit['name'] ?? '') ?>"></div>
      <div class="field"><label>Email *</label><input name="email" type="email" required value="<?= e($edit['email'] ?? '') ?>"></div>
      <div class="field"><label>Role</label>
        <select name="role_id">
          <option value="">— None —</option>
          <?php foreach ($roles as $r): ?>
          <option value="<?= (int)$r['id'] ?>" <?= (int)($edit['role_id'] ?? 0) === (int)$r['id'] ? 'selected' : '' ?>><?= e($r['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="field"><label><?= $edit ? 'New Password (leave blank to keep)' : 'Password (min 8 chars) *' ?></label>
        <input name="password" type="password" <?= $edit ? '' : 'required' ?> autocomplete="new-password"></div>
      <div class="field"><label style="display:flex;gap:10px;align-items:center;font-weight:400">
        <input type="checkbox" name="is_active" value="1" <?= (int)($edit['is_active'] ?? 1) ? 'checked' : '' ?> style="width:auto"> Active
      </label></div>
    </div>
    <div class="btn-row" style="margin-top:16px">
      <button class="btn btn--primary" type="submit"><?= $edit ? 'Save Changes' : 'Create User' ?></button>
      <?php if ($edit): ?><a class="btn btn--ghost" href="<?= url('admin/?module=users') ?>">Cancel</a><?php endif; ?>
    </div>
  </form>
</div>
<div class="card" style="padding:0;overflow:auto">
  <table class="data">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th>Last Login</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= e($u['name']) ?></td>
        <td><?= e($u['email']) ?></td>
        <td><?= e($u['role_name'] ?? '—') ?></td>
        <td><span class="badge <?= (int)$u['is_active'] ? 'badge--green' : 'badge--grey' ?>"><?= (int)$u['is_active'] ? 'Yes' : 'No' ?></span></td>
        <td><?= e($u['last_login_at'] ? format_date($u['last_login_at'], 'd M Y, h:i A') : 'Never') ?></td>
        <td class="btn-row">
          <a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=users&action=edit&id=' . (int)$u['id']) ?>">Edit</a>
          <form method="post" action="<?= url('admin/?module=users&action=delete&id=' . (int)$u['id']) ?>" onsubmit="return confirm('Delete this user?')" style="display:inline">
            <?= csrf_field() ?><button class="btn btn--danger btn--sm" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
