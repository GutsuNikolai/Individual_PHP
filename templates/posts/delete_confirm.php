<h1>Вы уверены, что хотите удалить пост?</h1>

<?php if (!empty($error)): ?>
    <div style="color: red;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<p><strong>Название поста:</strong> <?= htmlspecialchars($post['title']) ?></p>

<form action="/delete_post.php?id=<?= htmlspecialchars($post['id']) ?>" method="POST">
    <button type="submit" name="confirm_delete" value="yes">Да, удалить</button>
    <button type="submit" name="confirm_delete" value="no">Отмена</button>
</form>
