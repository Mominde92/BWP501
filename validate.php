<?php
require_once "pdo.php";
require_once "student_validation.php";
require_once "session.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('login' => 'Not logged in'));
    return;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$errors = validate_student($pdo, $_POST, $id);

echo json_encode((object)$errors);
