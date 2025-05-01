<?php

require_once __DIR__ . '/../../db.php';

function handle_create_post(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    session_start();
    $published_at = date('Y-m-d');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = $_POST['category_id'] ?? null;
    $tags_input = trim($_POST['tags'] ?? '');
    $author_id = $_SESSION['user_id'] ?? null;

    if ($title === '' || $description === '' || $published_at === '') {
        return 'Заполните все обязательные поля.';
    }

    if (!$author_id) {
        return 'Ошибка: пользователь не авторизован.';
    }

    //  Обработка загрузки картинки
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../../public/uploads/'; // Место куда загружается фото при создаии поста
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Задаю уникальное имя и получаю путь к этому изображению
        $original_name = basename($_FILES['image']['name']);
        $unique_name = uniqid() . '-' . $original_name;
        $target_path = $upload_dir . $unique_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = '/uploads/' . $unique_name; // Путь к изображению при отображении поста
        }
    }

    $pdo = getDbConnection();
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

    $post_id = $pdo->lastInsertId();

    // Сохраняю тэги для постов
    if ($tags_input) {
        $tags = array_map('trim', explode(',', $tags_input)); // Получаю тэги
        foreach ($tags as $tag) { // Погдготавливаю
            $stmt = $pdo->prepare('
                INSERT INTO tags (name)
                VALUES (:name)
                ON CONFLICT (name) DO NOTHING
            ');
            $stmt->execute(['name' => $tag]); // Сохраняю


            // Сохраняю в связующую таблицу связи тэгов и постов
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

