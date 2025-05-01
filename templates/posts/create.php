<h1>Создать новый пост</h1>

<?php if (!empty($error)): ?>
    <div style="color: red;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="/index.php?page=create_post" method="POST" enctype="multipart/form-data">
    <div>
        <label for="title">Заголовок:</label><br>
        <input type="text" id="title" name="title" required>
    </div>

    <div>
        <label for="description">Описание:</label><br>
        <textarea id="description" name="description" rows="6" required></textarea>
    </div>

    <div>
        <label for="image">Загрузить изображение:</label>
        <input type="file" name="image" id="image">
    </div>

    <div>
        <label for="category_id">Категория:</label><br>
        <select id="category_id" name="category_id" required>
            <?php
                // Получаем все категории из базы данных
                $pdo = getDbConnection();
                $stmt = $pdo->query('SELECT id, name FROM categories');
                while ($category = $stmt->fetch()) {
                    echo "<option value=\"{$category['id']}\">{$category['name']}</option>";
                }
            ?>
        </select>
    </div>

    <div>
        <label for="tags">Теги (через запятую):</label><br>
        <input type="text" id="tags" name="tags" placeholder="тег1, тег2, тег3">
    </div>

    <br>
    <button type="submit">Создать пост</button>
</form>
