<?php
require_once __DIR__ . '/../../controllers/recipes_controller.php';
require_once __DIR__ . '/../../controllers/ingredients_controller.php';

$recipes = new Recipes($pdo);
$ingredients = new Ingredients($pdo);

// $createRecipe = $recipes->createRecipe();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mis recetas</title>
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
                    <a href="/profile" class="text-sm/6 font-semibold text-purple-800 hover:text-lg"><?php echo ($_SESSION['username']) ?></a>
                    <a href="/recipes" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">otras recetas</a>
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
                                <a href="/recipes"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">mis recetas</a>
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
            <div class="flex flex-wrap gap-4 lg:gap-8 mb-8">
                <div class="w-full lg:w-1/4 rounded-lg overflow-hidden">
                    <a href="#" class="block rounded-lg shadow-sm shadow-indigo-100">
                        <img
                            alt=""
                            src="https://images.unsplash.com/photo-1613545325278-f24b0cae1224?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1770&q=80"
                            class="h-56 w-full object-cover rounded-t-md" />

                        <div class="p-4 bg-white rounded-b-lg">
                            <dl>
                                <div>
                                    <dt class="sr-only">Recipe type</dt>
                                    <dd class="text-sm text-gray-500">tipo de receta</dd>
                                </div>
                                <div>
                                    <dt class="sr-only">Name</dt>
                                    <dd class="font-medium">Nombre de la receta</dd>
                                </div>
                            </dl>

                            <div class="mt-6 flex items-center gap-8 text-xs">
                                <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                                    <svg
                                        class="w-4 h-4 text-indigo-700"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                    </svg>
                                    <div class="mt-1.5 sm:mt-0">
                                        <p class="text-gray-500">Parking</p>
                                        <p class="font-medium">2 spaces</p>
                                    </div>
                                </div>
                                <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                                    <svg
                                        class="w-4 h-4 text-indigo-700"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    <div class="mt-1.5 sm:mt-0">
                                        <p class="text-gray-500">Bathroom</p>
                                        <p class="font-medium">2 rooms</p>
                                    </div>
                                </div>
                                <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                                    <svg
                                        class="w-4 h-4 text-indigo-700"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                    <div class="mt-1.5 sm:mt-0">
                                        <p class="text-gray-500">Bedroom</p>
                                        <p class="font-medium">4 rooms</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


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