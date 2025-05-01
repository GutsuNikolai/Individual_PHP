<?php

require_once __DIR__ . '/../../db.php';

/**
 * Обрабатывает аутентификацию пользователя
 *
 * Проверяет POST-запрос, валидирует логин и пароль, и если данные верны,
 * создаёт сессию пользователя и перенаправляет на главную страницу.
 * В случае ошибки возвращает сообщение.
 *
 * @return string|null Возвращает строку с ошибкой или null, если:
 *   - Запрос не POST
 *   - Аутентификация прошла успешно (происходит редирект)
 * @throws PDOException Если произошла ошибка при работе с базой данных
 */

function handle_login(): ?string {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = getDbConnection();

    $stmt = $pdo->prepare('SELECT id, password_hash, role FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Если пользователь найден, вернет данные в виде ассоцитивного массива

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return 'Неверное имя пользователя или пароль.';
    }

    // Стартуем сессию и сохраняем информацию
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    header('Location: /');
    exit;
}
