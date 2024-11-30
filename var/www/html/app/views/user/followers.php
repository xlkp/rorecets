<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/profile_controller.php';

if (isset($_GET['user']) && $_GET['user'] !== '') {
    $id_user = $_GET['user'];
    $profileController = new ProfileController($pdo);
    $userData = $profileController->getUserDataById($id_user);
    $mySelf = $profileController->getUserDataByName($_SESSION['username']);
    // me puede servir para saber a los seguidores a los que sigo tambien
    $followed = $profileController->getFollowed($mySelf['id_user']);

    // my followers
    $followers = $profileController->getFollowers($mySelf['id_user']);
    $countFollowers = count($followers);

    if (isset($_POST['deleteFollower'])) {
        $profileController->deleteFollower($_POST['user_id'], $mySelf['id_user']);
        header('Location: /followers?user=' . $mySelf['id_user']);
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
</head>

<body>
    <div class="bg-white overflow-hidden h-screen">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="/profile?user=<?php echo $mySelf['id_user']; ?>" class="text-sm/6 font-semibold text-gray-800 hover:text-lg">MI PERFIL</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <form action="auth" method="post">
                        <input type="submit" name="closeSession" value="Cerrar sesión"
                            class="text-sm/6 font-semibold text-gray-800 hover:text-lg"> <span
                            aria-hidden="true">&rarr;</span></input>
                    </form>
                </div>
            </nav>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-6">Mis seguidores (<?php echo $countFollowers; ?>)</h2>
                    <ul class="space-y-4">
                        <?php foreach ($followers as $follower): ?>
                            <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <a href="/profile?user=<?php echo $follower['id_user']; ?>"
                                    class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                    <?php echo $follower['username']; ?>
                                </a>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $follower['id_user']; ?>">
                                    <button type="submit"
                                        name="deleteFollower"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                        Eliminar seguidor
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
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