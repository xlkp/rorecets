<?php
session_start();

$timeout_duration = 300;

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        $elapsed_time = time() - $_SESSION['LAST_ACTIVITY'];
        if ($elapsed_time > $timeout_duration) {
            session_unset();
            session_destroy();
            header('Location: /login');
            exit();
        }
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}

// Your existing routing logic
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