<?php
require_once __DIR__ . '/../../../models/Recipes.php';
require_once __DIR__ . '/../../../../config/config.php';

$recipes = new Recipes($pdo);

$id_recipe = $_SESSION['id_recipe'];
$recipe = $recipes->getRecipeById($id_recipe);

if (!$recipe) {
    echo "Receta no encontrada.";
    exit;
}

$ingredients = $recipes->getIngredientsByRecipeId($id_recipe);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ver Receta</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <style>
        .scrollable {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>

<body class="bg-white">
    <header class="absolute inset-x-0 top-0 z-50">
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1"></div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="/" class="text-sm font-semibold text-gray-800 hover:text-lg">inicio</a>
                <a href="/profile" class="text-sm font-semibold text-purple-800 hover:text-lg"><?php echo $_SESSION['username']; ?></a>
                <a href="/recipes" class="text-sm font-semibold text-gray-800 hover:text-lg">otras recetas</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <form action="/auth" method="post">
                    <input type="submit" name="closeSession" value="Cerrar sesión" class="text-sm font-semibold text-gray-800 hover:text-lg">
                    <span aria-hidden="true">&rarr;</span>
                </form>
            </div>
        </nav>
    </header>
    <main class="relative px-6 pt-14 lg:px-8">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
            aria-hidden="true">
            <div class="relative left-1/2 aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 opacity-30 sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%,
                80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%,
                45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%,
                27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="flex items-center justify-center mt-4">
            <img src="<?php echo '/../../../../assets/img/recipes/' . $recipe['image_recipe']; ?>" alt="Imagen de la receta" class="w-1/2 h-auto rounded-lg shadow-lg">
        </div>
        <div class="mt-8">
            <h1 class="text-2xl font-bold text-center"><?php echo ($recipe['title']); ?></h1>
            <div class="max-w-xl mx-auto mt-6">
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-700">Tipo de receta:</span>
                        <p class="mt-1 text-gray-800"><?php echo ($recipe['recipe_type']); ?></p>
                    </div>
                    <div>
                        <span class="text-gray-700">Descripción breve:</span>
                        <p class="mt-1 text-gray-800"><?php echo ($recipe['description']); ?></p>
                    </div>
                    <div>
                        <span class="text-gray-700">Ingredientes:</span>
                        <ul class="mt-2 list-disc list-inside">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <li><?php echo ($ingredient['name']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <span class="text-gray-700">Instrucciones:</span>
                        <p class="mt-1 text-gray-800"><?php echo nl2br(($recipe['instructions'])); ?></p>
                    </div>
                    <div>
                        <span class="text-gray-700">Nivel de dificultad:</span>
                        <p class="mt-1 text-gray-800">
                            <?php
                            if ($recipe['difficulty'] == 1) {
                                echo "Fácil";
                            } elseif ($recipe['difficulty'] == 2) {
                                echo "Medio";
                            } else {
                                echo "Difícil";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>