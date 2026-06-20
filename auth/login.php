<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect(BASE_URL . '/index.php');
}

$errors      = [];
$old_identity = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';
    $old_identity = $identity;

    if ($identity === '' || $password === '') {
        $errors[] = 'لطفاً نام کاربری/ایمیل و رمز عبور را وارد کنید.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$identity, $identity]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'نام کاربری/ایمیل یا رمز عبور اشتباه است.';
        } else {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            set_flash('success', 'خوش آمدید، ' . $user['username'] . '!');
            redirect(BASE_URL . '/index.php');
        }
    }
}

$page_title = 'ورود';
require __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">ورود به حساب کاربری</h1>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="auth-form" novalidate>
            <label for="identity">نام کاربری یا ایمیل</label>
            <input type="text" id="identity" name="identity" value="<?php echo e($old_identity); ?>" required>

            <label for="password">رمز عبور</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn btn-primary btn-block">ورود</button>
        </form>

        <p class="auth-switch">حساب کاربری ندارید؟ <a href="<?php echo BASE_URL; ?>/auth/register.php">ثبت‌نام کنید</a></p>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
