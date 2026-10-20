<?php
require_once "pdo.php";
session_start();

// احصائيات لوحة التحكم (بس للمستخدم المسجل)
if (isset($_SESSION['user_id'])) {
    $total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $today_students = $pdo->query("SELECT COUNT(*) FROM students WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $with_pic       = $pdo->query("SELECT COUNT(*) FROM students WHERE profile_picture IS NOT NULL AND profile_picture != ''")->fetchColumn();
    $total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $latest = $pdo->query("SELECT * FROM students ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
}

$title = "Dashboard";
require_once "header.php";
?>
<?php if (!isset($_SESSION['user_id'])) { ?>
<div class="landing">
    <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
    <h1>Student Registration System</h1>
    <p>Manage students easily: add, search, edit and delete student records from one place.</p>
    <div class="d-flex gap-2 justify-content-center mt-4 flex-wrap">
        <a href="login.php" class="btn btn-primary btn-lg px-4">Login</a>
        <a href="register.php" class="btn btn-outline-light btn-lg px-4">Create Account</a>
    </div>
    <div class="features">
        <span><i class="bi bi-shield-lock"></i> Secure login</span>
        <span><i class="bi bi-search"></i> Search &amp; pagination</span>
        <span><i class="bi bi-image"></i> Profile pictures</span>
        <span><i class="bi bi-phone"></i> Mobile friendly</span>
    </div>
</div>
<?php } else { ?>

<div class="welcome mb-4">
    <h3 class="fw-bold mb-1">Welcome back, <?= htmlentities($_SESSION['username']) ?></h3>
    <p class="mb-0 opacity-75">Here is a quick overview of the students system.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon bg-soft-primary"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-value"><?= $total_students ?></div><div class="stat-label">Total Students</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon bg-soft-success"><i class="bi bi-person-plus-fill"></i></div>
            <div><div class="stat-value"><?= $today_students ?></div><div class="stat-label">Added Today</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon bg-soft-warning"><i class="bi bi-image-fill"></i></div>
            <div><div class="stat-value"><?= $with_pic ?></div><div class="stat-label">With Picture</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon bg-soft-info"><i class="bi bi-person-badge-fill"></i></div>
            <div><div class="stat-value"><?= $total_users ?></div><div class="stat-label">System Users</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-head">
                <h5>Latest Students</h5>
                <a href="view_students.php" class="btn btn-sm btn-light">View all <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
            <table class="table">
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
                                    <div class="fw-semibold"><?= htmlentities($s['name']) ?></div>
                                    <small class="text-muted"><?= htmlentities($s['email']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlentities($s['phone']) ?></td>
                        <td class="text-muted small text-nowrap"><?= date("d M Y", strtotime($s['created_at'])) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-head"><h5>Quick Actions</h5></div>
            <div class="card-body d-grid gap-3">
                <a href="add_student.php" class="quick-link">
                    <div class="stat-icon bg-soft-primary"><i class="bi bi-person-plus"></i></div>
                    <div><div class="fw-semibold">Add Student</div><small class="text-muted">Register a new student</small></div>
                </a>
                <a href="view_students.php" class="quick-link">
                    <div class="stat-icon bg-soft-success"><i class="bi bi-search"></i></div>
                    <div><div class="fw-semibold">Search Students</div><small class="text-muted">Find by name, email, phone</small></div>
                </a>
                <a href="logout.php" class="quick-link">
                    <div class="stat-icon bg-soft-warning"><i class="bi bi-box-arrow-left"></i></div>
                    <div><div class="fw-semibold">Logout</div><small class="text-muted">End your session</small></div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php require_once "footer.php"; ?>
