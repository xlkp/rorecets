<?php
$url = $_SERVER['REQUEST_URI'];

if ($url == '/' || $url == '/index.php') {
    require 'public/index.php';
    exit;
}