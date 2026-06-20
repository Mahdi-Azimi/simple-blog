<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$page_title = 'خانه';

$stmt  = $pdo->query("SELECT posts.*, users.username, users.role FROM posts JOIN users ON users.id = posts.user_id ORDER BY posts.created_at DESC");
$posts = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="feed-head">
    <h1>آخرین نوشته‌ها</h1>
</div>

<?php if (empty($posts)): ?>
    <div class="empty-state">
        <p>هنوز نوشته‌ای منتشر نشده است.</p>
        <?php if (is_admin()): ?>
            <a href="<?php echo BASE_URL; ?>/admin/add_post.php" class="btn btn-primary">نوشتن اولین پست</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="post-feed">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
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

                <h2 class="post-title">
                    <a href="<?php echo BASE_URL; ?>/post.php?id=<?php echo (int) $post['id']; ?>"><?php echo e($post['title']); ?></a>
                </h2>
                <p class="post-excerpt">
                    <?php
                    $excerpt = mb_substr($post['content'], 0, 220);
                    echo e($excerpt) . (mb_strlen($post['content']) > 220 ? '…' : '');
                    ?>
                </p>
                <a href="<?php echo BASE_URL; ?>/post.php?id=<?php echo (int) $post['id']; ?>" class="read-more">ادامه مطلب ←</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
