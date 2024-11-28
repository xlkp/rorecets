<?php
require_once __DIR__ . '/../../../models/Recipes.php';
require_once __DIR__ . '/../../../../config/config.php';

$recipes = new Recipes($pdo);

if (isset($_GET['id_recipe'])) {
    $id_recipe = intval($_GET['id_recipe']);
}
$recipe = $recipes->getRecipeById($id_recipe);

if (!$recipe) {
    echo "Receta no encontrada.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image_recipe']) && $_FILES['image_recipe']['error'] === UPLOAD_ERR_OK) {
        $fileExtension = strtolower(pathinfo($_FILES['image_recipe']['name'], PATHINFO_EXTENSION));
        $originalFileName = pathinfo($_FILES['image_recipe']['name'], PATHINFO_FILENAME);
        $timestamp = date('YmdHis');
        $newFileName = $originalFileName . '_' . $timestamp . '.' . $fileExtension;
        $uploadDir = __DIR__ . '/../../../../assets/img/recipes/';
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['image_recipe']['tmp_name'], $destPath)) {
            if (!empty($recipe['image_recipe'])) {
                $oldImagePath = $uploadDir . $recipe['image_recipe'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $imagePathForDb = $newFileName;
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    } else {
        $imagePathForDb = $recipe['image_recipe'];
    }
    $recipes->updateRecipe(
        $id_recipe,
        $_POST['recipe_type'],
        $_POST['title'],
        $_POST['description'],
        $_POST['instructions'],
        $_POST['difficulty'],
        json_encode($_POST['ingredients']),
        $imagePathForDb
    );

    // Redirect after update
    header("Location: /recipes/edit?id_recipe=$id_recipe");
    exit;
}

$ingredients = $recipes->getIngredientsByRecipeId($id_recipe);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Receta</title>
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
                <a href="/" class="text-sm font-semibold text-gray-800 hover:text-lg">INICIO</a>
                <a href="/profile" class="text-sm font-semibold text-purple-800 hover:text-lg"><?php echo strtoupper($_SESSION['username']) ?></a>
                <a href="/recipes" class="text-sm font-semibold text-gray-800 hover:text-lg">OTRAS RECETAS</a>
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
            <h1 class="text-2xl font-bold text-center"><?php echo $recipe['title'] ?></h1>
            <form id="editRecipeForm" action="/recipes/edit?id_recipe=<?php echo $id_recipe?>" method="POST" enctype="multipart/form-data" class="max-w-xl mx-auto mt-6">
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-gray-700">Tipo de receta</span>
                        <input type="text" name="recipe_type" value="<?php echo ($recipe['recipe_type']); ?>" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required />
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Nombre de la receta</span>
                        <input type="text" name="title" value="<?php echo ($recipe['title']); ?>" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required />
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Descripción breve</span>
                        <input type="text" name="description" value="<?php echo ($recipe['description']); ?>" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required />
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Ingredientes</span>
                        <div id="ingredientFields" class="mt-2 space-y-2">
                            <?php foreach ($ingredients as $index => $ingredient): ?>
                                <div class="flex items-center">
                                    <input type="text" name="ingredients[]" value="<?php echo ($ingredient['name']); ?>" class="flex-grow rounded-md border-gray-200 shadow-sm" required />
                                    <button type="button" onclick="removeIngredient(<?php echo $index; ?>)" class="ml-2 px-3 py-1 bg-red-500 text-white rounded-md">Eliminar</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- hago que cuente los ingredientes para que no envie el formulario sin que haya ingredientes -->
                        <input type="hidden" id="ingredientCount" name="ingredientCount" value="<?php echo count($ingredients); ?>" />
                        <script>
                            document.getElementById('editRecipeForm').addEventListener('submit', function(e) {
                                const count = document.querySelectorAll('input[name="ingredients[]"]').length;
                                if (count === 0) {
                                    e.preventDefault();
                                    alert('Debe agregar al menos un ingrediente.');
                                }
                            });
                        </script>
                        <div class="flex gap-2 mt-2">
                            <input type="text" id="newIngredient" class="flex-grow rounded-md border-gray-200 shadow-sm" placeholder="Agregar ingrediente..." />
                            <button type="button" onclick="addIngredient()" class="px-4 py-2 bg-blue-500 text-white rounded-md">Agregar</button>
                        </div>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Instrucciones</span>
                        <textarea name="instructions" rows="5" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required><?php echo ($recipe['instructions']); ?></textarea>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Nivel de dificultad</span>
                        <select name="difficulty" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                            <option value="1" <?php echo ($recipe['difficulty'] == "1") ? "selected" : ""; ?>>Fácil - ⭐</option>
                            <option value="2" <?php echo ($recipe['difficulty'] == "2") ? "selected" : ""; ?>>Media - ⭐⭐</option>
                            <option value="3" <?php echo ($recipe['difficulty'] == "3") ? "selected" : ""; ?>>Difícil - ⭐⭐⭐</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Actualizar imagen</span>
                        <input type="file" name="image_recipe" accept="image/*" class="mt-1 block w-full" />
                    </label>
                    <div class="flex items-center justify-center">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md">Actualizar Receta</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script>
        let ingredientsList = <?php echo json_encode(array_column($ingredients, 'name')); ?>;

        function addIngredient() {
            const input = document.getElementById('newIngredient');
            const ingredient = input.value.trim();

            if (ingredient) {
                ingredientsList.push(ingredient);
                updateIngredientFields();
                input.value = '';
            }
        }

        function removeIngredient(index) {
            ingredientsList.splice(index, 1);
            updateIngredientFields();
        }

        function updateIngredientFields() {
            const container = document.getElementById('ingredientFields');
            container.innerHTML = '';

            ingredientsList.forEach((ingredient, index) => {
                const ingredientRow = document.createElement('div');
                ingredientRow.classList.add('flex', 'items-center', 'mt-2');

                ingredientRow.innerHTML = `
                    <input type="text" name="ingredients[]" value="${ingredient}" class="flex-grow rounded-md border-gray-200 shadow-sm" required />
                    <button type="button" onclick="removeIngredient(${index})" class="ml-2 px-3 py-1 bg-red-500 text-white rounded-md">Eliminar</button>
                `;

                container.appendChild(ingredientRow);
            });
        }
    </script>
</body>

</html>