# BWP501 - Student Registration System

مشروع مادة برمجة الويب (2) - نظام تسجيل الطلاب باستخدام HTML, CSS, JavaScript, Bootstrap 5, PHP, MySQL.

**رابط الاستضافة:** https://moumen-dev.com/BWP501_HW

---

## 1. طريقة التشغيل على XAMPP

1. انسخ مجلد المشروع إلى `C:\xampp\htdocs\BWP501_Project`
2. شغل Apache و MySQL من XAMPP Control Panel
3. افتح `http://localhost/phpmyadmin` ثم من تبويب **Import** اختر الملف `database.sql` واضغط Go
   (الملف بينشئ قاعدة البيانات `student_registration_system` والجداول `users` و `students` مع 4 طلاب تجريبيين)
4. إذا كان عندك كلمة سر لـ MySQL عدلها بملف `pdo.php`
5. افتح بالمتصفح: `http://localhost/BWP501_Project/index.php`

> إذا أباتشي شغال على البوابة 8080 (متل ما شرحنا بالفصل الأول) يصير الرابط `http://localhost:8080/BWP501_Project/`

ملاحظة: لازم يكون المجلد `uploads` قابل للكتابة لأنو الصور بتنحفظ فيه.

## 2. طريقة الاستخدام

1. **Register**: إنشاء حساب جديد (username, email, password + تأكيد كلمة السر)
2. **Login**: الدخول بالاسم أو بالإيميل
3. **Add Student**: إضافة طالب مع صورة اختيارية، الأخطاء بتظهر تحت كل حقل بدون تحديث الصفحة
4. **View Students**: جدول الطلاب، فيه بحث وترتيب (بالضغط على اسم العمود) و 10 طلاب بكل صفحة
5. **Edit**: تعديل بيانات الطالب أو تغيير صورته
6. **Delete**: صفحة تأكيد + رسالة confirm قبل الحذف
7. **Logout**: إنهاء الجلسة

## 3. الملفات

| الملف | الوظيفة |
|---|---|
| `database.sql` | إنشاء قاعدة البيانات والجداول |
| `pdo.php` | الاتصال مع قاعدة البيانات |
| `header.php` / `footer.php` | القائمة العلوية + رسائل الخطأ والنجاح + الذيل |
| `check_login.php` | حماية الصفحات (يرجع لصفحة الدخول إذا ما في جلسة) |
| `index.php` | الصفحة الرئيسية |
| `register.php` / `login.php` / `logout.php` | تسجيل المستخدمين والدخول والخروج |
| `add_student.php` | إضافة طالب |
| `view_students.php` | عرض + بحث + ترتيب + تقسيم صفحات |
| `update_student.php` | تعديل طالب |
| `delete_student.php` | حذف طالب |
| `student_validation.php` | دالة التحقق من بيانات الطالب |
| `validate.php` | صفحة يستدعيها الـ AJAX وترجع الأخطاء JSON |
| `upload_image.php` | دالة رفع الصورة |
| `js/script.js` | كود AJAX ومعاينة الصورة وتحقق كلمة السر |
| `css/style.css` | تنسيقات إضافية فوق Bootstrap |

## 4. شرح طريقة عمل الميزات

### تسجيل الدخول والجلسات
- عند التسجيل منشفر كلمة السر بـ `password_hash()` قبل ما نخزنها، فبالجدول ما بتنحفظ كلمة السر نفسها.
- عند الدخول منجيب المستخدم بالاسم أو الإيميل ومنقارن بـ `password_verify()`.
- إذا نجح منخزن `user_id` و `username` بـ `$_SESSION`، وكل صفحة محمية بتضمن `check_login.php` يلي بيفحص وجود `user_id`.
- `logout.php` بيستخدم `session_unset()` و `session_destroy()`.
- الرسائل (نجاح/خطأ) بنفس طريقة Flash pattern بالفصل الثامن: منخزن الرسالة بالجلسة ومنعمل redirect، والصفحة التانية بتعرضها وبتحذفها.

### الحماية من SQL Injection و XSS
- كل الاستعلامات بـ `prepare()` و `execute()` (التعليمات المعدّة) فما في أي قيمة من المستخدم بتنحط مباشرة بالـ SQL.
- أسماء أعمدة الترتيب منفحصها بـ `in_array` مع قائمة مسموحة لأنو ما فينا نربطها كمعامل.
- كل شي منعرضه من قاعدة البيانات بيمر على `htmlentities()`.

### التحقق بالـ AJAX
- بـ `js/script.js` لما المستخدم يطلع من أي حقل (blur) أو يضغط Add، منبعت بيانات الفورم بـ `XMLHttpRequest` (POST) لصفحة `validate.php`.
- `validate.php` بتستدعي `validate_student()` وبترجع الأخطاء بـ `json_encode`، مثلاً:
  `{"email":"Invalid email format"}`
- الجافاسكربت بتعمل `JSON.parse` وبتكتب كل خطأ تحت حقله وبتلون الحقل أحمر/أخضر.
- إذا ما في أخطاء `{}` منعمل `submit()` للفورم.
- نفس الدالة بتنفذ كمان بالسيرفر داخل `add_student.php` و `update_student.php` لو حدا عطّل الجافاسكربت.
- كمان منتأكد انو إيميل الطالب مو مكرر.

### رفع الصور
- الفورم لازم يكون `enctype="multipart/form-data"`.
- دالة `upload_image()` (معدلة من مثال الفصل 11): بتفحص انو الملف صورة بـ `getimagesize()`، وانو الحجم أقل من 2MB، وانو اللاحقة jpg/jpeg/png/gif (بـ `pathinfo`).
- منعطي الصورة اسم جديد (`student_وقت_رقم.ext`) مشان ما تنكتب صورة فوق صورة، ومننقلها بـ `move_uploaded_file()` لمجلد `uploads/`.
- بقاعدة البيانات منخزن المسار فقط بعمود `profile_picture`.
- عند التعديل بصورة جديدة أو حذف الطالب منحذف الصورة القديمة بـ `unlink()`.

### البحث والتقسيم لصفحات والترتيب
- البحث بـ GET (`?q=...`) وبيستخدم `LIKE '%q%'` على الاسم والإيميل والهاتف.
- أول شي منحسب `COUNT(*)` لنعرف عدد الصفحات = `ceil(total / 10)`.
- بعدين `LIMIT 10 OFFSET (page-1)*10`، و LIMIT/OFFSET مربوطين بـ `bindValue` كـ `PDO::PARAM_INT`.
- روابط Previous / Next وأرقام الصفحات بتحافظ على كلمة البحث والترتيب (`http_build_query`).

### التصميم المتجاوب
- Bootstrap 5 من CDN: navbar بينطوي على الموبايل، grid (`row`/`col-md-*`)، والجدول داخل `table-responsive`.

## 5. الرفع على الاستضافة
1. ارفع الملفات على الاستضافة (مثلاً InfinityFree أو 000webhost) بمجلد `htdocs`.
2. أنشئ قاعدة بيانات من لوحة التحكم واعمل Import لـ `database.sql` (احذف أول سطرين CREATE DATABASE و USE إذا الاستضافة ما بتسمح).
3. عدّل `pdo.php` ببيانات الاستضافة (host, dbname, username, password).
