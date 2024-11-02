<?php
$host = 'mysql-db';
$user = 'stef';
$pass = '1312';
$db = 'rorecets';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connected successfully";
}
$conn->close();

$pdo = new PDO("mysql:host=".$host.";dbname=".$db,$user,$pass);
