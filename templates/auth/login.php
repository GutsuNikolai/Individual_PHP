<h2>Вход в систему</h2>
<form method="POST" action="/index.php?page=login">
    <label for="username">Имя пользователя:</label><br>
    <input type="text" name="username" required><br><br>

    <label for="password">Пароль:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Войти">
</form>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
