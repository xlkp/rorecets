<?php
$host = 'mysql-db';
$user = 'stef';
$pass = '1312';
$db = 'rorecets';
$pdo = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $pass);
