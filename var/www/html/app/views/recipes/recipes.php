<?php
require_once __DIR__ . '/../../controllers/pagination_controller.php';
require_once __DIR__ . '/../../models/Recipes.php';
require_once __DIR__ . '/../../controllers/profile_controller.php';

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $menuAdmin = true;
}

// paginación
$recipes = new Recipes($pdo);
$recetasPorPagina = 4;
$recipesController = new PaginationController($pdo);
$paginationData = $recipesController->getPaginatedRecipes( $recetasPorPagina);

$recetasPagina = $paginationData['recetasPagina'];
$paginaActual = $paginationData['paginaActual'];
$totalPaginas = $paginationData['totalPaginas'];


// usuario
if(isset($_SESSION['username'])){
    $user = new ProfileController($pdo);
    $userData = $user->getUserDataByName($_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recetas</title>
    <script
        src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>
<?php if (isset($menuAdmin)) { ?>
    <style>
        .image-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .image:hover {
            filter: brightness(50%);
        }

        .edit-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #3b82f6;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .image-container:hover .edit-button {
            opacity: 1;
        }
    </style>
<?php } ?>

<body>
    <div class="bg-white">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                </div>
                <?php if (isset($_SESSION['logged_in'])) { ?>
                    <div class="hidden lg:flex lg:gap-x-12">
                        <a href="/" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">INICIO</a>
                        <a href="profile?user=<?php echo $userData['id_user']?>" class="text-sm/6 font-semibold text-purple-800 hover:text-lg"><?php echo strtoupper($_SESSION['username']) ?></a>
                        <?php if (isset($menuAdmin)) {
                            echo '<a href="recipes/mine" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">CREAR RECETAS</a>';
                        } else {
                            echo '<a href="/followers" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">SEGUIDORES</a>';
                        } ?>

                    </div>
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <form action="auth" method="post">
                            <input type="submit" name="closeSession" value="Cerrar sesión"
                                class="text-sm/6 font-semibold text-gray-800 hover:text-lg"> <span
                                aria-hidden="true">&rarr;</span></input>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="hidden lg:flex lg:gap-x-12 lg:justify-center">
                        <a href="login" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">INICIAR SESIÓN</a>
                        <a href="register" class="text-sm/6 font-semibold text-purple-800 hover:text-lg">REGISTRARSE</a>
                    </div>
                <?php } ?>
            </nav>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>

            <!-- RECETAS -->
            <section>
                <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                    <header>
                        <h2 class="text-xl font-bold text-gray-900 sm:text-3xl">Recetas</h2>
                        <p class="mt-4 max-w-md text-gray-500">
                            Descubre las recetas que han creado los usuarios de la comunidad.
                        </p>
                    </header>
                    <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 contenedor-recetas">
                        <!-- siempre las mas recientes se ponen las primeras -->
                        <?php foreach ($recetasPagina as $recipe) { ?>
                            <li>
                                <a class="group block overflow-hidden" href="/recipes/view?id_recipe=<?php echo $recipe['id_recipe']; ?>">
                                    <div class="image-container">
                                        <img src="<?php echo '/../../../../assets/img/recipes/' . $recipe['image_recipe']; ?>" alt="<?php echo ($recipe['title']); ?>" class="image h-[350px] w-full object-cover transition duration-500 group-hover:scale-105 sm:h-[450px]">
                                        <?php if (isset($menuAdmin)) { ?>
                                            <a class="edit-button" href="/recipes/edit?id_recipe=<?php echo $recipe['id_recipe']; ?>">
                                                ✏️ Editar
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <div class="relative pt-3">
                                        <h3 class="text-xs text-gray-700 group-hover:underline group-hover:underline-offset-4">
                                            <?php echo "{$recipe['title']}"; ?>
                                        </h3>

                                        <p class="mt-2">
                                            <span class="sr-only"> Dificultad </span>
                                            <span class="tracking-wider text-gray-900">
                                                <?php
                                                $rating = $recipes->getAverageRating($recipe['id_recipe']);
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
                                                        $avgRating = "No hay valoraciones.";
                                                        break;
                                                }
                                                echo $avgRating; ?>
                                            </span>
                                        </p>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <ol class="mt-8 flex justify-center gap-1 text-xs font-medium">
                        <?php if ($paginaActual > 1): ?>
                            <li>
                                <a href="?pagina=<?php echo $paginaActual - 1; ?>" class="block size-8 rounded border border-gray-100 text-center leading-8 inline-flex items-center justify-center">
                                    <span class="sr-only">Página Anterior</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5l-7.5-7.5 7.5-7.5" />
                                    </svg>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li>
                                <?php if ($i == $paginaActual): ?>
                                    <span class="block size-8 rounded border-black bg-black text-center leading-8 text-white">
                                        <?php echo $i; ?>
                                    </span>
                                <?php else: ?>
                                    <a href="?pagina=<?php echo $i; ?>" class="block size-8 rounded border border-gray-100 text-center leading-8">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endfor; ?>
                        <?php if ($paginaActual < $totalPaginas): ?>
                            <li>
                                <a href="?pagina=<?php echo $paginaActual + 1; ?>" class="inline-flex items-center justify-center rounded border border-gray-300 bg-white px-3 py-1.5 text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    <span class="sr-only">Página Siguiente</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ol>
                </div>
            </section>

            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
                aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
        </div>
    </div>
</body>

</html>