-- قاعدة بيانات مشروع BWP501
-- نفذ هذا الملف من phpMyAdmin (تبويب SQL) او Import



-- جدول المستخدمين (لتسجيل الدخول)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول الطلاب
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(15) NOT NULL,
  address TEXT,
  profile_picture VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- بيانات تجريبية
INSERT INTO students (name, email, phone, address) VALUES
('Ahmad Khaled', 'ahmad@test.com', '0933111222', 'Damascus - Mazzeh'),
('Sara Haddad', 'sara@test.com', '0944555666', 'Aleppo'),
('Omar Nasser', 'omar@test.com', '0955777888', 'Homs'),
('Lina Saleh', 'lina@test.com', '0966123456', 'Latakia');
