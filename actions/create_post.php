<?php
/**
 * Обрабатывает создание нового поста.
 * 
 * Функция Handle_create_post вызывается при отправке формы для создания поста
 * и возвращает ошибки, если они есть.
 */
require_once __DIR__ . '/../src/handlers/posts/create_post_handler.php';

$error = handle_create_post(); // Обрабатываем отправку формы, если POST
$template = 'posts/create'; // Указываем какой шаблон подключать
require_once __DIR__ . '/../templates/layout.php'; // Загружаем основной макет
