<?php

require_once __DIR__ . '/../src/handlers/posts/delete_post_handler.php';

// Получаем ID поста из URL
$post_id = $_GET['id'] ?? null;

if ($post_id === null) {
    // Если ID не передан, выводим ошибку
    echo "Ошибка: ID поста не передан.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Если это POST-запрос — удаляем пост
    if (isset($_POST['confirm_delete'])) {
        handle_delete_post($post_id); // Удаляем пост
        header('Location: /index.php'); // Перенаправляем на главную после удаления
        exit;
    } else {
        // Если отменили — перенаправляем обратно
        header('Location: /index.php');
        exit;
    }
}

?>

<h1>Вы уверены, что хотите удалить этот пост?</h1>

<form action="" method="POST">
    <button type="submit" name="confirm_delete" value="yes">Да, удалить</button>
    <a href="/index.php">Отмена</a>
</form>
