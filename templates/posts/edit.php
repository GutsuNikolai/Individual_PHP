<h1>Редактировать пост</h1>

<?php if (!empty($error)): ?>
    <div style="color: red;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="/edit_post.php?id=<?= $post['id'] ?>" method="POST">
    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">   
    <div>
        <label for="title">Заголовок:</label><br>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
    </div>

    <div>
        <label for="description">Описание:</label><br>
        <textarea id="description" name="description" rows="6" required><?= htmlspecialchars($post['description']) ?></textarea>
    </div>

    <div>
        <label for="image_path">Ссылка на картинку:</label><br>
        <input type="text" id="image_path" name="image_path" value="<?= htmlspecialchars($post['image_path']) ?>">
    </div>

    <div>
        <label for="category_id">Категория:</label><br>
        <select id="category_id" name="category_id" required>
            <?php
                $pdo = getDbConnection();
                $stmt = $pdo->query('SELECT id, name FROM categories');
                while ($category = $stmt->fetch()) {
                    $selected = $post['category_id'] == $category['id'] ? 'selected' : '';
                    echo "<option value=\"{$category['id']}\" $selected>{$category['name']}</option>";
                }
            ?>
        </select>
    </div>

    <div>
        <label for="tags">Теги (через запятую):</label><br>
        <input type="text" id="tags" name="tags" value="<?= htmlspecialchars($post['tags']) ?>">

    </div>

    <br>
    <button type="submit">Сохранить изменения</button>
</form>
