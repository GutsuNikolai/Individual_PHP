<?php

require_once __DIR__ . '/../src/handlers/posts/get_post_handler.php';

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    die('Пост не найден.');
}

$post = get_post($post_id); // Получаем пост по ID

$template = 'posts/post'; // Указываем шаблон для отображения одного поста
require_once __DIR__ . '/../templates/layout.php'; // Загружаем основной макет
