<?php
require_once "pdo.php";
require_once "check_login.php";
require_once "student_validation.php";
require_once "upload_image.php";

if (isset($_POST['name']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $errors = validate_student($pdo, $_POST, $id);
    if (count($errors) > 0) {
        $_SESSION['error'] = implode(" - ", $errors);
        header("Location: update_student.php?id=" . $id);
        return;
    }

    $stmt = $pdo->prepare("SELECT profile_picture FROM students WHERE id = :id");
    $stmt->execute(array(':id' => $id));
    $old = $stmt->fetch(PDO::FETCH_ASSOC);
    $picture = ($old !== false) ? $old['profile_picture'] : null;

    if (isset($_FILES['picture']) && $_FILES['picture']['name'] != "") {
        $result = upload_image($_FILES['picture']);
        if (!$result['ok']) {
            $_SESSION['error'] = $result['msg'];
            header("Location: update_student.php?id=" . $id);
            return;
        }
        if ($picture != "" && file_exists($picture)) {
            unlink($picture);
        }
        $picture = $result['path'];
    }

    $sql = "UPDATE students SET name = :name, email = :email, phone = :phone,
            address = :address, profile_picture = :pic
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name'    => trim($_POST['name']),
        ':email'   => trim($_POST['email']),
        ':phone'   => trim($_POST['phone']),
        ':address' => trim($_POST['address']),
        ':pic'     => $picture,
        ':id'      => $id
    ));

    $_SESSION['success'] = "Student updated";
    header("Location: view_students.php");
    return;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Missing student id";
    header("Location: view_students.php");
    return;
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = "Bad value for student id";
    header("Location: view_students.php");
    return;
}

$n = htmlentities($row['name']);
$e = htmlentities($row['email']);
$p = htmlentities($row['phone']);
$a = htmlentities($row['address']);

$title = "Edit Student";
require_once "header.php";
?>
<?php $has_pic = ($row['profile_picture'] != "" && file_exists($row['profile_picture'])); ?>
<div class="row justify-content-center">
  <div class="col-xl-8">
    <div class="card">
      <div class="card-head">
        <h5><i class="bi bi-pencil-square text-primary me-2"></i>Edit Student #<?= $row['id'] ?></h5>
        <a href="view_students.php" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
      <div class="card-body">
      <form method="post" enctype="multipart/form-data" id="studentForm" novalidate>
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <div class="upload-box mb-4">
          <?php if ($has_pic) { ?>
            <img src="<?= htmlentities($row['profile_picture']) ?>" class="preview-img" id="noPic" alt="current">
          <?php } else { ?>
            <div class="avatar preview-img d-grid fs-2" id="noPic"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
          <?php } ?>
          <img id="preview" class="preview-img d-none" alt="preview">
          <div class="flex-grow-1">
            <label class="form-label mb-1"><?= $has_pic ? "Change Picture" : "Add Picture" ?></label>
            <input type="file" name="picture" id="picture" class="form-control" accept="image/*">
            <small class="text-muted">Leave empty to keep the current picture</small>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="name" class="form-control check" value="<?= $n ?>">
            </div>
            <span class="error-text" id="nameError"></span>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control check" value="<?= $e ?>">
            </div>
            <span class="error-text" id="emailError"></span>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" name="phone" class="form-control check" value="<?= $p ?>">
          </div>
          <span class="error-text" id="phoneError"></span>
        </div>
        <div class="mb-4">
          <label class="form-label">Address</label>
          <textarea name="address" rows="3" class="form-control check"><?= $a ?></textarea>
          <span class="error-text" id="addressError"></span>
        </div>
        <div class="d-flex gap-2 justify-content-end">
          <a href="view_students.php" class="btn btn-light">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Update Student</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
<?php require_once "footer.php"; ?>
