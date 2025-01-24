<?php
$hostmane = 'localhost';
$database='empresa';
$username='root';
$password= "";

$conexion = new mysqli($hostmane,$username,$password,$database);

if($conexion -> connect_errno){
    echo"No se puede conectar";
}

?>