<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: /login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoRecets</title>
    <!-- bootstrap implementation igual lo cambio por otra shit-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-primary">rorecets</button>
        </div>
    </div>
</body>
</html>