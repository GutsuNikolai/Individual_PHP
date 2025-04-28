<?php

require_once __DIR__ . '/db.php';

function getRecipeById(int $id): ?array
{
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $recipe ?: null;
    } catch (PDOException $e) {
        echo "Ошибка при получении рецепта: " . $e->getMessage();
        return null;
    }
}
// function renderTemplate($template, $data = [])
// {
//     // Извлекаем массив данных в отдельные переменные
//     extract($data);
    
//     // Подключаем шаблон
//     include __DIR__ . "/templates/$template.php";
// }
