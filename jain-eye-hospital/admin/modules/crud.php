<?php
/** Generic CRUD engine used by most admin modules. */

function handle_upload(string $field, string $kind): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed for ' . $field . ' (error ' . $file['error'] . ').');
    }
    $maxImage = 10 * 1024 * 1024; $maxVideo = 100 * 1024 * 1024;
    $allowedImage = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $allowedVideo = ['video/mp4' => 'mp4'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if ($kind === 'image') {
        if (!isset($allowedImage[$mime])) { throw new RuntimeException('Only JPG, PNG, WEBP or GIF images are allowed.'); }
        if ($file['size'] > $maxImage) { throw new RuntimeException('Image exceeds the 10MB limit.'); }
        $ext = $allowedImage[$mime];
    } else {
        if (!isset($allowedVideo[$mime])) { throw new RuntimeException('Only MP4 videos are allowed.'); }
        if ($file['size'] > $maxVideo) { throw new RuntimeException('Video exceeds the 100MB limit.'); }
        $ext = $allowedVideo[$mime];
    }

    $sub = date('Ym');
    $dir = dirname(__DIR__, 2) . '/uploads/' . $sub;
    if (!is_dir($dir)) { mkdir($dir, 0755, true); }
    $name = bin2hex(random_bytes(10)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
        throw new RuntimeException('Could not store the uploaded file.');
    }
    $path = $sub . '/' . $name;
    db_exec("INSERT INTO media_library (file_path, file_name, mime_type, file_size, uploaded_by, created_at) VALUES (?,?,?,?,?,NOW())",
        [$path, $file['name'], $mime, $file['size'], current_admin()['id'] ?? null]);
    return $path;
}

function unique_slug(string $table, string $slug, ?int $excludeId): string
{
    $base = $slug; $i = 2;
    while (true) {
        $row = db_row("SELECT id FROM `$table` WHERE slug = ?", [$slug]);
        if (!$row || ($excludeId && (int)$row['id'] === $excludeId)) { return $slug; }
        $slug = $base . '-' . $i++;
    }
}

function crud_list(array $mod, string $moduleKey): void
{
    $q = trim((string)($_GET['q'] ?? ''));
    $page = max(1, (int)($_GET['page'] ?? 1));
    $per = 15;
    $where = '1=1'; $params = [];
    if ($q !== '' && !empty($mod['search'])) {
        $likes = [];
        foreach ($mod['search'] as $col) { $likes[] = "`$col` LIKE ?"; $params[] = "%$q%"; }
        $where = '(' . implode(' OR ', $likes) . ')';
    }
    $table = $mod['table'];
    $total = (int)db_val("SELECT COUNT(*) FROM `$table` WHERE $where", $params);
    $pages = max(1, (int)ceil($total / $per));
    $page = min($page, $pages);
    $order = $mod['order'] ?? 'id DESC';
    $rows = db_all("SELECT * FROM `$table` WHERE $where ORDER BY $order LIMIT $per OFFSET " . (($page-1)*$per), $params);
    ?>
    <div class="toolbar">
      <form method="get" action="<?= url('admin/') ?>">
        <input type="hidden" name="module" value="<?= e($moduleKey) ?>">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search…">
        <button class="btn btn--ghost" type="submit">Search</button>
      </form>
      <a class="btn btn--orange" href="<?= url('admin/?module=' . $moduleKey . '&action=edit') ?>">+ Add New</a>
    </div>
    <div class="card" style="padding:0;overflow:auto">
      <table class="data">
        <thead><tr>
          <th>#</th>
          <?php foreach ($mod['columns'] as $col): ?><th><?= e(ucwords(str_replace('_', ' ', $col))) ?></th><?php endforeach; ?>
          <th style="width:150px">Actions</th>
        </tr></thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int)$row['id'] ?></td>
            <?php foreach ($mod['columns'] as $col):
              $val = (string)($row[$col] ?? '');
              if ($col === 'status') {
                  $cls = in_array($val, ['published','Published'], true) ? 'badge--green' : ($val === 'review' ? 'badge--blue' : 'badge--grey');
                  echo '<td><span class="badge ' . $cls . '">' . e(ucfirst($val)) . '</span></td>';
              } elseif (in_array($col, ['is_active','is_featured'], true)) {
                  echo '<td><span class="badge ' . ((int)$val ? 'badge--green' : 'badge--grey') . '">' . ((int)$val ? 'Yes' : 'No') . '</span></td>';
              } else {
                  echo '<td>' . e(excerpt($val, 60)) . '</td>';
              }
            endforeach; ?>
            <td class="btn-row">
              <a class="btn btn--ghost btn--sm" href="<?= url('admin/?module=' . $moduleKey . '&action=edit&id=' . (int)$row['id']) ?>">Edit</a>
              <form method="post" action="<?= url('admin/?module=' . $moduleKey . '&action=delete&id=' . (int)$row['id']) ?>" onsubmit="return confirm('Delete this record permanently?')" style="display:inline">
                <?= csrf_field() ?><button class="btn btn--danger btn--sm" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="9" style="text-align:center;color:var(--grey);padding:30px">No records found.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pager">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i === $page): ?><span class="is-current"><?= $i ?></span>
        <?php else: ?><a href="<?= url('admin/?module=' . $moduleKey . '&page=' . $i . ($q ? '&q=' . urlencode($q) : '')) ?>"><?= $i ?></a><?php endif; ?>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php
}

