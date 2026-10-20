<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $today_students = $pdo->query("SELECT COUNT(*) FROM students WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $with_pic       = $pdo->query("SELECT COUNT(*) FROM students WHERE profile_picture IS NOT NULL AND profile_picture != ''")->fetchColumn();
    $total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $latest = $pdo->query("SELECT * FROM students ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
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

<p class="mb-4">Hello <b><?= htmlentities($_SESSION['username']) ?></b>, welcome to the student registration system.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="stat-value"><?= $total_students ?></div>
            <div class="stat-label">Total Students</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card green">
            <div class="stat-value"><?= $today_students ?></div>
            <div class="stat-label">Added Today</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card orange">
            <div class="stat-value"><?= $with_pic ?></div>
            <div class="stat-label">With Picture</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card gray">
            <div class="stat-value"><?= $total_users ?></div>
            <div class="stat-label">System Users</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h5>Latest Students</h5>
        <div class="d-flex gap-2">
            <a href="add_student.php" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Student</a>
            <a href="view_students.php" class="btn btn-sm btn-light">View all</a>
        </div>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead><tr><th>Student</th><th>Phone</th><th>Added</th></tr></thead>
        <tbody>
        <?php if (count($latest) == 0) { ?>
            <tr><td colspan="3" class="text-center text-muted py-4">No students yet</td></tr>
        <?php } ?>
        <?php foreach ($latest as $s) { ?>
            <tr>
                <td>
                    <div class="student-cell">
                        <?php if ($s['profile_picture'] != "" && file_exists($s['profile_picture'])) { ?>
                            <img src="<?= htmlentities($s['profile_picture']) ?>" class="student-img" alt="">
                        <?php } else { ?>
                            <div class="avatar"><?= strtoupper(substr($s['name'], 0, 1)) ?></div>
                        <?php } ?>
                        <div>
                            <div><?= htmlentities($s['name']) ?></div>
                            <small class="text-muted"><?= htmlentities($s['email']) ?></small>
                        </div>
                    </div>
                </td>
                <td><?= htmlentities($s['phone']) ?></td>
                <td class="text-nowrap"><?= date("d/m/Y", strtotime($s['created_at'])) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</div>
<?php } ?>
<?php require_once "footer.php"; ?>
