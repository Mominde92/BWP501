<?php
require_once "pdo.php";
require_once "session.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password2'];

    if (strlen($username) < 1 || strlen($email) < 1 || strlen($password) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: register.php");
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: register.php");
        return;
    }
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters";
        header("Location: register.php");
        return;
    }
    if ($password != $password_confirm) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: register.php");
        return;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute(array(':username' => $username, ':email' => $email));
    if ($stmt->fetch(PDO::FETCH_ASSOC) !== false) {
        $_SESSION['error'] = "Username or email already exists";
        header("Location: register.php");
        return;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $password_hash));

    $_SESSION['success'] = "Account created, you can login now";
    header("Location: login.php");
    return;
}

$title = "Register";
require_once "header.php";
?>
<div class="auth-card">
    <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
    <h3>Create Account</h3>
    <p class="sub">Register to start managing students</p>
    <form method="post" id="registerForm">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="your username" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="min 6 chars" required>
            </div>
            <div class="col-sm-6 mb-3">
                <label class="form-label">Confirm</label>
                <input type="password" name="password2" id="password2" class="form-control" placeholder="repeat" required>
            </div>
        </div>
        <span class="error-text mb-2" id="passError"></span>
        <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
        <p class="text-center mt-3 mb-0 small">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>
<?php require_once "footer.php"; ?>