function crud_form(array $mod, string $moduleKey, ?array $row = null): void
{
    $isEdit = $row !== null;
    ?>
    <div class="card">
      <h2><?= $isEdit ? 'Edit' : 'Add New' ?> <?= e(rtrim($mod['label'], 's')) ?></h2>
      <form method="post" enctype="multipart/form-data" action="<?= url('admin/?module=' . $moduleKey . '&action=save' . ($isEdit ? '&id=' . (int)$row['id'] : '')) ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
        <?php foreach ($mod['fields'] as $f):
          $name = $f['name'];
          $val = $isEdit ? ($row[$name] ?? '') : ($f['default'] ?? '');
          $full = in_array($f['type'], ['textarea','richtext','image','video'], true) ? ' full' : '';
        ?>
          <div class="field<?= $full ?>">
            <label for="f_<?= e($name) ?>"><?= e($f['label']) ?><?= !empty($f['required']) ? ' *' : '' ?></label>
            <?php switch ($f['type']):
              case 'textarea': ?>
              <textarea id="f_<?= e($name) ?>" name="<?= e($name) ?>" <?= !empty($f['required']) ? 'required' : '' ?>><?= e((string)$val) ?></textarea>
              <?php break;
              case 'richtext': ?>
              <textarea id="f_<?= e($name) ?>" name="<?= e($name) ?>" rows="10"><?= e((string)$val) ?></textarea>
              <div class="hint">HTML tags like &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;strong&gt; are allowed.</div>
              <?php break;
              case 'slug': ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="text" value="<?= e((string)$val) ?>" placeholder="auto-generated if empty">
              <div class="hint">Leave empty to auto-generate from the <?= e($f['from'] ?? 'title') ?>.</div>
              <?php break;
              case 'url': ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="url" value="<?= e((string)$val) ?>" placeholder="https://…" inputmode="url">
              <div class="hint">Use an official public video or reel link. Only secure HTTPS links are accepted.</div>
              <?php break;
              case 'select': ?>
              <select id="f_<?= e($name) ?>" name="<?= e($name) ?>">
                <?php foreach ($f['options'] as $optVal => $optLabel): ?>
                <option value="<?= e((string)$optVal) ?>" <?= (string)$val === (string)$optVal ? 'selected' : '' ?>><?= e($optLabel) ?></option>
                <?php endforeach; ?>
              </select>
              <?php break;
              case 'fk':
                $options = db_all("SELECT id, `{$f['fk_label']}` AS label FROM `{$f['fk_table']}` ORDER BY `{$f['fk_label']}`"); ?>
              <select id="f_<?= e($name) ?>" name="<?= e($name) ?>">
                <option value="">— None —</option>
                <?php foreach ($options as $opt): ?>
                <option value="<?= (int)$opt['id'] ?>" <?= (string)$val === (string)$opt['id'] ? 'selected' : '' ?>><?= e($opt['label']) ?></option>
                <?php endforeach; ?>
              </select>
              <?php break;
              case 'number': ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="number" value="<?= e((string)$val) ?>">
              <?php break;
              case 'date': ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="date" value="<?= e((string)$val) ?>">
              <?php break;
              case 'datetime':
                $dtVal = $val ? date('Y-m-d\TH:i', strtotime((string)$val)) : ''; ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="datetime-local" value="<?= e($dtVal) ?>">
              <?php break;
              case 'checkbox': ?>
              <label style="display:flex;gap:10px;align-items:center;font-weight:400">
                <input type="checkbox" name="<?= e($name) ?>" value="1" <?= (int)$val ? 'checked' : '' ?> style="width:auto"> Yes
              </label>
              <?php break;
              case 'image': case 'video': ?>
              <?php if ($isEdit && $val): ?>
                <?php if ($f['type'] === 'image'): ?>
                <img class="preview" src="<?= e(uploads_url((string)$val)) ?>" alt="Current file">
                <?php else: ?>
                <div class="hint">Current: <?= e((string)$val) ?></div>
                <?php endif; ?>
                <label style="display:flex;gap:8px;align-items:center;font-weight:400;margin:8px 0">
                  <input type="checkbox" name="remove_<?= e($name) ?>" value="1" style="width:auto"> Remove current file
                </label>
              <?php endif; ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="file" accept="<?= $f['type'] === 'image' ? 'image/*' : 'video/mp4' ?>">
              <div class="hint"><?= $f['type'] === 'image' ? 'JPG, PNG, WEBP or GIF, max 10MB.' : 'MP4 only, max 100MB.' ?></div>
              <?php break;
              default: ?>
              <input id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="text" value="<?= e((string)$val) ?>" <?= !empty($f['required']) ? 'required' : '' ?>>
            <?php endswitch; ?>
          </div>
        <?php endforeach; ?>
        </div>
        <div class="btn-row" style="margin-top:22px">
          <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save Changes' : 'Create Record' ?></button>
          <a class="btn btn--ghost" href="<?= url('admin/?module=' . $moduleKey) ?>">Cancel</a>
        </div>
      </form>
    </div>
    <?php
}

