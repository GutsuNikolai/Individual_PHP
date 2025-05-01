<?php

require_once __DIR__ . '/../../db.php';
/**
 * Обрабатывает регистрацию нового АДМИНА
 *
 * Принимает POST-данные формы регистрации, валидирует их и создает нового пользователя в БД.
 * В случае ошибки валидации возвращает соответствующее сообщение.
 * При успешной регистрации перенаправляет на страницу входа.
 *
 * @return string|null Возвращает строку с ошибкой или null в случаях:
 *   - Запрос не POST
 *   - Успешная регистрация (происходит редирект)
 * @throws PDOException Если произошла ошибка при работе с базой данных
 */

function add_admin(): ?string {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    // Получаем данные из формы
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';


    if ($password !== $confirm) {
        return 'Пароли не совпадают.';
    }
    
    if (strlen($username) < 3 || strlen($password) < 6) {
        return 'Имя или пароль слишком короткие.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Некорректный email.';
    }

    $pdo = getDbConnection();

    // Проверка, существует ли уже пользователь с таким именем или email
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->fetch()) {
        return 'Пользователь с таким именем или email уже существует.';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)');
    $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash, 'role' => 'admin']);

    header('Location: /');
    exit;
}
