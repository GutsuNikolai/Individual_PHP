
<h2>Редактирование комментария</h2>

<form method="POST" action="/index.php?page=edit_comment&id=<?= $commentIdStr ?>&post_id=<?= $postId ?>">
    <textarea name="content"><?= htmlspecialchars($content) ?></textarea></br>
    <input type="hidden" name="post_id" value="<?= $postId ?>">
    <button type="submit">Сохранить изменения</button>
</form>
