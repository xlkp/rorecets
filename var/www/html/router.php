<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '':
    case '/':
        require __DIR__ . '/public/index.php';
        break;
//------------------------- AUTH ---------------------   
    case '/auth':
        require __DIR__ . '/app/controllers/auth_controller.php';
        break;
//------------------------- VISTAS ------------------ 
    case '/login':
        require __DIR__ . '/app/views/auth/login.html';
        break;

    case '/register':
        require __DIR__ . '/app/views/auth/register.html';
        break;
        
    case '/change_password':
        require __DIR__ . '/app/views/auth/change_password.html';
        break;
 
    case '/home':
        require __DIR__ . '/app/controllers/home_controller.php';
        break;

    case '/admin':
        require __DIR__ . '/app/views/layouts/admin.html';
        break;

    case '/profile':
        require __DIR__ . '/app/views/layouts/user.html';
        break;
//------------------------- ERROR ---------------------
    default:
        http_response_code(404);
        require __DIR__ . '/app/views/layouts/404.html';
        break;
}