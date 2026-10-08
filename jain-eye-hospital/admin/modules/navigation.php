<?php
/** Navigation manager: header items (tree) + mega menu columns. */
if (!has_permission('settings.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

$action = $_GET['action'] ?? 'list';

if ($action === 'save_item' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'parent_id' => (int)$_POST['parent_id'] ?: null,
        'label' => trim((string)$_POST['label']),
        'url' => trim((string)$_POST['url']),
        'has_mega' => !empty($_POST['has_mega']) ? 1 : 0,
        'position' => (int)$_POST['position'],
        'is_active' => !empty($_POST['is_active']) ? 1 : 0,
    ];
    if ($data['label'] === '' || $data['url'] === '') {
        flash('error', 'Label and URL are required.');
        redirect('admin/?module=navigation');
    }
    if ($id) {
        db_exec("UPDATE navigation_items SET parent_id=?, label=?, url=?, has_mega=?, position=?, is_active=? WHERE id=?",
            [...array_values($data), $id]);
        flash('success', 'Menu item updated.');
    } else {
        db_exec("INSERT INTO navigation_items (parent_id, menu_location, label, url, has_mega, position, is_active) VALUES (?,?,?,?,?,?,?)",
            [$data['parent_id'], 'header', $data['label'], $data['url'], $data['has_mega'], $data['position'], $data['is_active']]);
        flash('success', 'Menu item added.');
    }
    log_activity('save', 'navigation', $data['label']);
    redirect('admin/?module=navigation');
}
if ($action === 'delete_item' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_GET['id'] ?? 0);
    db_exec("UPDATE navigation_items SET parent_id=NULL WHERE parent_id=?", [$id]);
    db_exec("DELETE FROM navigation_items WHERE id=?", [$id]);
    flash('success', 'Menu item deleted.');
    redirect('admin/?module=navigation');
}
if ($action === 'save_col' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $type = $_POST['column_type'] === 'featured' ? 'featured' : 'links';
    $data = [
        'nav_item_id' => (int)$_POST['nav_item_id'],
        'column_type' => $type,
        'title' => trim((string)$_POST['title']),
        'links_json' => trim((string)$_POST['links_json']),
        'heading' => trim((string)$_POST['heading']),
        'text' => trim((string)$_POST['text']),
        'cta_label' => trim((string)$_POST['cta_label']),
        'cta_url' => trim((string)$_POST['cta_url']),
        'position' => (int)$_POST['position'],
    ];
    if ($type === 'links' && $data['links_json'] !== '' && json_decode($data['links_json'], true) === null) {
        flash('error', 'Links JSON is invalid. Use format: [{"label":"Name","url":"/page"}]');
        redirect('admin/?module=navigation');
    }
    if ($id) {
        db_exec("UPDATE mega_menu_columns SET nav_item_id=?, column_type=?, title=?, links_json=?, heading=?, text=?, cta_label=?, cta_url=?, position=? WHERE id=?",
            [$data['nav_item_id'], $data['column_type'], $data['title'], $data['links_json'] ?: null, $data['heading'] ?: null, $data['text'] ?: null, $data['cta_label'] ?: null, $data['cta_url'] ?: null, $data['position'], $id]);
        flash('success', 'Mega menu column updated.');
    } else {
        db_exec("INSERT INTO mega_menu_columns (nav_item_id, column_type, title, links_json, heading, text, cta_label, cta_url, position) VALUES (?,?,?,?,?,?,?,?,?)",
            [$data['nav_item_id'], $data['column_type'], $data['title'] ?: null, $data['links_json'] ?: null, $data['heading'] ?: null, $data['text'] ?: null, $data['cta_label'] ?: null, $data['cta_url'] ?: null, $data['position']]);
        flash('success', 'Mega menu column added.');
    }
    log_activity('save', 'navigation', 'Mega column');
    redirect('admin/?module=navigation');
}
if ($action === 'delete_col' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    db_exec("DELETE FROM mega_menu_columns WHERE id=?", [(int)($_GET['id'] ?? 0)]);
    flash('success', 'Column deleted.');
    redirect('admin/?module=navigation');
}

