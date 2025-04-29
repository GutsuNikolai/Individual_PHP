<h1><?= htmlspecialchars($post['title']) ?></h1>
<p><strong>Опубликовано:</strong> <?= htmlspecialchars($post['published_at']) ?></p>
<p><strong>Содержание:</strong> <?= nl2br(htmlspecialchars($post['description'])) ?></p>

<?php if ($post['image_path']): ?>
    <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Изображение поста">
<?php endif; ?>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="/edit_post.php?id=<?= $post['id'] ?>"></br>Редактировать</a>
    <form method="POST" action="/delete_post.php" onsubmit="return confirm('Удалить пост?')">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <button type="submit">Удалить</button>
    </form>
<?php endif; ?>


