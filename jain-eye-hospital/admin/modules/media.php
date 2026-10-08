<?php
/** Media library: list uploads, upload new, delete. */
if (!has_permission('content.manage')) { echo '<div class="alert alert--error">You do not have access to this module.</div>'; return; }

if (($_GET['action'] ?? '') === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    try {
        $path = handle_upload('media_file', 'image');
        flash('success', $path ? 'File uploaded.' : 'No file selected.');
    } catch (RuntimeException $ex) {
        flash('error', $ex->getMessage());
    }
    redirect('admin/?module=media');
}
if (($_GET['action'] ?? '') === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_GET['id'] ?? 0);
    $item = db_row("SELECT * FROM media_library WHERE id=?", [$id]);
    if ($item) {
        $file = dirname(__DIR__, 2) . '/uploads/' . $item['file_path'];
        if (is_file($file)) { unlink($file); }
        db_exec("DELETE FROM media_library WHERE id=?", [$id]);
        log_activity('delete', 'media', $item['file_path']);
        flash('success', 'File deleted.');
    }
    redirect('admin/?module=media');
}

$q = trim((string)($_GET['q'] ?? ''));
$params = []; $where = '1=1';
if ($q !== '') { $where = '(file_name LIKE ? OR alt_text LIKE ?)'; $params = ["%$q%", "%$q%"]; }
$items = db_all("SELECT * FROM media_library WHERE $where ORDER BY id DESC LIMIT 200", $params);
?>
<div class="card">
  <h2>Upload New File</h2>
  <form method="post" enctype="multipart/form-data" action="<?= url('admin/?module=media&action=upload') ?>" class="btn-row">
    <?= csrf_field() ?>
    <input type="file" name="media_file" accept="image/*" required style="padding:9px;border:1px dashed var(--border);border-radius:10px">
    <button class="btn btn--orange" type="submit">Upload</button>
  </form>
</div>
<div class="toolbar">
  <form method="get" action="<?= url('admin/') ?>">
    <input type="hidden" name="module" value="media">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search files…">
    <button class="btn btn--ghost" type="submit">Search</button>
  </form>
</div>
<div class="card">
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:16px">
    <?php foreach ($items as $item): ?>
    <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden">
      <img src="<?= e(uploads_url($item['file_path'])) ?>" alt="<?= e($item['alt_text'] ?? $item['file_name']) ?>" style="width:100%;aspect-ratio:1;object-fit:cover">
      <div style="padding:10px">
        <div style="font-size:.72rem;color:var(--grey);word-break:break-all"><?= e(excerpt($item['file_name'], 26)) ?></div>
        <form method="post" action="<?= url('admin/?module=media&action=delete&id=' . (int)$item['id']) ?>" onsubmit="return confirm('Delete this file?')" style="margin-top:8px">
          <?= csrf_field() ?><button class="btn btn--danger btn--sm" type="submit">Delete</button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (!$items): ?><p style="color:var(--grey)">No files uploaded yet.</p><?php endif; ?>
  </div>
</div>
