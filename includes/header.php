<?php
$current_user = current_user();
$flashes      = get_flashes();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? e($page_title) . ' | ' . e(SITE_NAME) : e(SITE_NAME); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo BASE_URL; ?>/index.php" class="logo">
            <span class="logo-mark">د</span><?php echo e(SITE_NAME); ?>
        </a>
        <nav class="main-nav">
            <?php if ($current_user): ?>
                <?php if ($current_user['role'] === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/add_post.php" class="nav-link nav-link--accent">+ نوشتهٔ جدید</a>
                <?php endif; ?>
                <span class="nav-user">
                    <span class="nav-user-avatar" style="background-color: <?php echo e(avatar_color($current_user['username'])); ?>">
                        <?php echo e(initials($current_user['username'])); ?>
                    </span>
                    <?php echo e($current_user['username']); ?>
                </span>
                <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="nav-link">خروج</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login.php" class="nav-link">ورود</a>
                <a href="<?php echo BASE_URL; ?>/auth/register.php" class="nav-link nav-link--accent">ثبت‌نام</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php if (!empty($flashes)): ?>
    <div class="flash-wrap">
        <?php foreach ($flashes as $flash): ?>
            <div class="flash flash--<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<main class="page-main">
