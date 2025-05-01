<?php
session_start();

$page = $_GET['page'] ?? 'home';

$routes = [
    'create_post'    => __DIR__ . '/../actions/create_post.php',
    'edit_post'      => __DIR__ . '/../actions/edit_post.php',
    'login'          => __DIR__ . '/../actions/login.php',
    'logout'         => __DIR__ . '/../actions/logout.php',
    'register'       => __DIR__ . '/../actions/register.php',
    'post'           => __DIR__ . '/../actions/post.php',
    'home'           => __DIR__ . '/../actions/home.php',
    'add_admin'      => __DIR__ . '/../actions/add_admin.php',
    'edit_comment'   => __DIR__ . '/../actions/edit_comment.php',
    'delete_post'           => __DIR__ . '/../src/handlers/posts/delete_post_handler.php',
    'submit_comment'        => __DIR__ . '/../src/handlers/posts/submit_comment_handler.php',
    'delete_comment'        => __DIR__ . '/../src/handlers/posts/delete_comment_handler.php',
];

if (isset($routes[$page])) {
    // Получаю имя шаблона
    $template = require $routes[$page];  

} else {
    http_response_code(404);
    $template = 'errors/404';
}

if (!headers_sent()) {
    require_once __DIR__ . '/../templates/layout.php';
}