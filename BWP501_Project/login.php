<?php
require_once "pdo.php";
session_start();

// اذا مسجل دخول اصلا ما في داعي يشوف صفحة الدخول
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = trim($_POST['login']);
    $pass  = $_POST['password'];

    if (strlen($login) < 1 || strlen($pass) < 1) {
        $_SESSION['error'] = "Username/email and password are required";
        header("Location: login.php");
        return;
    }

    // المستخدم يقدر يدخل بالاسم او بالايميل
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :l OR email = :l2");
    $stmt->execute(array(':l' => $login, ':l2' => $login));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false && password_verify($pass, $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['success']  = "Welcome back " . $row['username'];
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Incorrect username/email or password";
        header("Location: login.php");
        return;
    }
}

$title = "Login";
require_once "header.php";
?>
<div class="auth-card">
    <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
    <h3>Welcome back</h3>
    <p class="sub">Login to your account</p>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username or Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="login" class="form-control" placeholder="username or email" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="your password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
        <p class="text-center mt-3 mb-0 small">No account? <a href="register.php">Register here</a></p>
    </form>
</div>
<?php require_once "footer.php"; ?>
