<?php


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Доступ запрещён');
}

require_once __DIR__ . '/../../db.php';

function handle_delete_post($post_id)
{
    $pdo = getDbConnection();

    // Удаляем связи с тегами
    $stmt = $pdo->prepare('DELETE FROM post_tags WHERE post_id = :post_id');
    $stmt->execute(['post_id' => $post_id]);

    // Удаляем сам пост
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :post_id');
    $stmt->execute(['post_id' => $post_id]);
}

// Логика удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $post_id = (int) $_POST['id'];
    handle_delete_post($post_id);
    header('Location: /');
    exit;
} else {
    http_response_code(400);
    exit('ID поста не передан');
}