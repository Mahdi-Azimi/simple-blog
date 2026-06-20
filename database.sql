-- =========================================================
-- دفترچه — اسکریپت ساخت دیتابیس
-- این فایل را در phpMyAdmin، تب Import وارد کنید
-- =========================================================

CREATE DATABASE IF NOT EXISTS simple_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simple_blog;

-- جدول کاربران
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول پست‌ها
CREATE TABLE IF NOT EXISTS posts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    title      VARCHAR(255) NOT NULL,
    content    TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- یک حساب ادمین آماده برای شروع
-- نام کاربری: admin
-- رمز عبور:   Admin@123
-- (حتماً بعد از اولین ورود رمز را عوض کنید یا کاربر جدید بسازید)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2b$12$ElcnrKhnGGqIlkFsbbspKOn1VsPeOaJ7w4XGXemCYRnSGlew9fo12', 'admin');
