<?php
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function is_admin(): bool
{
    return is_logged_in() && ($_SESSION['role'] ?? '') === 'admin';
}

function current_user(): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'role'     => $_SESSION['role'],
    ];
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect(BASE_URL . '/auth/login.php');
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        set_flash('error', 'شما به این بخش دسترسی ندارید.');
        redirect(BASE_URL . '/index.php');
    }
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function to_persian_digits(string $string): string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return str_replace(range(0, 9), $persian, $string);
}

function persian_date(string $datetime): string
{
    $ts = strtotime($datetime);

    if (class_exists('IntlDateFormatter')) {
        $fmt = new IntlDateFormatter('fa_IR@calendar=persian', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Tehran');
        $formatted = $fmt->format($ts);
        if ($formatted !== false) {
            return $formatted;
        }
    }

    $months = ['ژانویه', 'فوریه', 'مارس', 'آوریل', 'مه', 'ژوئن', 'ژوئیه', 'اوت', 'سپتامبر', 'اکتبر', 'نوامبر', 'دسامبر'];
    $day    = (int) date('j', $ts);
    $month  = (int) date('n', $ts);
    $year   = date('Y', $ts);

    return to_persian_digits($day . ' ' . $months[$month - 1] . ' ' . $year);
}

// رنگ ثابت و یکتا برای آواتار هر کاربر، بر اساس نام کاربری
function avatar_color(string $seed): string
{
    $palette = ['#1E6F5C', '#B6651D', '#3A5BA0', '#8E4585', '#2F7A4F', '#A6444C'];
    $hash    = crc32($seed);
    return $palette[$hash % count($palette)];
}

function initials(string $name): string
{
    $name = trim($name);
    if ($name === '') {
        return '?';
    }
    $firstChar = mb_substr($name, 0, 1, 'UTF-8');
    return mb_strtoupper($firstChar, 'UTF-8');
}
