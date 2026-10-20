<?php
session_start();
// نحذف كل متغيرات الجلسة وننهيها (الفصل السابع)
session_unset();
session_destroy();

session_start();
$_SESSION['success'] = "You have been logged out";
header("Location: login.php");
return;
