<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/profile_controller.php';


if (isset($_GET['user']) && $_GET['user'] !== '') {
    $id_user = $_GET['user'];
    $profileController = new ProfileController($pdo);
    $userData = $profileController->getUserDataById($id_user);
    $mySelf = $profileController->getUserDataByName($_SESSION['username']);
    $followed = $profileController->getFollowed($mySelf['id_user']);

    // my followers
    $followers = $profileController->getFollowers($mySelf['id_user']);
    $countFollowers = count($followers);

    // user followers
    $userFollowers = $profileController->getFollowers($id_user);
    $countUserFollowers = count($userFollowers);

    $isFollowed = false;

    foreach ($followed as $follower) {
        if ($follower['id_user'] == $id_user) {
            $isFollowed = true;
            break;
        }
    }
    if (isset($_POST['follow']) && !$isFollowed) {
        $profileController->follow($mySelf['id_user'], $_POST['user_id']);
        header('Location: /profile?user=' . $_POST['user_id']);
        exit();
    }

    if (isset($_POST['unfollow']) && $isFollowed) {
        $profileController->unfollow($mySelf['id_user'], $_POST['user_id']);
        header('Location: /profile?user=' . $_POST['user_id']);
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
                    <a href="/" class="text-lg/6 font-semibold text-blue-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105 hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">INICIO</a>
                    <a href="recipes" class="text-lg/6 font-semibold text-yellow-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">RECETAS</a>
                    <?php if ($userData['username'] !== $_SESSION['username']) { ?>
                        <a href="/profile?user=<?php echo $mySelf['id_user']; ?>" class="text-lg/6 font-semibold text-red-800 hover:text-lg hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-105">MI PERFIL</a>
                    <?php } ?>
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
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="text-center">
                    <h1 class="text-balance text-5xl font-semibold tracking-tight text-gray-900 sm:text-7xl">
                        <?php echo strtoupper($userData['username']) ?></h1>
                    <p class="mt-8 text-pretty text-lg font-medium text-gray-500 sm:text-xl/8">
                        <?php if ($userData['username'] !== $_SESSION['username']) { ?>
                            <?php if ($countUserFollowers > 1) { ?>
                                <?php echo $countUserFollowers; ?> seguidores
                            <?php } else if ($countUserFollowers > 0) { ?>
                                <?php echo $countUserFollowers;  ?> seguidor
                            <?php } else { ?>
                                No tiene seguidores 😢, sé su primer seguidor!!🎊
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($countFollowers > 1) { ?>
                                Tienes <?php echo $countFollowers; ?> seguidores!! 🙉
                            <?php } else if ($countFollowers > 0) { ?>
                                Tienes <?php echo $countFollowers; ?> seguidor!! 🙉
                            <?php } else { ?>
                                No tienes seguidores 😢
                            <?php } ?>
                        <?php } ?>
                    </p>
                    <?php if ($userData['username'] !== $_SESSION['username']) { ?>
                        <div class="mt-4">
                            <?php if ($isFollowed === false) { ?>
                                <form action="" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $id_user; ?>">
                                    <button type="submit" name="follow" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Seguir</button>
                                </form>
                            <?php } else { ?>
                                <form action="" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $id_user; ?>">
                                    <button type="submit" name="unfollow" class="rounded-md bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Dejar de seguir</button>
                                </form>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="followers?user=<?php echo $mySelf['id_user'] ?>" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Seguidores</a>
                            <a href="profile/edit" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Editar Perfil</a>
                        </div>
                    <?php } ?>
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