<?php

require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');
/**
* Método que establece la conexión con la base de datos
* @param {string} - Host
* @param {string} - Usuario
* @param {string} - Contraseña
* @param {string} - Esquema de la base de datos
* @return {PDO}
*/
$conexion = conectarPDO($host, $user, $password, $bbdd);
//-----------------------------------------------------
// Variables
//-----------------------------------------------------
$email = isset($_REQUEST["email"]) ? urldecode($_REQUEST["email"]) : "";
$token = isset($_REQUEST["token"]) ? urldecode($_REQUEST["token"]) : "";
$host = "localhost";
$user = "dwes_tarde";
$passwordDB = "dwes_2223";
$bbdd = "dwes_tarde_pruebas";
//-----------------------------------------------------
// COMPROBAR SI SON CORRECTOS LOS DATOS
//-----------------------------------------------------
// Conecta con base de datos
$conexion = conectarPDO($host, $user, $passwordDB, $bbdd);
// Prepara SELECT para obtener la contraseña almacenada del usuario
$select = "SELECT COUNT(*) as numero FROM usuarios WHERE email = :email AND token
= :token AND activo = 0";
$consulta = $conexion->prepare($select);
// Ejecuta consulta
$consulta->execute([
"email" => $email,
"token" => $token
]);
$resultado = $consulta->fetch();
$consulta = null;
// Existe el usuario con el token
if ($resultado["numero"] > 0)
{
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
// No es un usuario válido, le enviamos al formulario de identificación
header('Location: identificarse.php');
exit();
?>