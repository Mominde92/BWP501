<?php
// دالة التحقق من بيانات الطالب
// نستخدمها بمكانين: validate.php (للـ AJAX) و add/update (تحقق من جهة المخدم كمان)
function validate_student($pdo, $data, $id = 0)
{
    $errors = array();

    $name    = isset($data['name'])    ? trim($data['name'])    : "";
    $email   = isset($data['email'])   ? trim($data['email'])   : "";
    $phone   = isset($data['phone'])   ? trim($data['phone'])   : "";
    $address = isset($data['address']) ? trim($data['address']) : "";

    if ($name == "") {
        $errors['name'] = "Name is required";
    } else if (!preg_match("/^[a-zA-Z\x{0600}-\x{06FF} ]*$/u", $name)) {
        $errors['name'] = "Only letters and white space allowed";
    }

    if ($email == "") {
        $errors['email'] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        // نتأكد انو الايميل مو مسجل لطالب تاني
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :e AND id != :id");
        $stmt->execute(array(':e' => $email, ':id' => $id));
        if ($stmt->fetch(PDO::FETCH_ASSOC) !== false) {
            $errors['email'] = "This email is already used by another student";
        }
    }

    if ($phone == "") {
        $errors['phone'] = "Phone is required";
    } else if (!preg_match("/^[0-9+]{7,15}$/", $phone)) {
        $errors['phone'] = "Phone must be 7-15 digits";
    }

    if ($address == "") {
        $errors['address'] = "Address is required";
    }

    return $errors;
}
