<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Доступ запрещен');
}

require_once __DIR__ . '/../src/handlers/auth/add_admin_handler.php';

$error = add_admin();

$template = 'auth/add_admin';

require_once __DIR__ . '/../templates/layout.php';

