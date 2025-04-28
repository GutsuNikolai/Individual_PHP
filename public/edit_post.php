<?php
require_once __DIR__ . '/../src/handlers/posts/edit_post_handler.php';

session_start(); // на будущее (например, для проверки прав)

[$error, $post] = handle_edit_post($_GET['id'] ?? null);

$template = 'posts/edit';
require_once __DIR__ . '/../templates/layout.php';
