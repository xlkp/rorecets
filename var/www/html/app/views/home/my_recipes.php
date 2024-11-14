<?php
require_once __DIR__ . '/../../controllers/recipes_controller.php';

$recipes = new Recipes($pdo);
$userRecipes = $recipes->getUserRecipes();
$allIngredients = $recipes->getAllIngredients();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mis recetas</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>

<body class="bg-white">
    <header class="absolute inset-x-0 top-0 z-50">
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1"></div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="/" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">inicio</a>
                <a href="/profile" class="text-sm/6 font-semibold text-purple-800 hover:text-lg"><?php echo ($_SESSION['username']) ?></a>
                <a href="/recipes" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">otras recetas</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <form action="/auth" method="post">
                    <input type="submit" name="closeSession" value="Cerrar sesión" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">
                    <span aria-hidden="true">&rarr;</span>
                </form>
            </div>
        </nav>
    </header>
    <main class="relative px-6 pt-14 lg:px-8">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
            aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="flex items-center justify-center mt-4">
            <button id="openModalButton"
                class="group inline-block rounded-full bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 p-[2px] hover:text-white focus:outline-none focus:ring active:text-opacity-75">
                <span
                    class="block rounded-full bg-white px-8 py-3 text-sm font-medium group-hover:bg-transparent">
                    Crear receta!
                </span>
            </button>
        </div>
        <div id="modalReceta" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-70 hidden">
            <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 p-6 relative z-80">
                <div>
                    <h2 class="sr-only">Steps</h2>
                    <div>
                        <div class="overflow-hidden rounded-full bg-gray-200">
                            <div class="h-2 w-1/2 rounded-full bg-blue-500"></div>
                        </div>

                        <ol class="mt-4 grid grid-cols-3 text-sm font-medium text-gray-500">
                            <li class="flex items-center justify-start text-blue-600 sm:gap-1.5">
                                <span class="hidden sm:inline">Detalles ⭐</span>
                            </li>

                            <li class="flex items-center justify-center text-blue-600 sm:gap-1.5">
                                <span class="hidden sm:inline">Ingredientes 🥣</span>
                            </li>

                            <li class="flex items-center justify-end sm:gap-1.5">
                                <span class="hidden sm:inline">Instrucciones 📖</span>
                            </li>
                        </ol>
                    </div>
                </div>
                <form id="recipeForm" action="/validarReceta" method="POST" enctype="multipart/form-data">
                    <div id="step1" class="step-content">
                        <h1 class="text-2xl font-bold sm:text-3xl text-center">👩‍🍳 Crea tu propia receta! 👨‍🍳</h1>
                        <p class="text-center mt-4 text-gray-500">Deleita a los demás usuarios con tus dotes culinarias. 😋🍜</p>

                        <div class="mt-8">
                            <label for="recipe_type" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                                <input type="text" name="recipe_type" id="recipe_type" class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0" placeholder="Tipo de receta" required />
                                <span class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">Tipo de receta</span>
                            </label>
                            <label for="title" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600 mt-4">
                                <input type="text" name="title" id="title" class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0" placeholder="Nombre" required />
                                <span class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">Nombre de la receta</span>
                            </label>
                            <label for="description" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600 mt-4">
                                <input type="text" name="description" id="description" class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0" placeholder="Pequeña descripción" required />
                                <span class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">Descripción breve</span>
                            </label>
                        </div>
                    </div>
                    <div id="step2" class="step-content hidden">
                        <h2 class="text-center text-2xl font-bold sm:text-3xl">Ingredientes</h2>
                        <p class="text-center mt-4 text-gray-500">Añade los ingredientes necesarios para tu receta.</p>

                        <div id="ingredientFields" class="mt-4 space-y-4">
                            <?php foreach ($allIngredients as $ingredient): ?>
                                <div class="flex items-center ingredient-row">
                                    <select name="ingredients[]" class="border-gray-200 rounded-md w-full py-2 px-3" required>
                                        <option value="">Selecciona un ingrediente</option>
                                        <option value="<?= $ingredient['id_ingredient'] ?>"><?= $ingredient['name'] ?></option>
                                    </select>
                                    <input type="number" name="quantities[]" class="ml-4 w-20 p-2 border rounded" placeholder="Cantidad" required />
                                    <select name="units[]" class="ml-4 border-gray-200 rounded-md w-32 py-2 px-3" required>
                                        <option value="1">Gramos</option>
                                        <option value="2">Kilogramos</option>
                                        <option value="3">Mililitros</option>
                                        <option value="4">Litros</option>
                                        <option value="5">Cucharadas</option>
                                        <option value="6">Cucharaditas</option>
                                        <option value="7">Tazas</option>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4">
                            <label for="new_ingredient" class="block text-gray-700">Ingrediente personalizado</label>
                            <input type="text" name="new_ingredient" id="new_ingredient" placeholder="Ingrediente personalizado" class="border-gray-200 rounded-md w-full py-2 px-3" />
                        </div>
                    </div>
                    <div id="step3" class="step-content hidden">
                        <h2 class="text-center text-2xl font-bold sm:text-3xl">Instrucciones</h2>
                        <p class="text-center mt-4 text-gray-500">Escribe paso a paso las instrucciones para la preparación.</p>
                        <label for="recipe_image" class="mt-4 block">
                            <span class="text-gray-500">Sube una imagen de tu receta</span>
                            <input type="file" name="recipe_image" id="recipe_image" accept="image/*" class="mt-2" required />
                        </label>
                        <textarea class="w-full rounded-lg border-gray-200 p-3 text-sm" placeholder="Instrucciones para la preparación" rows="5" name="instructions" required></textarea>
                        <label for="difficulty" class="block mt-4 text-gray-700">Nivel de dificultad</label>
                        <select name="difficulty" id="difficulty" class="border-gray-200 rounded-md w-full py-2 px-3" required>
                            <option value="">Selecciona la dificultad</option>
                            <option value="1">Fácil</option>
                            <option value="2">Media</option>
                            <option value="3">Difícil</option>
                        </select>
                    </div>
                    <div class="flex justify-between mt-8">
                        <button type="button" id="prevButton" class="inline-block bg-gray-300 px-5 py-3 text-sm font-medium text-gray-700 hidden">Anterior</button>
                        <button type="button" id="nextButton" class="inline-block bg-blue-500 px-5 py-3 text-sm font-medium text-white">Siguiente</button>
                        <button type="submit" id="submitButton" class="inline-block bg-green-500 px-5 py-3 text-sm font-medium text-white hidden">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if (count($userRecipes) == 0) {
            echo '<h1 class="text-center text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl mt-4">No has creado ninguna receta aún😥</h1>';
        } ?>
        <!-- MIS RECETAS -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8 m-4">
            <?php
            $i = 0;
            foreach ($userRecipes as $recipe) {
                $colSpanClass = ($i % 2 == 0) ? '' : 'lg:col-span-2';
            ?>
                <div class="min-h-32 <?= $colSpanClass ?>">
                    <a href="#" class="block">
                        <img
                            alt=""
                            src=<?php echo "../../../assets/img/recipes/" . "{$recipe['image_recipe']}"; ?>
                            class="h-56 w-full rounded-bl-3xl rounded-tr-3xl object-cover sm:h-64 lg:h-72" />

                        <div class="mt-4 sm:flex sm:items-center sm:justify-center sm:gap-4">
                            <strong class="font-medium"><?php echo "{$recipe['title']}"; ?></strong>

                            <span class="hidden sm:block sm:h-px sm:w-8 sm:bg-yellow-500"></span>

                            <p class="mt-0.5 sm:mt-0"><?php echo "{$recipe['publication_date']}"; ?></p>
                        </div>
                    </a>
                </div>
            <?php
                $i++;
            }
            ?>
        </div>
    </main>
    <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
        aria-hidden="true">
        <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
        </div>
    </div>
