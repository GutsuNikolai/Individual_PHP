<?php
// Ну що сказать, просто layout с динамичским отображеним шаблонов. Думаю тут все интуитивно понятно
$templatePath = __DIR__ . "/$template.php";  
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Блог</title>
</head>
<body>
<header>
    <h1><a href="/?page=home">Добро пожаловать в блог!</a></h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Вы вошли как: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
        <a href="/index.php?page=logout">Выйти</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/index.php?page=create_post"></br>Создать пост</a>
        <?php endif; ?>
        
        <!-- Форма поиска по постам -->
        <form action="/index.php?page=search" method="GET">
            <div>
                <label for="category_id">Поиск по:</br>Категория:</label>
                <select id="category_id" name="category_id">
                    <option value="all">Все</option>
                    <?php
                        // Получаю категории из базы данных
                        $pdo = getDbConnection();
                        $stmt = $pdo->query('SELECT id, name FROM categories');
                        while ($category = $stmt->fetch()) {
                            echo "<option value=\"{$category['id']}\">{$category['name']}</option>";
                        }
                    ?>
                </select>
            </div>

            <div>
                <label for="tags">Теги:</label>
                <input type="text" id="tags" name="tags" placeholder="тег1, тег2, тег3">
            </div>

            <button type="submit">Поиск</button>
        </form>
    
    <?php else: ?>
        <a href="/index.php?page=login">Войти</a> | <a href="/index.php?page=register">Регистрация</a>
    <?php endif; ?>
    <hr>

    <?php
    if (file_exists($templatePath)) {
        include $templatePath;  // Подключение шаблона, если таков пришел
    } else {
        echo "Шаблон '$template' не найден."; 
    }
    ?>
    
</header>
<main>
</main>
</body>
</html>
