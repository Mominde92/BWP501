<?php
// هذه الصفحة تستدعى بالـ AJAX من ملف js/script.js
// تستقبل بيانات الفورم وترجع الاخطاء بصيغة JSON (الفصل التاسع والعاشر)
require_once "pdo.php";
require_once "student_validation.php";
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('login' => 'Not logged in'));
    return;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$errors = validate_student($pdo, $_POST, $id);

// اذا المصفوفة فاضية بترجع {} بدل []
echo json_encode((object)$errors);
