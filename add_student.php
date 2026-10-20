<?php
require_once "pdo.php";
require_once "check_login.php";
require_once "student_validation.php";
require_once "upload_image.php";

if (isset($_POST['name']) && isset($_POST['email'])) {
    $errors = validate_student($pdo, $_POST);
    if (count($errors) > 0) {
        $_SESSION['error'] = implode(" - ", $errors);
        header("Location: add_student.php");
        return;
    }

    $picture = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['name'] != "") {
        $result = upload_image($_FILES['picture']);
        if (!$result['ok']) {
            $_SESSION['error'] = $result['msg'];
            header("Location: add_student.php");
            return;
        }
        $picture = $result['path'];
    }

    $sql = "INSERT INTO students (name, email, phone, address, profile_picture)
            VALUES (:name, :email, :phone, :address, :pic)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name'    => trim($_POST['name']),
        ':email'   => trim($_POST['email']),
        ':phone'   => trim($_POST['phone']),
        ':address' => trim($_POST['address']),
        ':pic'     => $picture
    ));

    $_SESSION['success'] = "Student added successfully (ID: " . $pdo->lastInsertId() . ")";
    header("Location: view_students.php");
    return;
}

$title = "Add Student";
require_once "header.php";
?>
<div class="row justify-content-center">
  <div class="col-xl-8">
    <div class="card">
      <div class="card-head">
        <h5><i class="bi bi-person-plus text-primary me-2"></i>New Student Information</h5>
        <a href="view_students.php" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
      <div class="card-body">
      <form method="post" enctype="multipart/form-data" id="studentForm" novalidate>
        <div class="upload-box mb-4">
          <div class="avatar preview-img d-grid" id="noPic"><i class="bi bi-camera fs-3"></i></div>
          <img id="preview" class="preview-img d-none" alt="preview">
          <div class="flex-grow-1">
            <label class="form-label mb-1">Profile Picture <small class="text-muted">(optional)</small></label>
            <input type="file" name="picture" id="picture" class="form-control" accept="image/*">
            <small class="text-muted">JPG, PNG or GIF - max 2MB</small>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="name" class="form-control check" placeholder="e.g. Ahmad Khaled">
            </div>
            <span class="error-text" id="nameError"></span>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control check" placeholder="student@example.com">
            </div>
            <span class="error-text" id="emailError"></span>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" name="phone" class="form-control check" placeholder="09xxxxxxxx">
          </div>
          <span class="error-text" id="phoneError"></span>
        </div>
        <div class="mb-4">
          <label class="form-label">Address</label>
          <textarea name="address" rows="3" class="form-control check" placeholder="City - street"></textarea>
          <span class="error-text" id="addressError"></span>
        </div>
        <div class="d-flex gap-2 justify-content-end">
          <a href="view_students.php" class="btn btn-light">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save Student</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
<?php require_once "footer.php"; ?>
