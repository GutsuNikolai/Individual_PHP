<?php
$postId = $post['id'];

// Получаю все комментарии для текущего поста из MongoDB
$comments = $commentsCollection->find(['post_id' => (int)$postId]);

// Преобразуем курсор в массив
$commentsArray = iterator_to_array($comments);

?>

<h1><?= htmlspecialchars($post['title']) ?></h1>
<p><strong>Опубликовано:</strong> <?= htmlspecialchars($post['published_at']) ?></p>

<?php if ($post['image_path']): ?>
    <div style="display: flex; justify-content: center;">
        <img src="<?= htmlspecialchars($post['image_path']) ?>" 
             alt="Изображение поста" 
             style="max-width: 70%; 
             height: auto;">
    </div>
<?php endif; ?>

<p><strong>Содержание:</strong> <?= nl2br(htmlspecialchars($post['description'])) ?></p>

<!-- Редактирование и удаление постов, доступно только админу -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="/index.php?page=edit_post&id=<?= $post['id'] ?>"></br>Редактировать</a>
    <form method="POST" action="/index.php?page=delete_post" onsubmit="return confirm('Удалить пост?')">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <button type="submit">Удалить</button>
    </form>
<?php endif; ?>


<h2>Комментарии:</h2>
<?php if (count($commentsArray) > 0): ?>
    <ul>
        <?php foreach ($commentsArray as $comment): ?>
            <li>
                <strong><?= htmlspecialchars($comment['user_name']) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>

                <?php
                    $createdAt = $comment['created_at']->toDateTime(); // это нужно для MongoDB DateTime объекта
                    // Форматирование даты
                    $formattedDate = $createdAt->format('d-m-Y H:i'); // Например: 30-04-2025 12:12:04
                ?>
                <p><small>Опубликовано: <?= htmlspecialchars($formattedDate) ?></small></p>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <!-- Ссылка для редактирования и кнопка для удаления комментариев (тоже только для админа)  -->
                    <a href="/index.php?page=edit_comment&id=<?= $comment['_id'] ?>&post_id=<?= $post['id'] ?>">Редактировать</a>

                    <form method="POST" action="/index.php?page=delete_comment" onsubmit="return confirm('Удалить комментарий?')">
                        <input type="hidden" name="comment_id" value="<?= $comment['_id'] ?>">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit">Удалить</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Комментариев пока нет.</p>
<?php endif; ?>


<hr>
<h3>Оставить комментарий</h3>
<!-- Оставлять комменты могут только авторизованные пользователи  -->
<?php if (isset($_SESSION['user_id'])): ?>
    <form method="POST" action="/index.php?page=submit_comment">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
        <textarea name="content" rows="4" cols="50" placeholder="Введите ваш комментарий..." required></textarea><br>
        <button type="submit">Отправить</button>
    </form>
    
<?php else: ?>
    <p><a href="/index.php?page=login">Войдите</a>, чтобы оставить комментарий.</p>
<?php endif; ?>

