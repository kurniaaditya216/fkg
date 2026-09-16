<?php
// ==========================================
// LAYOUT HEADER
// ==========================================

if (!isset($page_title)) {
    $page_title = 'FKG UNPAD';
}

$script_name = basename($_SERVER['SCRIPT_NAME']);
$script_base = pathinfo($script_name, PATHINFO_FILENAME);

$dir_name = basename(dirname($_SERVER['SCRIPT_NAME']));
$is_sub = ($dir_name == 'admin' || $dir_name == 'dosen');

$base_dir = $is_sub ? '../' : '';
$css_dir  = $is_sub ? $dir_name . '/' : '';

$common_css = $base_dir . 'assets/css/style.css';
$page_css   = $base_dir . 'assets/css/' . $css_dir . $script_base . '.css';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="<?= $common_css; ?>">
    <link rel="stylesheet" href="<?= $page_css; ?>">
</head>
<body>

<?php if (isset($_SESSION['login'])): ?>
<nav class="navbar">
    <div class="logo">
        <a href="index.php">FKG UNPAD</a>
    </div>
    <div class="user">
        <span class="nama">👤 <?= htmlspecialchars($_SESSION['nama'] ?? ''); ?></span>
        <a class="logout" href="<?= $base_dir; ?>logout.php">Logout</a>
    </div>
</nav>
<?php endif; ?>