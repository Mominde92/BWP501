<?php
// ملف الاتصال مع قاعدة البيانات (نفس فكرة الفصل الثامن)
// عند الرفع على الاستضافة نغير هذه القيم فقط
$servername = "localhost";
$dbname     = "student_registration_system";
$username   = "root";
$password   = "";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
