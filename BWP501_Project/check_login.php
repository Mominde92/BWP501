<?php
// نضمن هذا الملف بأول كل صفحة محمية
// اذا المستخدم مو مسجل دخول منرجعه لصفحة login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first";
    header("Location: login.php");
    exit(); // return هون ما بتوقف الصفحة يلي ضمّنت الملف، لهيك exit
}
