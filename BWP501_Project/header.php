<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$logged  = isset($_SESSION['user_id']);
$current = basename($_SERVER['PHP_SELF']);

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
    <title><?= isset($title) ? $title : "Student Registration System" ?> - SVU Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="<?= $logged ? 'app' : 'guest' ?>">

<?php if ($logged) { ?>
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="sidebar-brand">
        <a href="index.php"><i class="bi bi-mortarboard"></i> SVU Students</a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php" class="<?= active('index.php', $current) ?>"><i class="bi bi-house"></i> Home</a>
        <a href="view_students.php" class="<?= active('view_students.php', $current) . active('update_student.php', $current) . active('delete_student.php', $current) ?>"><i class="bi bi-people"></i> Students</a>
        <a href="add_student.php" class="<?= active('add_student.php', $current) ?>"><i class="bi bi-person-plus"></i> Add Student</a>
        <a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</aside>

<div class="main">
<header class="topbar">
    <button class="btn btn-sm btn-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>
    <span class="topbar-title"><?= isset($title) ? $title : "" ?></span>
    <div class="dropdown ms-auto">
        <a href="#" class="user-menu" data-bs-toggle="dropdown">
            <span class="avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
            <span class="d-none d-sm-inline"><?= htmlentities($_SESSION['username']) ?></span>
            <i class="bi bi-chevron-down small"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text small text-muted">Signed in as <b><?= htmlentities($_SESSION['username']) ?></b></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
    </div>
</header>
<?php } else { ?>
<nav class="navbar navbar-expand navbar-dark site-nav">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard"></i> SVU Students</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link <?= active('login.php', $current) ?>" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link <?= active('register.php', $current) ?>" href="register.php">Register</a></li>
        </ul>
    </div>
</nav>
<?php } ?>

<div class="<?= $logged ? 'container-fluid' : 'container' ?> content">
<?php if (!$logged) { ?>
<div class="guest-wrap">
<?php } ?>

<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show flash">' . htmlentities($_SESSION['error'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>' . "\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show flash">' . htmlentities($_SESSION['success'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>' . "\n";
    unset($_SESSION['success']);
}
?>
