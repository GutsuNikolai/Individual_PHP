<?php
require_once __DIR__ . '/../src/handlers/posts/get_post_handler.php';

// Отображение формы для поиска, по умолчанию на все посты
$category_id = $_GET['category_id'] ?? 'all';  //  по умолчанию - все
$tags_input = $_GET['tags'] ?? '';  // по умолчанию - пусто

$posts = get_posts($category_id, $tags_input);  

return 'posts/list';  
