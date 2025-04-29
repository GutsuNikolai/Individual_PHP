<?php

require_once __DIR__ . '/../../db.php';

session_start();


function get_posts($category_id = 'all', $tags = ''): array
{
    $pdo = getDbConnection();

    // Подготавливаем условия для тегов
    $tagConditions = [];
    $tagParams = [];

    if (!empty($tags)) {
        // Разбиваем введённые теги по запятой и убираем пробелы
        $tags = array_map('trim', explode(',', $tags));

        // Строим условие для поиска по тегам
        foreach ($tags as $index => $tag) {
            $tagConditions[] = "t.name LIKE :tag$index";
            $tagParams[":tag$index"] = "%$tag%";
        }
    }

    // Строим основной запрос
    $sql = 'SELECT p.id, p.title, p.description, p.published_at,
                   STRING_AGG(t.name, \',\' ORDER BY t.name ASC) AS tags
            FROM posts p
            LEFT JOIN post_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON pt.tag_id = t.id';

    // Добавляем условия для категории
    if ($category_id != 'all') {
        $sql .= ' WHERE p.category_id = :category_id';
    }

    // Добавляем условия для тегов
    if (!empty($tagConditions)) {
        $sql .= $category_id != 'all' ? ' AND ' : ' WHERE ';
        $sql .= implode(' OR ', $tagConditions);
    }

    // Группировка по полям, которые не являются агрегатами
    $sql .= ' GROUP BY p.id, p.title, p.description, p.published_at';

    // Логирование SQL-запроса и параметров
    //file_put_contents('query_log.txt', "SQL Query: $sql\n", FILE_APPEND);
    //file_put_contents('query_log.txt', "Params: " . json_encode($tagParams) . "\n", FILE_APPEND);

    // Выполняем запрос
    $stmt = $pdo->prepare($sql);

    // Добавляем параметры для категории
    if ($category_id != 'all') {
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }

    // Добавляем параметры для тегов
    foreach ($tagParams as $tagParam => $tagValue) {
        $stmt->bindValue($tagParam, $tagValue, PDO::PARAM_STR);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_post(int $post_id): array
{
    $pdo = getDbConnection();

    $stmt = $pdo->prepare('
        SELECT id, title, description, image_path, published_at
        FROM posts
        WHERE id = :id
    ');
    $stmt->execute(['id' => $post_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}