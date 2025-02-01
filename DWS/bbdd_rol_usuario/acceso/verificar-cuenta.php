<?php
session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["email"])) header("Location: ../login.php");
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');
//-----------------------------------------------------
// Variables
//-----------------------------------------------------
$email = isset($_REQUEST["email"]) ? urldecode($_REQUEST["email"]) : "";
$token = isset($_REQUEST["token"]) ? urldecode($_REQUEST["token"]) : "";

//-----------------------------------------------------
// COMPROBAR SI SON CORRECTOS LOS DATOS
//-----------------------------------------------------
// Conecta con base de datos
$conexion = conectarPDO($host, $user, $password, $bbdd);
// Prepara SELECT para obtener la contraseña almacenada del usuario
$select = "SELECT COUNT(*) as numero FROM usuarios WHERE email = :email AND token = :token AND activo = 0";
$consulta = $conexion->prepare($select);
// Ejecuta consulta
$consulta->execute([
  "email" => $email,
  "token" => $token
]);
$resultado = $consulta->fetch();
$consulta = null;
// Existe el usuario con el token
if ($resultado["numero"] > 0) {
  //-----------------------------------------------------
  // ACTIVAR CUENTA
  //-----------------------------------------------------
  // Prepara la actualización 
  $update = "UPDATE usuarios SET activo = 1 WHERE email = :email";
  $consulta = $conexion->prepare($update);
  // Ejecuta actualización
  $consulta->execute([
    "email" => $email
  ]);
  //-----------------------------------------------------
  // REDIRECCIONAR A IDENTIFICACIÓN
  //-----------------------------------------------------
  header('Location: identificarse.php?activada=1');
  exit();
}
// No es un usuario válido, le enviamos al formulario de identificacion
header('Location: identificarse.php');
exit();
