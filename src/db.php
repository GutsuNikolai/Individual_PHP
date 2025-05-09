<?php

// Подключаем конфигурацию с параметрами подключения
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Создает и возвращает подключение к базе данных PostgreSQL
 * 
 * Использует константы для параметров подключения:
 * - DB_HOST - хост базы данных
 * - DB_PORT - порт подключения
 * - DB_NAME - имя базы данных
 * - DB_USER - имя пользователя
 * - DB_PASS - пароль
 *
 * @return PDO Объект подключения к базе данных
 * @throws PDOException Если подключение не удалось
 * @throws RuntimeException Если не определены необходимые константы
 */
function getDbConnection(): PDO
{
    try {
        // Строка подключения для PostgreSQL
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";";
        
        // Создаем и возвращаем экземпляр PDO
        $pdo = new PDO($dsn, DB_USER, DB_PASS);

        // Настроим выбрасывание исключений при ошибках
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        // Если не удается подключиться, выбрасываем исключение с сообщением об ошибке
        echo "Ошибка подключения к базе данных: " . $e->getMessage();
        exit;
    }
}

// Для подключения mongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$commentsCollection = $mongoClient->blog_comments->comments;
