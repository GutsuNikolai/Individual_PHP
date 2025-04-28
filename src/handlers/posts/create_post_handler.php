<?php

require_once __DIR__ . '/../../db.php';

function handle_create_post(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    session_start();
    $published_at = date('Y-m-d'); // Формат: 'ГГГГ-ММ-ДД'
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $category_id = $_POST['category_id'] ?? null;
    $image_path = trim($_POST['image_path'] ?? null);
    $tags_input = trim($_POST['tags'] ?? '');  // Получаем теги из формы
    $author_id = $_SESSION['user_id'] ?? null;

    // Базовая валидация
    if ($title === '' || $description === '' || $published_at === '') {
        return 'Заполните все обязательные поля.';
    }

    if (!$author_id) {
        return 'Ошибка: пользователь не авторизован.';
    }

    $pdo = getDbConnection();

    // Вставляем пост в базу данных
    $stmt = $pdo->prepare('
        INSERT INTO posts (title, description, image_path, published_at, category_id, author_id)
        VALUES (:title, :description, :image_path, :published_at, :category_id, :author_id)
    ');
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'image_path' => $image_path,
        'published_at' => $published_at,
        'category_id' => $category_id !== '' ? $category_id : null,
        'author_id' => $author_id,
    ]);
    $post_id = $pdo->lastInsertId();  // Получаем ID только что добавленного поста

    // Обрабатываем теги (если есть)
    if ($tags_input) {
        $tags = array_map('trim', explode(',', $tags_input));  // Разделяем теги по запятой и убираем пробелы
        foreach ($tags as $tag) {
            // Добавляем тег в таблицу tags (если его еще нет)
            $stmt = $pdo->prepare('
                INSERT INTO tags (name) 
                VALUES (:name) 
                ON CONFLICT (name) DO NOTHING
            ');
            $stmt->execute(['name' => $tag]);

            // Связываем тег с постом
            $stmt = $pdo->prepare('
                INSERT INTO post_tags (post_id, tag_id)
                SELECT :post_id, id FROM tags WHERE name = :name
            ');
            $stmt->execute([
                'post_id' => $post_id,
                'name' => $tag
            ]);
        }
    }

    header('Location: /index.php');
    exit;
}
