<?php
require_once __DIR__ . '/../../../models/Recipes.php';
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../controllers/comment_controller.php';
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $menuAdmin = true;
}

$recipes = new Recipes($pdo);

if (isset($_GET['id_recipe'])) {
    $id_recipe = intval($_GET['id_recipe']);
}
$recipe = $recipes->getRecipeById($id_recipe);
$authorRecipe = $recipes->getUserRecipeName($id_recipe);
$rating = $recipes->getAverageRating($id_recipe);

switch ($rating) {
    case 1:
        $avgRating = "⭐";
        break;
    case 2:
        $avgRating = "⭐⭐";
        break;
    case 3:
        $avgRating = "⭐⭐⭐";
        break;
    case 4:
        $avgRating = "⭐⭐⭐⭐";
        break;
    case 5:
        $avgRating = "⭐⭐⭐⭐⭐";
        break;
    default:
        $avgRating = "";
        break;
}

if (!$recipe) {
    echo "Receta no encontrada.";
    exit;
}

$ingredients = $recipes->getIngredientsByRecipeId($id_recipe);
$comments = $recipes->getCommentsByRecipeId($id_recipe);

$commentsController = new CommentsController($recipes);
$commentsController->handleRequest($id_recipe);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_recipe'])) {
    $recipes->deleteRecipe($id_recipe);
    header("Location: /recipes");
    exit;
}
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
        <?php if (isset($_SESSION['logged_in'])) { ?>
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
        <?php } else { ?>
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1"></div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="/recipes" class="text-sm font-semibold text-gray-800 hover:text-lg">INICIO</a>
                    <a href="/login" class="text-sm font-semibold text-gray-800 hover:text-lg">INICIAR SESIÓN</a>
                    <a href="/register" class="text-sm font-semibold text-gray-800 hover:text-lg">REGISTRARSE</a>
                </div>
            </nav>
        <?php } ?>
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
        <div class="flex flex-row mt-8">
            <!-- Left side: Ingredients, Comments, and Add Comment -->
            <div class="w-1/2">
                <!-- Ingredients Section -->
                <div class="mt-8">
                    <h1 class="text-2xl  font-bold text-center"><?php echo $recipe['title'] ?></h1>
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

                <!-- Comments Section -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-center mt-6">Comentarios</h2>
                    <?php foreach ($comments as $comment):
                        $userRating = $recipes->getRating($comment['id_user'], $id_recipe);
                        switch ($userRating) {
                            case 1:
                                $userRatingStars = "⭐";
                                break;
                            case 2:
                                $userRatingStars = "⭐⭐";
                                break;
                            case 3:
                                $userRatingStars = "⭐⭐⭐";
                                break;
                            case 4:
                                $userRatingStars = "⭐⭐⭐⭐";
                                break;
                            case 5:
                                $userRatingStars = "⭐⭐⭐⭐⭐";
                                break;
                            default:
                                $userRatingStars = "";
                                break;
                        }
                    ?>
                        <div class="border p-4 mt-4 rounded-lg shadow-md bg-gray-50">
                            <p class="font-semibold text-lg"><?php echo ($comment['username']); ?></p>

                            <p><?php echo $userRatingStars; ?></p>
                            <p class="mt-2 text-gray-800"><?php echo ($comment['description']); ?></p>
                            <p class="mt-2 text-sm text-gray-500"><?php echo ($comment['comment_date']); ?></p>


                            <?php if (isset($menuAdmin)): ?>
                                <form method="post" action="/recipes/view?id_recipe=<?php echo $id_recipe?>" class="mt-2">

                                    <input type="hidden" name="id_comment" value="<?php echo ($comment['id_comment']); ?>">

                                    <button type="submit" name="delete_comment" class="text-red-500 hover:text-red-700">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add Comment Form -->
                <?php if (isset($_SESSION['logged_in'])) { ?>
                    <form action="/recipes/view?id_recipe=<?php echo $id_recipe ?>" method="post" class="mt-6 bg-gray-100 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Añadir un comentario</h3>
                        <textarea name="description" required class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Escribe tu comentario"></textarea>
                        <div class="mt-4">
                            <span class="block text-gray-700 mb-2">Valoración:</span>
                            <div class="flex items-center space-x-1 flex-row-reverse justify-end">
                                <input type="radio" id="star1" name="score" value="5" class="hidden">
                                <label for="star1" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-500 star">&#9733;</label>

                                <input type="radio" id="star2" name="score" value="4" class="hidden">
                                <label for="star2" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-500 star">&#9733;</label>

                                <input type="radio" id="star3" name="score" value="3" class="hidden">
                                <label for="star3" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-500 star">&#9733;</label>

                                <input type="radio" id="star4" name="score" value="2" class="hidden">
                                <label for="star4" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-500 star">&#9733;</label>

                                <input type="radio" id="star5" name="score" value="1" class="hidden">
                                <label for="star5" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-500 star">&#9733;</label>
                            </div>
                        </div>
                        <button type="submit" name="submit_comment" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                            Enviar comentario
                        </button>
                    </form>

                    <style>
                        .star:hover,
                        .star:hover~.star {
                            color: #f59e0b;
                        }

                        input[type="radio"]:checked~label {
                            color: #f59e0b;
                        }

                        input[type="radio"]:checked~label~label {
                            color: #f59e0b;
                        }

                        input[type="radio"]:checked~label~label~label {
                            color: #f59e0b;
                        }

                        input[type="radio"]:checked~label~label~label~label {
                            color: #f59e0b;
                        }

                        input[type="radio"]:checked~label~label~label~label~label {
                            color: #f59e0b;
                        }
                    </style>
                <?php } ?>
            </div>

            <!-- Right side: Image, Username, and Rating -->
            <div class="w-1/2 flex flex-col items-center">
                <img src="<?php echo '/../../../../assets/img/recipes/' . $recipe['image_recipe']; ?>" alt="Imagen de la receta" class="w-3/4 h-auto rounded-lg shadow-lg">
                <h1 class="text-1xl text-center mt-4"><?php echo "$authorRecipe" ?></h1>
                <h1 class="text-2xl font-bold text-center"><?php echo $avgRating ?></h1>
            </div>
        </div>
    </main>
</body>

</html>