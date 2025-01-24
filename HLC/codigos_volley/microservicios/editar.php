<?php

include 'conexion.php';
$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$salario=$_POST['salario'];
//$fabricante=$_POST['fabricante'];

$consulta="update personal set nombre = '".$nombre."', salario='".$salario."' where codigo = '".$codigo."'";
mysqli_query($conexion, $consulta) or die(mysqli_error());
mysqli_close($conexion);

?>

