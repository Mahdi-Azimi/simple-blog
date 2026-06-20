<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$errors = [];
$old    = ['title' => '', 'content' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    $old['title']   = $title;
    $old['content'] = $content;

    if ($title === '' || mb_strlen($title) < 3) {
        $errors[] = 'عنوان باید حداقل ۳ کاراکتر باشد.';
    }
    if ($content === '' || mb_strlen($content) < 10) {
        $errors[] = 'متن پست باید حداقل ۱۰ کاراکتر باشد.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $title, $content]);

        set_flash('success', 'پست با موفقیت منتشر شد.');
        redirect(BASE_URL . '/index.php');
    }
}

$page_title = 'نوشتهٔ جدید';
require __DIR__ . '/../includes/header.php';
?>

<a href="<?php echo BASE_URL; ?>/index.php" class="back-link">→ بازگشت به فهرست</a>

<div class="editor-card">
    <h1>نوشتن پست جدید</h1>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="editor-form" novalidate>
        <label for="title">عنوان</label>
        <input type="text" id="title" name="title" value="<?php echo e($old['title']); ?>" required>

        <label for="content">متن پست</label>
        <textarea id="content" name="content" required><?php echo e($old['content']); ?></textarea>

        <div class="editor-actions">
            <button type="submit" class="btn btn-primary">انتشار پست</button>
            <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-outline">انصراف</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
