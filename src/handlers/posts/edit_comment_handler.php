<?php
// session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
    require_once __DIR__ . '/../../db.php';

    $commentId = new MongoDB\BSON\ObjectId($_GET['id']);

    // Получаю коммент из MongoDB
    $comment = $commentsCollection->findOne(['_id' => $commentId]);
    $content = $comment['content'];
    $postId = $_GET['post_id'] ?? '';
    $commentIdStr = (string)$comment['_id'];
    
    if ($comment) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Обновляем комментарий
            $updatedContent = $_POST['content'];

            $updateResult = $commentsCollection->updateOne(
                ['_id' => $commentId],
                ['$set' => ['content' => $updatedContent]]
            );
            
            // Проверяю если были внесены изменения
            if ($updateResult->getModifiedCount() > 0) {
                header('Location: /index.php?page=post&id=' . Trim($_POST['post_id'])); // Перенаправляю на пост
                exit;
            } else {
                echo "Ошибка при обновлении комментария.";
            }
        }
    } else {
        echo "Комментарий не найден.";
    }
} else {
    echo "У вас нет прав для редактирования комментариев.";
}
?>

