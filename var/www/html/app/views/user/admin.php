<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/admin_controller.php';
require_once __DIR__ . '/../../controllers/profile_controller.php';

if (isset($_SESSION['username'])) {
    $profileController = new ProfileController($pdo);
    $adminController = new AdminController($pdo);

    $mySelf = $profileController->getUserDataByName($_SESSION['username']);
    $users = $adminController->getAllUsers();
    $recipes = $adminController->getAllRecipes();
    $comments = $adminController->getAllComments();
    $ratings = $adminController->getAllRatings();

    if (isset($_POST['deleteUser'])) {
        $adminController->deleteUser($_POST['user_id']);
        header('Location: /admin');
        exit();
    }

    if (isset($_POST['deleteRecipe'])) {
        $adminController->deleteRecipe($_POST['recipe_id']);
        header('Location: /admin');
        exit();
    }

    if (isset($_POST['deleteComment'])) {
        $adminController->deleteComment($_POST['comment_id']);
        header('Location: /admin');
        exit();
    }

    if (isset($_POST['deleteRating'])) {
        $adminController->deleteRating($_POST['rating_id']);
        header('Location: /admin');
        exit();
    }

    // he metido que se puedan quitar de administradores a ellos mismos y a otros
    // administradores, porque aun no hay roles de superadmin y beyond
    if (isset($_POST['toggleAdmin']) && isset($_POST['user_id'])) {
        $user = $profileController->getUserDataById($_POST['user_id']);
        $adminController->toggleAdmin($user);
        header('Location: /admin');
        exit();
    }
} else {
    header('Location: /404');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rorecets</title>

    <script
        src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.classList.toggle('hidden');
        }
    </script>
</head>

