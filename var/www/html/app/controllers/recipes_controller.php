<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Recipes.php';

$recipes = new Recipes($pdo);

$allRecipes = $recipes->getAllRecipes();
$authorRecipe = $recipes->getUserRecipeName();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $fileExtension = '';
    $imagePath = '';
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
        $fileExtension = strtolower(pathinfo($_FILES['recipe_image']['name'], PATHINFO_EXTENSION));

        $uploadDir = __DIR__ . '/../../assets/img/recipes/';
        $timestamp = date('Y-m-d_H-i-s');
        $originalFileName = pathinfo($_FILES['recipe_image']['name'], PATHINFO_FILENAME);
        $imageName = $originalFileName . '_' . $timestamp . '.' . $fileExtension;
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['recipe_image']['tmp_name'], $imagePath)) {
            $imagePath = $imageName;
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    $ingredients = isset($_POST['ingredients']) ? json_decode($_POST['ingredients'], true) : [];
    if (!is_array($ingredients)) {
        $ingredients = [];
    }

    $recipes->createRecipe(
        $_POST['recipe_type'],
        $_POST['title'],
        $_POST['description'],
        $_POST['instructions'],
        $_POST['difficulty'],
        $ingredients,
        $imagePath
    );
}
