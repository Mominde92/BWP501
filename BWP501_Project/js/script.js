// التحقق من فورم الطالب باستخدام AJAX (بدون اعادة تحميل الصفحة)
// نفس طريقة XMLHttpRequest يلي اخدناها بالفصل العاشر

var studentForm = document.getElementById("studentForm");

function showErrors(errors) {
    var fields = ["name", "email", "phone", "address"];
    for (var i = 0; i < fields.length; i++) {
        var span = document.getElementById(fields[i] + "Error");
        var input = document.getElementsByName(fields[i])[0];
        if (errors[fields[i]]) {
            span.innerHTML = errors[fields[i]];
            input.classList.add("is-invalid");
            input.classList.remove("is-valid");
        } else {
            span.innerHTML = "";
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
        }
    }
}

// submitAfter = true يعني اذا ما في اخطاء نبعت الفورم
function validateStudent(submitAfter) {
    var data = new FormData();
    // استخدمنا elements لان studentForm.name بترجع اسم الفورم نفسه مو الحقل
    var f = studentForm.elements;
    data.append("name", f["name"].value);
    data.append("email", f["email"].value);
    data.append("phone", f["phone"].value);
    data.append("address", f["address"].value);
    if (f["id"]) {
        data.append("id", f["id"].value);
    }

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var errors = JSON.parse(this.responseText);
            if (errors.login) {
                window.location = "login.php";
                return;
            }
            showErrors(errors);
            if (submitAfter && Object.keys(errors).length == 0) {
                studentForm.submit();
            }
        }
    };
    xmlhttp.open("POST", "validate.php", true);
    xmlhttp.send(data);
}

if (studentForm) {
    // التحقق اثناء الكتابة
    var inputs = studentForm.querySelectorAll(".check");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("blur", function () {
            validateStudent(false);
        });
    }

    studentForm.addEventListener("submit", function (e) {
        e.preventDefault();
        validateStudent(true);
    });

    // معاينة الصورة قبل الرفع
    var pic = document.getElementById("picture");
    pic.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            var img = document.getElementById("preview");
            img.src = URL.createObjectURL(this.files[0]);
            img.classList.remove("d-none");
            var old = document.getElementById("noPic");
            if (old) old.classList.add("d-none");
        }
    });
}

// التأكد من تطابق كلمتي السر بصفحة التسجيل
var registerForm = document.getElementById("registerForm");
if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
        var p1 = document.getElementById("password").value;
        var p2 = document.getElementById("password2").value;
        if (p1 != p2) {
            e.preventDefault();
            document.getElementById("passError").innerHTML = "Passwords do not match";
        } else if (p1.length < 6) {
            e.preventDefault();
            document.getElementById("passError").innerHTML = "Password must be at least 6 characters";
        }
    });
}
