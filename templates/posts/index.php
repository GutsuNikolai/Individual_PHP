<h1>Все посты</h1>

<?php foreach ($posts as $post): ?>
    <div class="post">
        <h2><a href="/index.php?page=post&id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
        <p><?= htmlspecialchars(substr($post['description'], 0, 100)) ?>...</p>
        <a href="/post.php?id=<?= $post['id'] ?>">Читать полностью</a>
    </div>
<?php endforeach; ?>
