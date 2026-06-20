<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    set_flash('error', 'پستی با این مشخصات پیدا نشد.');
    redirect(BASE_URL . '/index.php');
}

$errors = [];
$old    = ['title' => $post['title'], 'content' => $post['content']];

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
        $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?');
        $stmt->execute([$title, $content, $id]);

        set_flash('success', 'پست با موفقیت ویرایش شد.');
        redirect(BASE_URL . '/post.php?id=' . $id);
    }
}

$page_title = 'ویرایش پست';
require __DIR__ . '/../includes/header.php';
?>

<a href="<?php echo BASE_URL; ?>/post.php?id=<?php echo $id; ?>" class="back-link">→ بازگشت به پست</a>

<div class="editor-card">
    <h1>ویرایش پست</h1>

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
            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
            <a href="<?php echo BASE_URL; ?>/post.php?id=<?php echo $id; ?>" class="btn btn-outline">انصراف</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