<body class="overflow-x-hidden">
    <div class="bg-white  h-screen">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="/" class="text-lg/6 font-semibold text-purple-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">INICIO</a>
                    <a href="/recipes" class="text-lg/6 font-semibold text-blue-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">RECETAS</a>
                    <a href="/recipes/mine" class="text-lg/6 font-semibold text-yellow-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">CREAR RECETA</a>
                    <a href="/profile?user=<?php echo $mySelf['id_user']; ?>" class="text-lg/6 font-semibold text-red-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">MI PERFIL</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <form action="auth" method="post">
                        <input type="submit" name="closeSession" value="Cerrar sesión"
                            class="text-lg/6 font-semibold text-gray-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105"> <span
                            aria-hidden="true">&rarr;</span></input>
                    </form>
                </div>
            </nav>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu  blur-3xl sm:-top-80"
                aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="text-center">
                    <div class="space-y-8">
                        <!-- Users Section -->
                        <div class="border rounded-lg p-4">
                            <button class="w-full text-left text-2xl font-bold mb-4 focus:outline-none" onclick="toggleSection('users')">
                                Usuarios
                            </button>
                            <div id="users" class="hidden">
                                <?php foreach ($users as $user): ?>
                                    <div class="flex justify-between items-center p-2 border-b">
                                        <span><a href="/profile?user=<?php echo $user['id_user']; ?>"><?php echo ($user['username']); ?></a></span>
                                        <div class="flex gap-2">
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>">
                                                <?php if ($user['is_admin'] == 1): ?>
                                                    <button type="submit" name="toggleAdmin" class="bg-yellow-500 text-white px-4 py-2 rounded">
                                                        Quitar Admin
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit" name="toggleAdmin" class="bg-green-500 text-white px-4 py-2 rounded">
                                                        Hacer Admin
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>">
                                                <button type="submit" name="deleteUser" class="bg-red-500 text-white px-4 py-2 rounded">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Recipes Section -->
                        <div class="border rounded-lg p-4">
                            <button class="w-full text-left text-2xl font-bold mb-4 focus:outline-none" onclick="toggleSection('recipes')">
                                Recetas
                            </button>
                            <div id="recipes" class="hidden">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b"></tr>
                                        <th class="text-center p-2">Título</th>
                                        <th class="text-center p-2">Usuario</th>
                                        <th class="text-center p-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recipes as $recipe): ?>
                                            <tr class="border-b">
                                                <td class="p-2"><a href="/recipes/view?id_recipe=<?php echo $recipe['id_recipe']; ?>"><?php echo ($recipe['title']); ?></a></td>
                                                <td class="p-2">
                                                    <a href="/profile?user=<?php echo $recipe['id_user']; ?>"><?php echo $username = $adminController->getUsername($recipe['id_user']); ?>
                                                    </a>
                                                </td>
                                                <td class="p-2">
                                                    <form method="POST" class="inline">
                                                        <input type="hidden" name="recipe_id" value="<?php echo $recipe['id_recipe']; ?>">
                                                        <button type="submit" name="deleteRecipe" class="bg-red-500 text-white px-4 py-2 rounded">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="border rounded-lg p-4">
                            <button class="w-full text-left text-2xl font-bold mb-4 focus:outline-none" onclick="toggleSection('comments')">
                                Comentarios
                            </button>
                            <div id="comments" class="hidden">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b"></tr>
                                        <th class="text-center p-2">Comentario</th>
                                        <th class="text-center p-2">Receta</th>
                                        <th class="text-center p-2">Usuario</th>
                                        <th class="text-center p-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($comments as $comment): ?>
                                            <tr class="border-b"></tr>
                                            <td class="p-2"><a href="/recipes/view?id_recipe=<?php echo $comment['id_recipe']; ?>"><?php echo ($comment['description']); ?></a></td>
                                            <td class="p-2"><a href="/recipes/view?id_recipe=<?php echo $comment['id_recipe']; ?>"><?php echo ($comment['id_recipe']); ?></a></td>
                                            <td class="p-2"><a href="/profile?user=<?php echo $comment['id_user']; ?>"><?php echo $username = $adminController->getUsername($comment['id_user']); ?></a></td>
                                            <td class="p-2">
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id_comment']; ?>">
                                                    <button type="submit" name="deleteComment" class="bg-red-500 text-white px-4 py-2 rounded">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Ratings Section -->
                        <div class="border rounded-lg p-4">
                            <button class="w-full text-left text-2xl font-bold mb-4 focus:outline-none" onclick="toggleSection('ratings')">
                                Valoraciones
                            </button>
                            <div id="ratings" class="hidden">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-center p-2">Valoración</th>
                                            <th class="text-center p-2">Receta</th>
                                            <th class="text-center p-2">Usuario</th>
                                            <th class="text-center p-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ratings as $rating): ?>
                                            <tr class="border-b">
                                                <td class="p-2"><a href="/recipes/view?id_recipe=<?php echo $rating['id_recipe']; ?>">
                                                        <?php
                                                        switch ($rating['score']) {
                                                            case 1:
                                                                echo '⭐';
                                                                break;
                                                            case 2:
                                                                echo '⭐⭐';
                                                                break;
                                                            case 3:
                                                                echo '⭐⭐⭐';
                                                                break;
                                                            case 4:
                                                                echo '⭐⭐⭐⭐';
                                                                break;
                                                            case 5:
                                                                echo '⭐⭐⭐⭐⭐';
                                                                break;
                                                        }
                                                        ?></a></td>
                                                <td class="p-2"><a href="/recipes/view?id_recipe=<?php echo $rating['id_recipe']; ?>"><?php echo $rating['id_recipe']; ?></a></td>
                                                <td class="p-2"><a href="/profile?user=<?php echo $rating['id_user']; ?>"><?php echo $username = $adminController->getUsername($rating['id_user']); ?></a></td>
                                                <td class="p-2">
                                                    <form method="POST" class="inline">
                                                        <input type="hidden" name="rating_id" value="<?php echo $rating['id_rating']; ?>">
                                                        <button type="submit" name="deleteRating" class="bg-red-500 text-white px-4 py-2 rounded">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu  blur-3xl sm:top-[calc(100%-30rem)]"
                        aria-hidden="true">
                        <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
                            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>