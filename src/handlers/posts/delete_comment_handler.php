<?php


if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_POST['comment_id'])) {
    require_once __DIR__ . '/../../db.php';

    // Получаем ID комментария
    $commentId = new MongoDB\BSON\ObjectId($_POST['comment_id']);
    
    // Удаляем комментарий из MongoDB
    $deleteResult = $commentsCollection->deleteOne(['_id' => $commentId]);

    if ($deleteResult->getDeletedCount() > 0) {
        // Перенаправляем на страницу поста
        header('Location: /index.php?page=post&id=' . $_POST['post_id']);
        exit;
    } else {
        echo "Ошибка при удалении комментария.";
    }
} else {
    echo "У вас нет прав для удаления комментариев.";
}
?>
