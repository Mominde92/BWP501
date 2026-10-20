<?php
require_once "pdo.php";
require_once "check_login.php";

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE id = :id");
    $stmt->execute(array(':id' => $_POST['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false && $row['profile_picture'] != "" && file_exists($row['profile_picture'])) {
        unlink($row['profile_picture']);
    }

    $stmt = $pdo->prepare("DELETE FROM students WHERE id = :zip");
    $stmt->execute(array(':zip' => $_POST['id']));
    $_SESSION['success'] = "Student deleted";
    header("Location: view_students.php");
    return;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Missing student id";
    header("Location: view_students.php");
    return;
}

$stmt = $pdo->prepare("SELECT id, name, email FROM students WHERE id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = "Bad value for student id";
    header("Location: view_students.php");
    return;
}

$title = "Delete Student";
require_once "header.php";
?>
<div class="row justify-content-center mt-lg-4">
  <div class="col-md-7 col-lg-5">
    <div class="card text-center">
      <div class="card-body p-4">
        <div class="stat-icon mx-auto mb-3" style="background:#fef2f2;color:#dc2626;width:64px;height:64px;font-size:28px">
          <i class="bi bi-trash3"></i>
        </div>
        <h4 class="fw-bold">Delete Student?</h4>
        <p class="text-muted">You are about to delete <b class="text-dark"><?= htmlentities($row['name']) ?></b><br>
          <small><?= htmlentities($row['email']) ?></small></p>
        <p class="small text-danger">This action can not be undone.</p>
        <form method="post" onsubmit="return confirm('Are you sure? This can not be undone.');" class="d-flex gap-2 justify-content-center">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <a href="view_students.php" class="btn btn-light px-4">Cancel</a>
          <button type="submit" name="delete" value="Delete" class="btn btn-danger px-4"><i class="bi bi-trash3"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once "footer.php"; ?>
