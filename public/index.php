<?php

require_once __DIR__ . '/../src/handlers/posts/get_post_handler.php';

$category_id = $_GET['category_id'] ?? 'all';  // Получаем категорию (по умолчанию - все)
$tags_input = $_GET['tags'] ?? '';  // Получаем теги (по умолчанию - пусто)

$posts = get_posts($category_id, $tags_input);  // Получаем посты с фильтрацией

$template = 'posts/list';  // Указываем шаблон для отображения отфильтрованных постов
require_once __DIR__ . '/../templates/layout.php';  // Загружаем основной макет
