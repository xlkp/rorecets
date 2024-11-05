<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '':
    case '/':
        require __DIR__ . '/public/index.php';
        break;
//------------------------- AUTH ---------------------   
    case '/loginValidation':
        require __DIR__ . '/app/controllers/login.php';
        break;
    
    case '/registerValidation':
        require __DIR__ . '/app/controllers/register.php';
        break;
//------------------------- VISTAS ------------------ 
    case '/login':
        require __DIR__ . '/app/views/auth/login.html';
        break;

    case '/register':
        require __DIR__ . '/app/views/auth/register.html';
        break;
 
    case '/home':
        require __DIR__ . '/app/views/home/index.php';
        break;

    case '/admin':
        require __DIR__ . '/app/views/admin.php';
        break;
    case '/profile':
        require __DIR__ . '/app/views/layouts/user.php';
        break;
//------------------------- ERROR ---------------------
    default:
        http_response_code(404);
        require __DIR__ . '/app/views/404.php';
        break;
}