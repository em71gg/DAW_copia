<?php
// Activa las sesiones
session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["email"])) header("Location: login.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privado</title>
</head>
<body>
<p>
¡Te encuentras en una zona secreta!, solo visible por una persona identificada.
</p>
<?php
//echo "<p>Hash: " . password_hash('12345', PASSWORD_BCRYPT);

?>
<p>
<a href="cerrar-sesion.php">Cerrar sesión</a>
</p>
</body>
</html>