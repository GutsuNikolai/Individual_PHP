<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Доступ запрещён');
}

require_once __DIR__ . '/../../db.php';

function handle_edit_post(?string $post_id): array
{
    $pdo = getDbConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $post_id = $_POST['post_id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image_path = trim($_POST['image_path'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $tags_input = trim($_POST['tags'] ?? '');

        // Базовая валидация
        if ($title === '' || $description === '') {
            return ['Заполните все обязательные поля.', null];
        }

        if (!$post_id) {
            return ['Ошибка: не передан ID поста.', null];
        }

        // Обновляем пост
        $stmt = $pdo->prepare('
            UPDATE posts
            SET title = :title, description = :description, image_path = :image_path, category_id = :category_id
            WHERE id = :id
        ');

        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'image_path' => $image_path,
            'category_id' => $category_id !== '' ? $category_id : null,
            'id' => $post_id,
        ]);

        // Обновляем теги (очистить старые и добавить новые)
        $pdo->prepare('DELETE FROM post_tags WHERE post_id = :post_id')->execute(['post_id' => $post_id]);

        if ($tags_input) {
            $tags = array_map('trim', explode(',', $tags_input));
            foreach ($tags as $tag) {
                // Добавляем тег, если его нет
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
                    'name' => $tag,
                ]);
            }
        }

        // После обновления - переадресуем на главную
        header('Location: /index.php');
        exit;
    }

    // Если это GET-запрос — показываем форму
    if (!$post_id) {
        return ['Ошибка: ID поста не передан.', null];
    }

    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
    $stmt->execute(['id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    
    // ⛔ Сразу проверяем, найден ли пост
    if (!$post) {
        return ['Ошибка: Пост не найден.', null];
    }

    // ✅ Если пост найден — только тогда идём за тегами
    $stmt = $pdo->prepare('
        SELECT t.name
        FROM tags t
        INNER JOIN post_tags pt ON pt.tag_id = t.id
        WHERE pt.post_id = :post_id
    ');
    $stmt->execute(['post_id' => $post_id]);
    $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Склеиваем в строку через запятую
    $post['tags'] = implode(', ', $tags);

    return [null, $post];
}
