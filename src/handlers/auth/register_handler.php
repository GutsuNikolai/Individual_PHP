<?php

require_once __DIR__ . '/../../db.php';

function handle_register(): ?string {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    // Получаем данные из формы
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? ''); // Получаем email
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Проверка на совпадение паролей
    if ($password !== $confirm) {
        return 'Пароли не совпадают.';
    }

    // Проверка на длину имени пользователя и пароля
    if (strlen($username) < 3 || strlen($password) < 6) {
        return 'Имя или пароль слишком короткие.';
    }

    // Проверка, чтобы email был валидным
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Некорректный email.';
    }

    $pdo = getDbConnection();

    // Проверим, не существует ли уже пользователь с таким именем или email
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->fetch()) {
        return 'Пользователь с таким именем или email уже существует.';
    }

    // Хешируем пароль
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Вставляем данные в базу данных
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)');
    $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash, 'role' => 'user']);

    // Редирект на страницу входа после успешной регистрации
    header('Location: /login.php');
    exit;
}
