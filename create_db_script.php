<?php
// Параметры подключения к серверу PostgreSQL (без указания БД)
$host = 'localhost';
$port = '5432';
$user = 'имя вашего пользователя';
$password = 'ваш_пароль';

try {
    // Подключение к серверу PostgreSQL (без указания БД)
    $conn = new PDO("pgsql:host=$host;port=$port", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создание базы данных
    $dbname = 'blog';
    $conn->exec("CREATE DATABASE $dbname");
    echo "База данных '$dbname' успешно создана\n";
    
    // Теперь подключаемся к созданной БД
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создание таблицы users
    $sql = "CREATE TABLE users (
        id SERIAL PRIMARY KEY,
        user_name VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "Таблица 'users' успешно создана\n";
    
} catch(PDOException $e) {
    die("Ошибка: " . $e->getMessage());
}
?>