<?php
session_start();
// mediante las rutas controlo lo que aparece en la página por el tipo de sesion
$request = $_SERVER['REQUEST_URI'];
if ($request === '/') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /recipes');
        exit;
    }
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        $menuAdmin = 'PANEL DE ADMINISTRADOR';
    }
}