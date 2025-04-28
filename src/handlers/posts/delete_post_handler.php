<?php

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
