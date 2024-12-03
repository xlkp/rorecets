<?php
require_once __DIR__ . '/app/controllers/session_controller.php';
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
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
        
    case '/recipes':
        require __DIR__ . '/app/views/recipes/recipes.php';
        break;

    case '/recipes/view':
        require __DIR__ . '/app/views/recipes/view_recipe.php';
        break;
        //------------------------- VISTAS PROTEGIDAS POR SESIONES ------------------ 
    case '/profile':
    case '/followers':
    case '/profile/edit':
        // me he pegado 2 horas con este error y era simplemente empezar la sesión aquí
        // la he cambiado arriba la session para meterle el timeout
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            switch ($request) {
                case '/profile':
                    require __DIR__ . '/app/views/user/user.php';
                    break;
                case '/followers':
                    require __DIR__ . '/app/views/user/followers.php';
                    break;
                case '/profile/edit':
                    require __DIR__ . '/app/views/user/edit_profile.php';
                    break;
            }
        } else {
            http_response_code(403);
            header('Location: 403/login');
            // header('Location: 404');
        }
        break;
    case '/admin':
    case '/recipes/mine':
    case '/validarReceta':
    case '/recipes/edit':
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            switch ($request) {
                case '/admin':
                    require __DIR__ . '/app/views/user/admin.php';
                    break;
                case '/recipes/mine':
                    require __DIR__ . '/app/views/recipes/admin_recipes.php';
                    break;
                case '/validarReceta':
                    require __DIR__ . '/app/controllers/recipes_controller.php';
                    break;
                case '/recipes/edit':
                    require __DIR__ . '/app/views/recipes/edit_recipe.php';
                    break;
            }
        } else {
            http_response_code(403);
            header('Location: 403/admin');
            // los mando a rutas personalizadas dependiendo de si han iniciado sesion o son admins
            // mandarle al 404 es mas seguro ya que no le indica al usuario que la página existe
            // header('Location: 404');
        }
        break;

        //------------------------- ERROR ---------------------
    case '/404':
        require __DIR__ . '/app/views/auth/404.html';
        break;

    case '/403/login':
        require __DIR__ . '/app/views/auth/noLogin.html';
        break;
    case '/403/admin':
        require __DIR__ . '/app/views/auth/noAdmin.html';
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/app/views/auth/404.html';
        break;
}
