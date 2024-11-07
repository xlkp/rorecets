<?php
require __DIR__ . '/../../config/config.php';
//Si existe la componente 'login' en el formulario...
$username = $_POST['username'];
if (isset($_POST['logged_in'])) {
	//Hacemos la consulta de SQL, comillas en la variable del where incluidas
	//SELECT pwd FROM usuarios WHERE username="pepito";, no SELECT ... WHERE username=pepito;,
	$response = $pdo->query("SELECT pwd FROM users WHERE username='$username'");
	//Hacemos fetch del resultado, si me devuelve false es que no hay
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
	//intento registro
	$response = $pdo->query("SELECT pwd FROM user WHERE username='$username'");
	//Hacemos fetch del resultado, si me devuelve false es que no hay
	$hash = ($response->fetch());
	//si el usuario existe -> error
	if ($hash) {
		echo "Ese usuario ya existe";
	}
	//si no existe -> insertar
	else {
		$pwdHashed = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
		$mail = $_POST['mail'];
		$sql = "INSERT INTO users (username,pwd,mail) values ('$username','$pwdHashed','$mail')";
		$pdo->prepare($sql)->execute();
		echo "Registro correcto!";
	}
} else if (isset($_POST['pwdChange'])) {
	$response = $pdo->query("SELECT pwd FROM user WHERE username='$username'");
	$hash = ($response->fetch());
	//si el usuario existe -> cambiar la contraseña
if ($hash) {
		$pwdValidate = $_POST['pwd'];
		if (password_verify($pwdValidate, $hash['pwd'])) {
			$pwdNewHashed = password_hash($_POST['pwdNew'], PASSWORD_DEFAULT);
			//SQL para modificar el usuario cuyo nombre de usuario es el que viene en formulario
			// para poner su pwd a $contrasenaHasheada
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
