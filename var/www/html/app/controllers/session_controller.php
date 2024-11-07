<?php
session_start();

// mediante las rutas controlo mediante el tipo de sesión lo que aparece en cada página
$request = $_SERVER['REQUEST_URI'];
if ($request === '/') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /login');
        exit;
    }
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        // meter variable que pinte en el header la ruta hacia el panel de administrador
        header('Location: /admin');
        exit;
    }
}