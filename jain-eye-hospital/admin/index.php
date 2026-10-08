<?php
/** Admin CMS front controller. */
require __DIR__ . '/includes/bootstrap.php';
require_login();
require __DIR__ . '/modules/definitions.php';
require __DIR__ . '/modules/crud.php';

$module = (string)($_GET['module'] ?? 'dashboard');
$action = (string)($_GET['action'] ?? 'list');
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$modules = admin_modules();
$custom = ['dashboard', 'appointments', 'enquiries', 'media', 'users', 'roles', 'settings', 'navigation', 'audit'];

// Permission gate
$perm = $modules[$module]['permission'] ?? null;
if ($perm && !has_permission($perm)) {
    admin_header('Access Denied');
    echo '<div class="alert alert--error">You do not have permission to access this module. Please contact a Super Admin.</div>';
    admin_footer();
    exit;
}

// Handle generic CRUD save/delete before any output
if (isset($modules[$module])) {
    $mod = $modules[$module];
    if ($action === 'save') { crud_save($mod, $module, $id ?: null); }
    if ($action === 'delete' && $id) { crud_delete($mod, $module, $id); }
    $title = $mod['label'];
} else {
    $titles = ['dashboard'=>'Dashboard','appointments'=>'Appointments','enquiries'=>'Contact Enquiries',
        'media'=>'Media Library','users'=>'Admin Users','roles'=>'Roles & Permissions',
        'settings'=>'Settings','navigation'=>'Navigation Manager','audit'=>'Audit Logs'];
    $title = $titles[$module] ?? 'Dashboard';
    if (!in_array($module, $custom, true)) { $module = 'dashboard'; $title = 'Dashboard'; }
}

admin_header($title);

if (isset($modules[$module])) {
    $mod = $modules[$module];
    if ($action === 'edit') {
        $row = $id ? db_row("SELECT * FROM `{$mod['table']}` WHERE id = ?", [$id]) : null;
        if ($id && !$row) { echo '<div class="alert alert--error">Record not found.</div>'; }
        else { crud_form($mod, $module, $row); }
    } else {
        crud_list($mod, $module);
    }
} else {
    require __DIR__ . '/modules/' . $module . '.php';
}

admin_footer();
