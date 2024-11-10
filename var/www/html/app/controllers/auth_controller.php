<?php
require __DIR__ . '/../../config/config.php';

if (isset($_POST['username'])) {
	$username = $_POST['username'];
	if (isset($_POST['logged_in'])) {
		$response = $pdo->query("SELECT pwd, is_admin FROM users WHERE username='$username'");
		$hash = ($response->fetch());
		if ($hash) {
			$pwdValidate = $_POST['pwd'];
			$admin = $hash['is_admin'];
			if (password_verify($pwdValidate, $hash['pwd'])) {
				session_start();
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['logged_in'] = true;
				if ($admin === 1) {
					$_SESSION['admin'] = true;
					header("Location: /");
					exit;
				} else {
					unset($_SESSION['admin']);
					header("Location: /");
					exit;
				}
			} else {
				echo "La contraseña es incorrecta";
			}
		} else {
			echo "Ese usuario no existe";
		}
	} else if (isset($_POST['register'])) {
		$response = $pdo->query("SELECT pwd FROM users WHERE username='$username'");
		$hash = ($response->fetch());
		if ($hash) {
			echo "Ese usuario ya existe";
		}
		//si no existe -> insertar
		else {
			if ($_POST['pwd'] === $_POST['pwdVerify']) {
				$pwdHashed = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
				$email = $_POST['email'];
				$exp = $_POST['exp'];
				$sql = "INSERT INTO users (username,pwd,email,exp) values ('$username','$pwdHashed','$email','$exp')";
				$pdo->prepare($sql)->execute();
				header("Location: /login");
				exit;
			} else {
				header("Location: /register");
				exit;
			}
		}
	} else if (isset($_POST['pwdChange'])) {
		$response = $pdo->query("SELECT pwd FROM users WHERE username='$username'");
		$hash = ($response->fetch());
		if ($hash) {
			$pwdValidate = $_POST['pwd'];
			if (password_verify($pwdValidate, $hash['pwd'])) {
				if ($_POST['pwdNew'] === $_POST['pwdVerify']) {
					$pwdNewHashed = password_hash($_POST['pwdNew'], PASSWORD_DEFAULT);
					$sql = "UPDATE users SET pwd='$pwdNewHashed' WHERE username='$username'";
					$pdo->prepare($sql)->execute();
					header("Location: /login");
					exit;
				} else {
					echo 'Las contraseñas no coinciden';
				}
			} else {
				echo "La contraseña actual es incorrecta";
			}
		} else {
			echo "Ese usuario no existe";
		}
	} else {
		header('Location: 404');
		exit;
	}
} else {
	if (isset($_POST['closeSession'])) {
		session_start();
		session_unset();
		session_destroy();
		header("Location: /login");
		exit;
	}
	header('Location: 404');
	exit;
}
