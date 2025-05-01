<h2>Добавление нового админа</h2>
<form method="POST" action="/index.php?page=add_admin">
    <label for="username">Имя пользователя:</label><br>
    <input type="text" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" placeholder="Email" required><br><br>

    <label for="password">Пароль:</label><br>
    <input type="password" name="password" required><br><br>

    <label for="confirm_password">Подтвердите пароль:</label><br>
    <input type="password" name="confirm_password" required><br><br>
    

    <input type="submit" value="Зарегистрироваться">
</form>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
