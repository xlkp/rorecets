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

        //------------------------- VISTAS PROTEGIDAS POR SESIONES ------------------ 
    case '/recipes':
    case '/profile':
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            switch ($request) {
                case '/recipes':
                    require __DIR__ . '/app/views/home/my_recipes.html';
                    break;
                case '/profile':
                    require __DIR__ . '/app/views/layouts/user.html';
                    break;
            }
        } else {
            http_response_code(403);
            echo "Acceso denegado. Debes iniciar sesión para acceder a esta página.";
            // header('Location: 404');
        }
        break;
    case '/admin':
        session_start();
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            require __DIR__ . '/app/views/layouts/admin.html';
        } else {
            http_response_code(403);
            echo "Acceso denegado. Debes ser administrador para acceder a esta página.";
            // te dejo los echos para que veas que puedo controlar rutas y casos de uso
            // la parte de abajo es mas segura ya que no le indica al usuario que la página existe
            // header('Location: 404');
        }
        break;

        //------------------------- ERROR ---------------------
    case '/404':
        require __DIR__ . '/app/views/layouts/404.html';
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/app/views/layouts/404.html';
        break;
}
