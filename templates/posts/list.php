<h2>Посты</h2>

<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h3><a href="/index.php?page=post&id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
            
            <p><?= htmlspecialchars(mb_substr($post['description'], 0, 100)) ?>...</p>
            <p><strong>Теги:</strong> <?= htmlspecialchars($post['tags']) ?></p>
            <a href="/index.php?page=post&id=<?= $post['id'] ?>">Читать полностью</a>
            </br></br>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Посты не найдены по заданным критериям.</p>
<?php endif; ?>
