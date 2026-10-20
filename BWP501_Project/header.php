<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$logged  = isset($_SESSION['user_id']);
$current = basename($_SERVER['PHP_SELF']);

// دالة صغيرة لتلوين رابط الصفحة الحالية بالسايد بار
function active($page, $current)
{
    return ($page == $current) ? "active" : "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : "Student Registration System" ?> | SVU Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="<?= $logged ? 'app' : 'guest' ?>">

<?php if ($logged) { ?>
<!-- Sidebar: ثابت على الشاشات الكبيرة، وبيصير offcanvas على الموبايل -->
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <div>
            <div class="brand-name">SVU Students</div>
            <div class="brand-sub">Registration System</div>
        </div>
        <button type="button" class="btn-close btn-close-white d-lg-none ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
    </div>

    <div class="sidebar-label">Main</div>
    <nav class="sidebar-nav">
        <a href="index.php" class="<?= active('index.php', $current) ?>"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a href="view_students.php" class="<?= active('view_students.php', $current) . active('update_student.php', $current) . active('delete_student.php', $current) ?>"><i class="bi bi-people"></i> Students</a>
        <a href="add_student.php" class="<?= active('add_student.php', $current) ?>"><i class="bi bi-person-plus"></i> Add Student</a>
    </nav>

    <div class="sidebar-label">Account</div>
    <nav class="sidebar-nav">
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>

    <div class="sidebar-user">
        <div class="avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
        <div class="text-truncate">
            <div class="fw-semibold text-truncate"><?= htmlentities($_SESSION['username']) ?></div>
            <small>Administrator</small>
        </div>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <button class="btn btn-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <h1 class="page-title"><?= isset($title) ? $title : "" ?></h1>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-sm-inline"><i class="bi bi-calendar3"></i> <?= date("D, d M Y") ?></span>
        </div>
    </header>
    <div class="content">
<?php } else { ?>
<div class="guest-wrap">
<?php } ?>

<?php
// Flash pattern - نعرض الرسالة مرة وحدة وبعدين نحذفها
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show flash"><i class="bi bi-exclamation-circle me-2"></i>' . htmlentities($_SESSION['error'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>' . "\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show flash"><i class="bi bi-check-circle me-2"></i>' . htmlentities($_SESSION['success'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>' . "\n";
    unset($_SESSION['success']);
}
?>
