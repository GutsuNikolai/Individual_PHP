<?php

// require_once __DIR__ . '/db.php';

// Кажется нигде не использую. Хз зачем оно тут

/**
 * Получает список последних публикаций
 *
 * @param int $limit Количество возвращаемых записей (по умолчанию 5)
 * @return array Массив публикаций, отсортированных по дате публикации (новые сначала)
 * @throws PDOException Если произошла ошибка при работе с базой данных
 */
// function get_posts(): array
// {
//     $pdo = getDbConnection();

//     $stmt = $pdo->query('
//         SELECT id, title, description, published_at
//         FROM posts
//         ORDER BY published_at DESC
//         LIMIT 5
//     ');

//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }















// function renderTemplate($template, $data = [])
// {
//     // Извлекаем массив данных в отдельные переменные
//     extract($data);
    
//     // Подключаем шаблон
//     include __DIR__ . "/templates/$template.php";
// }
