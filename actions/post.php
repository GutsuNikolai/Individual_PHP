<?php
// Сохраняю id поста и сам пост для дальнейщих действий
require_once __DIR__ . '/../src/handlers/posts/get_post_handler.php';

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    die('Пост не найден.');
}

$post = get_post($post_id); 

$template = 'posts/post'; 
require_once __DIR__ . '/../templates/layout.php'; 
