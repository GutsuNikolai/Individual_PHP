<?php
session_start();

$page = $_GET['page'] ?? 'home';

$routes = [
    'create_post' => __DIR__ . '/../actions/create_post.php',
    'delete_post' => __DIR__ . '/../actions/delete_post.php',
    'edit_post'   => __DIR__ . '/../actions/edit_post.php',
    'login'       => __DIR__ . '/../actions/login.php',
    'logout'      => __DIR__ . '/../actions/logout.php',
    'register'    => __DIR__ . '/../actions/register.php',
    'post'        => __DIR__ . '/../actions/post.php',
    'home'      => __DIR__ . '/../actions/home.php',
    'submit_comment'        => __DIR__ . '/../src/handlers/posts/submit_comment_handler.php',
    'edit_comment'        => __DIR__ . '/../actions/edit_comment.php',
    'delete_comment'        => __DIR__ . '/../src/handlers/posts/delete_comment_handler.php',
];

if (isset($routes[$page])) {
    // Обработчик может вернуть имя шаблона
    $template = require $routes[$page];  
} else {
    http_response_code(404);
    $template = 'errors/404';
}

// layout подключается только для отображения шаблона
if (!headers_sent()) {
    require_once __DIR__ . '/../templates/layout.php';
}