$items = db_all("SELECT n.*, p.label AS parent_label FROM navigation_items n LEFT JOIN navigation_items p ON p.id=n.parent_id WHERE n.menu_location='header' ORDER BY COALESCE(n.parent_id, n.id), n.parent_id IS NOT NULL, n.position");
$cols = db_all("SELECT m.*, n.label AS nav_label FROM mega_menu_columns m JOIN navigation_items n ON n.id=m.nav_item_id ORDER BY m.nav_item_id, m.position");
$topItems = db_all("SELECT * FROM navigation_items WHERE parent_id IS NULL AND menu_location='header' ORDER BY position");
$editItem = ($action === 'edit_item') ? db_row("SELECT * FROM navigation_items WHERE id=?", [(int)($_GET['id'] ?? 0)]) : null;
$editCol = ($action === 'edit_col') ? db_row("SELECT * FROM mega_menu_columns WHERE id=?", [(int)($_GET['id'] ?? 0)]) : null;
?>
<div class="card">
  <h2><?= $editItem ? 'Edit Menu Item' : 'Add Menu Item' ?></h2>
  <form method="post" action="<?= url('admin/?module=navigation&action=save_item') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($editItem['id'] ?? 0) ?>">
    <div class="form-grid">
      <div class="field"><label>Parent</label>
        <select name="parent_id">
          <option value="">— Top Level —</option>
          <?php foreach ($topItems as $t): ?>
          <option value="<?= (int)$t['id'] ?>" <?= (int)($editItem['parent_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>><?= e($t['label']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="field"><label>Label *</label><input name="label" required value="<?= e($editItem['label'] ?? '') ?>"></div>
      <div class="field"><label>URL *</label><input name="url" required value="<?= e($editItem['url'] ?? '') ?>" placeholder="/about-us"></div>
      <div class="field"><label>Position</label><input name="position" type="number" value="<?= (int)($editItem['position'] ?? 0) ?>"></div>
      <div class="field"><label style="display:flex;gap:10px;font-weight:400;align-items:center"><input type="checkbox" name="has_mega" value="1" <?= (int)($editItem['has_mega'] ?? 0) ? 'checked' : '' ?> style="width:auto"> Has Mega Menu</label></div>
      <div class="field"><label style="display:flex;gap:10px;font-weight:400;align-items:center"><input type="checkbox" name="is_active" value="1" <?= (int)($editItem['is_active'] ?? 1) ? 'checked' : '' ?> style="width:auto"> Active</label></div>
    </div>
    <div class="btn-row" style="margin-top:14px"><button class="btn btn--primary" type="submit">Save Item</button>
      <?php if ($editItem): ?><a class="btn btn--ghost" href="<?= url('admin/?module=navigation') ?>">Cancel</a><?php endif; ?></div>
  </form>
</div>
<div class="card" style="padding:0;overflow:auto">
  <table class="data">
    <thead><tr><th>#</th><th>Label</th><th>URL</th><th>Parent</th><th>Mega</th><th>Pos</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= (int)$it['id'] ?></td>
        <td><?= $it['parent_id'] ? '&nbsp;&nbsp;└ ' : '' ?><strong><?= e($it['label']) ?></strong></td>
        <td><?= e($it['url']) ?></td>
        <td><?= e($it['parent_label'] ?? '—') ?></td>
        <td><?= (int)$it['has_mega'] ? 'Yes' : '—' ?></td>
        <td><?= (int)$it['position'] ?></td>
        <td><span class="badge <?= (int)$it['is_active'] ? 'badge--green' : 'badge--grey' ?>"><?= (int)$it['is_active'] ? 'Yes' : 'No' ?></span></td>
        <td class="btn-row">
          <a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=navigation&action=edit_item&id=' . (int)$it['id']) ?>">Edit</a>
          <form method="post" action="<?= url('admin/?module=navigation&action=delete_item&id=' . (int)$it['id']) ?>" onsubmit="return confirm('Delete this item?')" style="display:inline">
            <?= csrf_field() ?><button class="btn btn--danger btn--sm" type="submit">Del</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h2><?= $editCol ? 'Edit Mega Menu Column' : 'Add Mega Menu Column' ?></h2>
  <form method="post" action="<?= url('admin/?module=navigation&action=save_col') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($editCol['id'] ?? 0) ?>">
    <div class="form-grid">
      <div class="field"><label>Belongs To (top item with mega menu)</label>
        <select name="nav_item_id">
          <?php foreach ($topItems as $t): ?>
          <option value="<?= (int)$t['id'] ?>" <?= (int)($editCol['nav_item_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>><?= e($t['label']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="field"><label>Column Type</label>
        <select name="column_type">
          <option value="links" <?= ($editCol['column_type'] ?? '') === 'links' ? 'selected' : '' ?>>Links Column</option>
          <option value="featured" <?= ($editCol['column_type'] ?? '') === 'featured' ? 'selected' : '' ?>>Featured Card</option>
        </select></div>
      <div class="field"><label>Column Title (links type)</label><input name="title" value="<?= e($editCol['title'] ?? '') ?>"></div>
      <div class="field"><label>Position</label><input name="position" type="number" value="<?= (int)($editCol['position'] ?? 0) ?>"></div>
      <div class="field full"><label>Links JSON (links type)</label>
        <textarea name="links_json" placeholder='[{"label":"Page Name","url":"/page-url"}]'><?= e($editCol['links_json'] ?? '') ?></textarea></div>
      <div class="field"><label>Featured Heading</label><input name="heading" value="<?= e($editCol['heading'] ?? '') ?>"></div>
      <div class="field"><label>Featured Text</label><input name="text" value="<?= e($editCol['text'] ?? '') ?>"></div>
      <div class="field"><label>CTA Label</label><input name="cta_label" value="<?= e($editCol['cta_label'] ?? '') ?>"></div>
      <div class="field"><label>CTA URL</label><input name="cta_url" value="<?= e($editCol['cta_url'] ?? '') ?>"></div>
    </div>
    <div class="btn-row" style="margin-top:14px"><button class="btn btn--primary" type="submit">Save Column</button>
      <?php if ($editCol): ?><a class="btn btn--ghost" href="<?= url('admin/?module=navigation') ?>">Cancel</a><?php endif; ?></div>
  </form>
</div>
<div class="card" style="padding:0;overflow:auto">
  <table class="data">
    <thead><tr><th>#</th><th>Menu</th><th>Type</th><th>Title / Heading</th><th>Pos</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($cols as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= e($c['nav_label']) ?></td>
        <td><span class="badge <?= $c['column_type']==='featured' ? 'badge--blue' : 'badge--grey' ?>"><?= e($c['column_type']) ?></span></td>
        <td><?= e($c['column_type']==='featured' ? $c['heading'] : $c['title']) ?></td>
        <td><?= (int)$c['position'] ?></td>
        <td class="btn-row">
          <a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=navigation&action=edit_col&id=' . (int)$c['id']) ?>">Edit</a>
          <form method="post" action="<?= url('admin/?module=navigation&action=delete_col&id=' . (int)$c['id']) ?>" onsubmit="return confirm('Delete this column?')" style="display:inline">
            <?= csrf_field() ?><button class="btn btn--danger btn--sm" type="submit">Del</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
