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
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body class="<?= $logged ? 'app' : 'guest' ?>">

<?php if ($logged) {
    $page_icons = array(
        'Home'           => 'bi-house-door-fill',
        'Students'       => 'bi-people-fill',
        'Add Student'    => 'bi-person-plus-fill',
        'Edit Student'   => 'bi-pencil-square',
        'Delete Student' => 'bi-trash3-fill'
    );
    $page_icon = isset($page_icons[$title]) ? $page_icons[$title] : 'bi-grid-fill';
?>
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="sidebar-brand">
        <a href="index.php"><i class="bi bi-mortarboard-fill"></i> SVU Students</a>
        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php" class="<?= active('index.php', $current) ?>"><i class="bi bi-speedometer2 icon-indigo"></i> Home</a>
        <a href="view_students.php" class="<?= active('view_students.php', $current) . active('update_student.php', $current) . active('delete_student.php', $current) ?>"><i class="bi bi-people-fill icon-blue"></i> Students</a>
        <a href="add_student.php" class="<?= active('add_student.php', $current) ?>"><i class="bi bi-person-plus-fill icon-green"></i> Add Student</a>
        <a href="logout.php"><i class="bi bi-box-arrow-left icon-red"></i> Logout</a>
    </nav>
</aside>

<div class="main">
<header class="topbar">
    <button class="btn btn-sm topbar-toggle d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="topbar-date d-none d-md-inline"><i class="bi bi-clock-fill"></i> <?= date("l, F j, Y") ?></span>
        <span class="topbar-clock d-none d-sm-inline" id="clock"><?= date("h:i:s A") ?></span>
        <div class="dropdown">
            <a href="#" class="user-menu" data-bs-toggle="dropdown">
                <span class="avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
                <span class="d-none d-sm-inline"><?= htmlentities($_SESSION['username']) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                <li class="user-dropdown-title">Welcome !</li>
                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-person-walking"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>
<div class="page-header">
    <h1 class="page-title"><i class="bi <?= $page_icon ?>"></i> <?= isset($title) ? $title : "" ?></h1>
    <span class="breadcrumb-line"><a href="index.php"><i class="bi bi-house-door-fill"></i></a> - <?= isset($title) ? $title : "" ?></span>
</div>
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