</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalReceta = document.getElementById('modalReceta');
        const openModalButton = document.getElementById('openModalButton');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        const progressBar = document.querySelector('.bg-blue-500');
        const submitButton = document.getElementById('submitButton');

        const steps = Array.from(document.querySelectorAll('.step-content'));
        let currentStep = 0;

        openModalButton.addEventListener('click', () => {
            modalReceta.classList.remove('hidden');
            currentStep = 0;
            updateStep();
        });

        function updateStep() {
            steps.forEach((step, index) => {
                step.classList.toggle('hidden', index !== currentStep);
            });

            prevButton.classList.toggle('hidden', currentStep === 0);
            nextButton.textContent = currentStep === steps.length - 1 ? 'Finalizar' : 'Siguiente';
            submitButton.classList.toggle('hidden', currentStep !== steps.length - 1);

            let progressPercent;
            if (currentStep === 0) {
                progressPercent = 10;
            } else if (currentStep === 1) {
                progressPercent = 50;
            } else if (currentStep === 2) {
                progressPercent = 100;
            }
            progressBar.style.width = `${progressPercent}%`;
        }

        nextButton.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
            } else {
                const form = document.querySelector('form');
                if (form) form.submit();

                modalReceta.classList.add('hidden');
                currentStep = 0;
            }
            updateStep();
        });
        prevButton.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
            }
            updateStep();
        });
        window.addEventListener('click', function(event) {
            if (event.target === modalReceta) {
                modalReceta.classList.add('hidden');
            }
        });
        updateStep();
    });
</script>