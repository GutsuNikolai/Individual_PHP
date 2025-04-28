<?php
session_start();

$templatePath = __DIR__ . "/$template.php";  // Указываем путь к шаблону
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Блог</title>
</head>
<body>
<header>
    <h1>Добро пожаловать в блог!</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Вы вошли как: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
        <a href="/logout.php">Выйти</a>
        
        <!-- Форма поиска по постам -->
        <form action="/index.php" method="GET">
            <div>
                <label for="category_id">Категория:</label>
                <select id="category_id" name="category_id">
                    <option value="all">Все</option>
                    <?php
                        // Получаем категории из базы данных
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
        <a href="/login.php">Войти</a> | <a href="/register.php">Регистрация</a>
    <?php endif; ?>
    <hr>

    <?php
    if (file_exists($templatePath)) {
        include $templatePath;  // Если шаблон существует, подключаем его
    } else {
        echo "Шаблон '$template' не найден.";  // Если шаблон не найден, выводим сообщение
    }
    ?>
</header>

<main>
    
</main>

</body>
</html>
