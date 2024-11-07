<?php
require __DIR__ . '/../../config/config.php';

$username = $_POST['username'];
if (isset($_POST['logged_in'])) {
	$response = $pdo->query("SELECT pwd FROM users WHERE username='$username'");
	$hash = ($response->fetch());
	if ($hash) {
		$pwdValidate = $_POST['pwd'];

		if (password_verify($pwdValidate, $hash['pwd'])) {
			session_start();
			$_SESSION['logeado'] = true;
			header("Location: /home");
			exit;
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
		$pwdHashed = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
		$email = $_POST['mail'];
		$exp = $_POST['exp'];
		$sql = "INSERT INTO users (username,pwd,email,exp) values ('$username','$pwdHashed','$email','$exp')";
		$pdo->prepare($sql)->execute();
		echo "Registro correcto!";
	}
} else if (isset($_POST['pwdChange'])) {
	$response = $pdo->query("SELECT pwd FROM users WHERE username='$username'");
	$hash = ($response->fetch());
	if ($hash) {
		$pwdValidate = $_POST['pwd'];
		if (password_verify($pwdValidate, $hash['pwd'])) {
			$pwdNewHashed = password_hash($_POST['pwdNew'], PASSWORD_DEFAULT);
			$sql = "UPDATE users SET pwd='$pwdNewHashed' WHERE username='$username'";
			//$pdo->query($sql)->fetch();
			$pdo->prepare($sql)->execute();
			$_SESSION['logged_in'] = true;
			header("Location: /home");
		} else {
			echo "La contraseña actual es incorrecta";
		}
	} else {
		echo "Ese usuario no existe";
	}
} else {
	//devuelvo error
	http_response_code(403);
}
