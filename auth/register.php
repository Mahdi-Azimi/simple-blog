<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect(BASE_URL . '/index.php');
}

$errors = [];
$old    = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $old['username'] = $username;
    $old['email']    = $email;

    if ($username === '' || mb_strlen($username) < 3) {
        $errors[] = 'نام کاربری باید حداقل ۳ کاراکتر باشد.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'ایمیل وارد شده معتبر نیست.';
    }
    if (mb_strlen($password) < 6) {
        $errors[] = 'رمز عبور باید حداقل ۶ کاراکتر باشد.';
    }
    if ($password !== $confirm) {
        $errors[] = 'رمز عبور و تکرار آن یکسان نیستند.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'این نام کاربری یا ایمیل قبلاً ثبت شده است.';
        }
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $hash, 'user']);

        set_flash('success', 'ثبت‌نام با موفقیت انجام شد. حالا وارد شوید.');
        redirect(BASE_URL . '/auth/login.php');
    }
}

$page_title = 'ثبت‌نام';
require __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">ساخت حساب کاربری</h1>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="auth-form" novalidate>
            <label for="username">نام کاربری</label>
            <input type="text" id="username" name="username" value="<?php echo e($old['username']); ?>" required>

            <label for="email">ایمیل</label>
            <input type="email" id="email" name="email" value="<?php echo e($old['email']); ?>" required>

            <label for="password">رمز عبور</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">تکرار رمز عبور</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" class="btn btn-primary btn-block">ثبت‌نام</button>
        </form>

        <p class="auth-switch">حساب کاربری دارید؟ <a href="<?php echo BASE_URL; ?>/auth/login.php">وارد شوید</a></p>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
