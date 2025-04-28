<?php

require_once __DIR__ . '/../src/handlers/auth/register_handler.php';

$error = handle_register();
$template = 'auth/register';
require_once __DIR__ . '/../templates/layout.php';

