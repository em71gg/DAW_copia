<?php
// Activa las sesiones
session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión 'email', en caso contrario vuelve a la página de 
//identificación
if (!isset($_SESSION['email'])) 
{
    header('Location: identificarse.php');
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>>Panel secreto</title>
</head>
<body>
   <h1>Tu panel secreto</h1>
</body>
</html>
