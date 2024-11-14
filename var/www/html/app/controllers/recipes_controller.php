<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Recipes.php';

$recipes = new Recipes($pdo);

$allRecipes = $recipes->getAllRecipes();
$authorRecipe = $recipes->getUserRecipeName();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeType = $_POST['recipe_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructions = $_POST['instructions'];
    $ingredient = $_POST['ingredients']; 
    $quantity = $_POST['quantities'];   
    $unit = $_POST['units'];
    $new_ingredient = $_POST['new_ingredient'];
    $difficulty = $_POST['difficulty'];
    $imagePath = '';

    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === 0) {
        $uploadDir = __DIR__.'/../../assets/img/recipes/'; 
        $imageName = basename($_FILES['recipe_image']['name']); // Obtén el nombre del archivo
        $imagePath = $uploadDir . $imageName;

        // Mueve el archivo a la carpeta de destino
        if (move_uploaded_file($_FILES['recipe_image']['tmp_name'], $imagePath)) {
            $imagePath = $imageName; // Guarda el nombre del archivo para almacenar en la base de datos
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    $recipeId = $recipes->createRecipe(
        $recipeType,
        $title,
        $description,
        $instructions,
        $difficulty,
        $ingredient,
        $quantity,
        $unit,
        $new_ingredient,
        $imagePath
    );
}
