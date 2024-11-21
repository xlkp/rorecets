<?php
require_once __DIR__ . '/../../../controllers/recipes_controller.php';

$recipes = new Recipes($pdo);
$allRecipes = $recipes->getAllRecipes();

$recetasPorPagina = 4;
$totalRecetas = count($allRecipes);
// redondeo hacia arriba para obtener el total de páginas
$totalPaginas = ceil($totalRecetas / $recetasPorPagina);

if (isset($_POST['pagina'])) {
    $_SESSION['paginaActual'] = (int)$_POST['pagina'];
}

$paginaActual = isset($_SESSION['paginaActual']) ? $_SESSION['paginaActual'] : 1;

if ($paginaActual < 1) {
    $paginaActual = 1;
} elseif ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$offset = ($paginaActual - 1) * $recetasPorPagina;
$recetasPagina = array_slice($allRecipes, $offset, $recetasPorPagina);

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $menuAdmin = true;
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

<body>
    <div class="bg-white">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="/" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">inicio</a>
                    <a href="profile" class="text-sm/6 font-semibold text-purple-800 hover:text-lg"><?php echo ($_SESSION['username']) ?></a>
                    <?php if (isset($menuAdmin)) {
                        echo '<a href="recipes/mine" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">mis recetas</a>';
                    } else {
                        echo '<a href="/followers" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">mis seguidores</a>';
                    } ?>

                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <form action="auth" method="post">
                        <input type="submit" name="closeSession" value="Cerrar sesión"
                            class="text-sm/6 font-semibold text-gray-800 hover:text-lg"> <span
                            aria-hidden="true">&rarr;</span></input>
                    </form>
                </div>
            </nav>
            <div class="lg:hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 z-50"></div>
                <div
                    class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                                <a href="/"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">inicio</a>
                                <a href="profile"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"><?php echo ($_SESSION['username']) ?></a>
                                <?php if (isset($menuAdmin)) {
                                    echo '<a href="recipes/mine"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">mis recetas</a>';
                                } else {
                                    echo '<a href="/followers"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">mis seguidores</a>';
                                } ?>
                            </div>
                            <div class="py-6">
                                <form action="auth" method="post">
                                    <input type="submit" name="closeSession" value="Cerrar sesión"
                                        class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"></input>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>

            <!-- HEADER con botones para crear recetas o ver todas -->

            <!-- RECETAS -->
            <section>
                <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                    <header>
                        <h2 class="text-xl font-bold text-gray-900 sm:text-3xl">Recetas</h2>

                        <p class="mt-4 max-w-md text-gray-500">
                            Descubre las recetas que han creado los usuarios de la comunidad.
                        </p>
                    </header>

                    <div class="mt-8 sm:flex sm:items-center sm:justify-between">
                        <div class="block sm:hidden">
                            <button
                                class="flex cursor-pointer items-center gap-2 border-b border-gray-400 pb-1 text-gray-900 transition hover:border-gray-600">
                                <span class="text-sm font-medium"> Filtros y ordenación </span>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-4 rtl:rotate-180">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>

                        <div class="hidden sm:flex sm:gap-4">
                            <!-- Valoraciones-->
                            <div class="relative">
                                <details class="group [&_summary::-webkit-details-marker]:hidden">
                                    <summary
                                        class="flex cursor-pointer items-center gap-2 border-b border-gray-400 pb-1 text-gray-900 transition hover:border-gray-600">
                                        <span class="text-sm font-medium"> Valoración </span>

                                        <span class="transition group-open:-rotate-180">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="size-4">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </span>
                                    </summary>

                                    <div
                                        class="z-50 group-open:absolute group-open:top-auto group-open:mt-2 ltr:group-open:start-0">
                                        <div class="w-96 rounded border border-gray-200 bg-white">

                                            <ul class="space-y-1 border-t border-gray-200 p-4">
                                                <li>
                                                    <label for="easy" class="inline-flex items-center gap-2">
                                                        <input
                                                            type="checkbox"
                                                            id="easy"
                                                            name="easy"
                                                            class="size-5 rounded border-gray-300" />

                                                        <span class="text-sm font-medium text-gray-700"> ⭐ </span>
                                                    </label>
                                                </li>

                                                <li>
                                                    <label for="medium" class="inline-flex items-center gap-2">
                                                        <input
                                                            type="checkbox"
                                                            id="medium"
                                                            name="medium"
                                                            class="size-5 rounded border-gray-300" />

                                                        <span class="text-sm font-medium text-gray-700"> ⭐⭐ </span>
                                                    </label>
                                                </li>

                                                <li>
                                                    <label for="hard" class="inline-flex items-center gap-2">
                                                        <input
                                                            type="checkbox"
                                                            id="hard"
                                                            name="hard"
                                                            class="size-5 rounded border-gray-300" />

                                                        <span class="text-sm font-medium text-gray-700"> ⭐⭐⭐</span>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </details>
                            </div>
                            <!-- Dificultad -->
                            <div class="relative">
                                <details class="group [&_summary::-webkit-details-marker]:hidden">
                                    <summary
                                        class="flex cursor-pointer items-center gap-2 border-b border-gray-400 pb-1 text-gray-900 transition hover:border-gray-600">
                                        <span class="text-sm font-medium"> Dificultad </span>

                                        <span class="transition group-open:-rotate-180">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="size-4">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </span>
                                    </summary>

                                    <div
                                        class="z-50 group-open:absolute group-open:top-auto group-open:mt-2 ltr:group-open:start-0">
                                        <div class="w-96 rounded border border-gray-200 bg-white">

                                            <ul class="space-y-1 border-t border-gray-200 p-4">
                                                <li>
                                                    <label for="easy" class="inline-flex items-center gap-2">
                                                        <input
                                                            type="checkbox"
                                                            id="easy"
                                                            name="easy"
                                                            class="size-5 rounded border-gray-300" />

                                                        <span class="text-sm font-medium text-gray-700"> ⭐ </span>
                                                    </label>
                                                </li>

                                                <li>
                                                    <label for="medium" class="inline-flex items-center gap-2">
                                                        <input
                                                            type="checkbox"
                                                            id="medium"
                                                            name="medium"
                                                            class="size-5 rounded border-gray-300" />

                                                        <span class="text-sm font-medium text-gray-700"> ⭐⭐ </span>
                                                    </label>
                                                </li>

                                                <li>
                                                    <label for="hard" class="inline-flex items-center gap-2">
                                                        <input
                                                            type="checkbox"
                                                            id="hard"
                                                            name="hard"
                                                            class="size-5 rounded border-gray-300" />

                                                        <span class="text-sm font-medium text-gray-700"> ⭐⭐⭐</span>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>
                    <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 contenedor-recetas">
                        <!-- siempre las mas recientes se ponen las primeras -->
                        <?php foreach ($recetasPagina as $recipe) {
                            $_SESSION['id_recipe'] = $recipe['id_recipe'];
                        ?>
                            <li>
                                <a class="group block overflow-hidden" href="<?php echo isset($menuAdmin) ? '/recipes/edit' : '/recipes/view'; ?>">
                                    <img
                                        src=<?php echo "../../../assets/img/recipes/" . "{$recipe['image_recipe']}"; ?>
                                        alt=""
                                        class="h-[350px] w-full object-cover transition duration-500 group-hover:scale-105 sm:h-[450px]" />

                                    <div class="relative bg-white pt-3">
                                        <h3 class="text-xs text-gray-700 group-hover:underline group-hover:underline-offset-4">
                                            <?php echo "{$recipe['title']}"; ?>
                                        </h3>

                                        <p class="mt-2">
                                            <span class="sr-only"> Dificultad </span>

                                            <span class="tracking-wider text-gray-900">
                                                <?php if ($recipe['difficulty'] === 1) {
                                                    echo "⭐";
                                                } elseif ($recipe['difficulty'] === 2) {
                                                    echo "⭐⭐";
                                                } elseif ($recipe['difficulty'] === 3) {
                                                    echo "⭐⭐⭐";
                                                } ?> </span>
                                        </p>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <ol class="mt-8 flex justify-center gap-1 text-xs font-medium">
                        <!-- Botón de página anterior -->
                        <?php if ($paginaActual > 1): ?>
                            <li>
                                <form method="post" action="">
                                    <input type="hidden" name="pagina" value="<?php echo $paginaActual - 1; ?>">
                                    <button type="submit" class="inline-flex size-8 items-center justify-center rounded border border-gray-100">
                                        <span class="sr-only">Página Anterior</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                    </button>
                                </form>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li>
                                <?php if ($i == $paginaActual): ?>
                                    <span class="block size-8 rounded border-black bg-black text-center leading-8 text-white"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="pagina" value="<?php echo $i; ?>">
                                        <button type="submit" class="block size-8 rounded border border-gray-100 text-center leading-8">
                                            <?php echo $i; ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </li>
                        <?php endfor; ?>

                        <?php if ($paginaActual < $totalPaginas): ?>
                            <li>
                                <form method="post" action="">
                                    <input type="hidden" name="pagina" value="<?php echo $paginaActual + 1; ?>">
                                    <button type="submit" class="inline-flex size-8 items-center justify-center rounded border border-gray-100">
                                        <span class="sr-only">Página Siguiente</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                    </button>
                                </form>
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