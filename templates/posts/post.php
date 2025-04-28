<h1><?= htmlspecialchars($post['title']) ?></h1>
<p><strong>Опубликовано:</strong> <?= htmlspecialchars($post['published_at']) ?></p>
<p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($post['description'])) ?></p>

<?php if ($post['image_path']): ?>
    <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Изображение поста">
<?php endif; ?>
