<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../db.php';

use MongoDB\Client;
$pdo = getDbConnection();
// Проверка, авторизован ли пользователь
//session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php?page=login');
    exit;
}

// Получение данных из POST
$postId = $_POST['post_id'] ?? null;
$content = trim($_POST['content'] ?? '');
$userId = $_SESSION['user_id'] ?? null;

// Проверка
if (!$postId || !$content) {
    die('Поля комментария не заполнены.');
}

// Получение логина пользователя из MySQL
$stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
$userName = $user ? $user['username'] : 'неизвестный';

// Подключение к MongoDB 
// TODO
$mongo = new Client("mongodb://localhost:27017");
$collection = $mongo->blog_comments->comments;

// Запись комментария
$collection->insertOne([
    'post_id' => (int)$postId,
    'user_id' => (int)$userId,
    'user_name' => $userName,
    'content' => $content,
    'created_at' => new MongoDB\BSON\UTCDateTime()
]);

// Возврат на страницу поста
header("Location: /index.php?page=post&id=$postId");
exit;
