<?php

require_once __DIR__ . '/../../db.php';

/**
 * Получает список публикаций с возможностью фильтрации по категории и тегам
 *
 * Возвращает массив публикаций с агрегированными тегами. Поддерживает фильтрацию:
 * - По категории (если передано число вместо 'all')
 * - По тегам (частичное совпадение через LIKE для каждого тега)
 *
 * @param string|int $category_id ID категории или 'all' для всех категорий
 * @param string $tags Строка тегов через запятую для фильтрации
 * @return array Массив публикаций, каждая с полями:
 *   - id (int)
 *   - title (string)
 *   - description (string)
 *   - published_at (string)
 *   - tags (string) - строка тегов через запятую
 * @throws PDOException Если произошла ошибка при работе с базой данных
 */
function get_posts(int|string $category_id = 'all', $tags = ''): array
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

    // Добавляю условия для тегов
    if (!empty($tagConditions)) {
        $sql .= $category_id != 'all' ? ' AND ' : ' WHERE ';
        $sql .= implode(' OR ', $tagConditions);
    }

    // Группировка по полям, которые не являются агрегатами
    $sql .= ' GROUP BY p.id, p.title, p.description, p.published_at';

    $stmt = $pdo->prepare($sql);

    // Добавляю параметры для категории
    if ($category_id != 'all') {
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }

    // Добавляю параметры для тегов
    foreach ($tagParams as $tagParam => $tagValue) {
        $stmt->bindValue($tagParam, $tagValue, PDO::PARAM_STR);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получает данные конкретной публикации по её ID
 *
 * @param int $post_id ID запрашиваемой публикации
 * @return array|null Ассоциативный массив с данными публикации или null, если не найдена:
 *   - id (int)
 *   - title (string)
 *   - description (string)
 *   - image_path (string|null)
 *   - published_at (string)
 * @throws PDOException Если произошла ошибка при работе с базой данных
 */
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