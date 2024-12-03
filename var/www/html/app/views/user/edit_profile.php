<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/profile_controller.php';

$profileController = new ProfileController($pdo);
$mySelf = $profileController->getUserDataByName($_SESSION['username']);
$usernameError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_username'])) {
        $new_username = $_POST['username'];
        if ($new_username === $mySelf['username']) {
            // no lo cambia jeje
            header('Location: /profile/edit');
            exit();
        }
        $result = $profileController->updateUsername($mySelf['id_user'], $new_username);
        if ($result) {
            $_SESSION['username'] = $new_username;
            header('Location: /profile/edit');
            exit();
        } else {
            $usernameError = 'El nombre de usuario no está disponible.';
        }
    }

    if (isset($_POST['update_email'])) {
        $new_email = $_POST['email'];
        $profileController->updateEmail($mySelf['id_user'], $new_email);
        header('Location: /profile/edit');
        exit();
    }


    if (isset($_POST['action']) && $_POST['action'] === 'delete_account') {
        $password = $_POST['password'] ?? '';
        if (empty($password)) {
            $passwordError = 'La contraseña es requerida';
        } else {
            if ($profileController->checkPassword($mySelf['id_user'], $password)) {
                $profileController->deleteAccount($mySelf['id_user']);
                session_destroy();
                header('Location: /');
                exit();
            } else {
                $passwordError = 'Contraseña incorrecta';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <script
        src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>

<body class="overflow-x-hidden">
    <div class=" bg-white h-screen">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="/" class="text-lg/6 font-semibold text-blue-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">INICIO</a>
                    <a href="/recipes" class="text-lg/6 font-semibold text-yellow-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">RECETAS</a>
                    <a href="/profile?user=<?php echo $mySelf['id_user']; ?>" class="text-lg/6 font-semibold text-red-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">MI PERFIL</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <form action="/auth" method="post">
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
                    <h1 class="text-balance text-5xl font-semibold tracking-tight text-gray-900 sm:text-7xl">
                        Editar Perfil
                    </h1>
                    <form action="" method="post" class="mt-10">
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Nuevo Nombre de Usuario</label>
                            <input type="text" name="username" id="username" value="<?php echo $mySelf['username']; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <?php if ($usernameError): ?>
                                <p class="text-red-600 mt-2"><?php echo $usernameError; ?></p>
                            <?php endif; ?>
                        </div>
                        <button type="submit" name="update_username" class="mt-2 w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Actualizar Nombre de Usuario
                        </button>
                    </form>

                    <form action="" method="post" class="mt-6">
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Nuevo Email</label>
                            <input type="email" name="email" id="email" value="<?php echo $mySelf['email']; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <button type="submit" name="update_email" class="mt-2 w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Actualizar Email
                        </button>
                    </form>

                    <button class="w-full rounded-md bg-yellow-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-yellow-800 mt-6">
                        <a href="/change_password">Cambiar Contraseña</a>
                    </button>
                    <button type="button" id="openDeleteModal" class="w-full rounded-md bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-800 mt-6">
                        Eliminar Cuenta
                    </button>
                    <?php if (isset($passwordError)): ?>
                        <p class="text-red-600 mt-2"><?php echo $passwordError; ?></p>
                    <?php endif; ?>
                    <div id="deleteAccountModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">¿Estás seguro?</h3>
                                <form method="post" action="">
                                    <div class="mt-2 px-7 py-3">
                                        <p class="text-sm text-gray-500">Esta acción no se puede deshacer.</p>
                                        <input type="hidden" name="action" value="delete_account">
                                        <input type="password" name="password" placeholder="Ingresa tu contraseña"
                                            class="mt-4 w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <button type="submit" id="deleteConfirm" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700">
                                            Confirmar eliminación
                                        </button>
                                        <button type="button" id="deleteCancel" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
    <script>
        document.getElementById('openDeleteModal').addEventListener('click', function() {
            document.getElementById('deleteAccountModal').classList.remove('hidden');
        });

        document.getElementById('deleteCancel').addEventListener('click', function() {
            document.getElementById('deleteAccountModal').classList.add('hidden');
        });
    </script>
</body>

</html>