<?php
require_once('./utiles/funciones.php');
$contraseña = 123;
echo "<p>Hash para $contraseña: " . password_hash($contraseña, PASSWORD_BCRYPT);

?>