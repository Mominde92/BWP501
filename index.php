<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $today_students = $pdo->query("SELECT COUNT(*) FROM students WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $with_pic       = $pdo->query("SELECT COUNT(*) FROM students WHERE profile_picture IS NOT NULL AND profile_picture != ''")->fetchColumn();
    $total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
}

$title = "Home";
require_once "header.php";
?>
<?php if (!isset($_SESSION['user_id'])) { ?>
<div class="landing">
    <h1>Student Registration System</h1>
    <p>A simple system to add, search, edit and delete student records.</p>
    <ul>
        <li>Register and login with username or email</li>
        <li>Add students with a profile picture</li>
        <li>Search, sort and pagination</li>
    </ul>
    <div class="d-flex gap-2 justify-content-center mt-4 flex-wrap">
        <a href="login.php" class="btn btn-primary px-4">Login</a>
        <a href="register.php" class="btn btn-outline-secondary px-4">Register</a>
    </div>
</div>
<?php } else { ?>

<p class="welcome-card">Hello <b><?= htmlentities($_SESSION['username']) ?></b>, welcome to the student registration system.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <i class="bi bi-people-fill stat-card-icon"></i>
            <div>
                <div class="stat-label">Total Students</div>
                <div class="stat-value"><?= $total_students ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card green">
            <i class="bi bi-person-plus-fill stat-card-icon"></i>
            <div>
                <div class="stat-label">Added Today</div>
                <div class="stat-value"><?= $today_students ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card orange">
            <i class="bi bi-image-fill stat-card-icon"></i>
            <div>
                <div class="stat-label">With Picture</div>
                <div class="stat-value"><?= $with_pic ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card gray">
            <i class="bi bi-person-badge-fill stat-card-icon"></i>
            <div>
                <div class="stat-label">System Users</div>
                <div class="stat-value"><?= $total_users ?></div>
            </div>
        </div>
    </div>
</div>

<?php } ?>
<?php require_once "footer.php"; ?>
