<?php
// دالة رفع صورة الطالب - مأخوذة من مثال الفصل الحادي عشر مع تعديلات بسيطة
// ترجع مصفوفة: اذا نجح الرفع ترجع مسار الصورة، واذا فشل ترجع رسالة الخطأ
function upload_image($file)
{
    $target_dir = "uploads/";

    // تحقق من وجود خطأ اثناء الرفع
    if ($file['error'] != 0) {
        return array('ok' => false, 'msg' => "Error while uploading the file");
    }

    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    // نتأكد انو الملف صورة فعلا
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return array('ok' => false, 'msg' => "File is not an image");
    }

    // الحجم الاعظمي 2MB
    if ($file["size"] > 2000000) {
        return array('ok' => false, 'msg' => "Sorry, your file is too large (max 2MB)");
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        return array('ok' => false, 'msg' => "Only JPG, JPEG, PNG & GIF files are allowed");
    }

    // نعطي الصورة اسم جديد مشان ما يتكرر اسم صورتين
    $new_name = "student_" . time() . "_" . rand(100, 999) . "." . $imageFileType;
    $target_file = $target_dir . $new_name;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return array('ok' => true, 'path' => $target_file);
    }
    return array('ok' => false, 'msg' => "Sorry, there was an error uploading your file");
}
