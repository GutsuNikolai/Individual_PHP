<?php

require_once __DIR__ . '/../src/handlers/auth/login_handler.php';
$error = handle_login();

$template = 'auth/login'; 
require_once __DIR__ . '/../templates/layout.php';  
