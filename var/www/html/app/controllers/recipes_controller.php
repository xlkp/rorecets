<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Recipes.php';

// construyo el objeto de recetas con la conexion a base de datos para evitarme 1000 lineas de código más.
$recipes = new Recipes($pdo);

$allRecipes = $recipes->getAllRecipes();
$authorRecipe = $recipes->getUserRecipeName();