function crud_save(array $mod, string $moduleKey, ?int $id): void
{
    csrf_check();
    $table = $mod['table'];
    $data = [];
    try {
        foreach ($mod['fields'] as $f) {
            $name = $f['name'];
            switch ($f['type']) {
                case 'checkbox':
                    $data[$name] = !empty($_POST[$name]) ? 1 : 0;
                    break;
                case 'image':
                case 'video':
                    if (!empty($_POST['remove_' . $name])) { $data[$name] = null; }
                    $uploaded = handle_upload($name, $f['type']);
                    if ($uploaded !== null) { $data[$name] = $uploaded; }
                    break;
                case 'fk':
                case 'number':
                    $v = trim((string)($_POST[$name] ?? ''));
                    $data[$name] = $v === '' ? null : (int)$v;
                    break;
                case 'date':
                case 'datetime':
                    $v = trim((string)($_POST[$name] ?? ''));
                    $data[$name] = $v === '' ? null : str_replace('T', ' ', $v);
                    break;
                case 'slug':
                    $v = trim((string)($_POST[$name] ?? ''));
                    if ($v === '') {
                        $src = trim((string)($_POST[$f['from'] ?? ''] ?? ''));
                        $v = slugify($src !== '' ? $src : 'item');
                    } else {
                        $v = slugify($v);
                    }
                    $data[$name] = unique_slug($table, $v, $id);
                    break;
                case 'url':
                    $v = trim((string)($_POST[$name] ?? ''));
                    if ($v !== '') {
                        $parts = parse_url($v);
                        if (!filter_var($v, FILTER_VALIDATE_URL) || !$parts ||
                            strtolower((string)($parts['scheme'] ?? '')) !== 'https' ||
                            empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
                            throw new RuntimeException($f['label'] . ' must be a valid HTTPS URL.');
                        }
                    }
                    $data[$name] = $v === '' ? null : $v;
                    break;
                default:
                    $v = trim((string)($_POST[$name] ?? ''));
                    if (!empty($f['required']) && $v === '') {
                        throw new RuntimeException($f['label'] . ' is required.');
                    }
                    $data[$name] = $v === '' ? null : $v;
            }
        }
        if ($moduleKey === 'reels' && ($data['status'] ?? 'published') === 'published') {
            $existing = $id ? db_row("SELECT video_path, video_url FROM reels WHERE id=?", [$id]) : [];
            $videoPath = array_key_exists('video_path', $data) ? $data['video_path'] : ($existing['video_path'] ?? null);
            $videoUrl = array_key_exists('video_url', $data) ? $data['video_url'] : ($existing['video_url'] ?? null);
            if (empty($videoPath) && empty($videoUrl)) {
                throw new RuntimeException('Add an MP4 video or an official HTTPS reel link before publishing.');
            }
        }
    } catch (RuntimeException $ex) {
        flash('error', $ex->getMessage());
        redirect('admin/?module=' . $moduleKey . '&action=edit' . ($id ? '&id=' . $id : ''));
    }

    if ($id) {
        $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
        db_exec("UPDATE `$table` SET $sets WHERE id = ?", [...array_values($data), $id]);
        log_activity('update', $moduleKey, "ID $id");
        flash('success', 'Record updated successfully.');
    } else {
        $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
        $marks = rtrim(str_repeat('?,', count($data)), ',');
        db_exec("INSERT INTO `$table` ($cols) VALUES ($marks)", array_values($data));
        log_activity('create', $moduleKey, 'ID ' . db()->lastInsertId());
        flash('success', 'Record created successfully.');
    }
    redirect('admin/?module=' . $moduleKey);
}

function crud_delete(array $mod, string $moduleKey, int $id): void
{
    csrf_check();
    db_exec("DELETE FROM `{$mod['table']}` WHERE id = ?", [$id]);
    log_activity('delete', $moduleKey, "ID $id");
    flash('success', 'Record deleted.');
    redirect('admin/?module=' . $moduleKey);
}
