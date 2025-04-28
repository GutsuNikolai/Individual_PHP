<?php

require_once __DIR__ . '/../src/handlers/auth/login_handler.php';
$error = handle_login();

// Задаём шаблон
$template = 'auth/login'; // Указываем путь к шаблону
require_once __DIR__ . '/../templates/layout.php';  // Подключаем layout.php
