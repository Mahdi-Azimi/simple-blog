<?php
// ===== تنظیمات اتصال به دیتابیس (phpMyAdmin / MySQL لوکال) =====
// این مقادیر را مطابق تنظیمات سرور لوکال خودتان (XAMPP/Laragon و ...) ویرایش کنید
define('DB_HOST', 'localhost');
define('DB_NAME', 'simple_blog');
define('DB_USER', 'root');
define('DB_PASS', '');

// اگر نام پوشه پروژه روی سرور را عوض کردید، این مقدار را هم همان‌طور تغییر دهید
define('BASE_URL', '/simple-blog');

define('SITE_NAME', 'دفترچه');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('اتصال به دیتابیس برقرار نشد. تنظیمات config/db.php و سرویس MySQL را بررسی کنید. (' . $e->getMessage() . ')');
}
