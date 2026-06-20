<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT posts.*, users.username, users.role FROM posts JOIN users ON users.id = posts.user_id WHERE posts.id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    set_flash('error', 'پستی با این مشخصات پیدا نشد.');
    redirect(BASE_URL . '/index.php');
}

$page_title = $post['title'];
require __DIR__ . '/includes/header.php';
?>

<a href="<?php echo BASE_URL; ?>/index.php" class="back-link">→ بازگشت به فهرست</a>

<article class="post-card post-card--full">
    <div class="post-card-header">
        <span class="avatar" style="background-color: <?php echo e(avatar_color($post['username'])); ?>">
            <?php echo e(initials($post['username'])); ?>
        </span>
        <div class="post-meta">
            <span class="post-author">
                <?php echo e($post['username']); ?>
                <?php if ($post['role'] === 'admin'): ?><span class="badge badge-admin">ادمین</span><?php endif; ?>
            </span>
            <time class="post-date"><?php echo persian_date($post['created_at']); ?></time>
        </div>
        <?php if (is_admin()): ?>
            <div class="post-actions">
                <a href="<?php echo BASE_URL; ?>/admin/edit_post.php?id=<?php echo (int) $post['id']; ?>" class="icon-btn" title="ویرایش">✎</a>
                <form action="<?php echo BASE_URL; ?>/admin/delete_post.php" method="post" class="inline-form" onsubmit="return confirm('این پست برای همیشه حذف می‌شود. مطمئنید؟');">
                    <input type="hidden" name="id" value="<?php echo (int) $post['id']; ?>">
                    <button type="submit" class="icon-btn icon-btn--danger" title="حذف">✕</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <h1 class="post-title post-title--full"><?php echo e($post['title']); ?></h1>
    <div class="post-content"><?php echo nl2br(e($post['content'])); ?></div>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
