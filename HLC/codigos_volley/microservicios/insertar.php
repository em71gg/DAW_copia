<?php

include 'conexion.php';
$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$salario=$_POST['salario'];
//$fabricante=$_POST['fabricante'];
//al microservicio no le interesa como se llaman las columnas de mi base de datos, está haciendo el insert por orden de columna 
$consulta="insert into personal values('".$codigo."', '".$nombre."','".$salario."')";
mysqli_query($conexion, $consulta) or die(mysqli_error());
mysqli_close($conexion);

?>