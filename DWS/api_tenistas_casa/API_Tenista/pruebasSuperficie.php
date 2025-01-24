<?php

require_once("../utiles/config.php");
require_once("../utiles/funciones.php");
$datos = "";

// ESCRIBA AQUI EL CÓDIGO PHP NECESARIO
$conexion = conectarPDO($database);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $datos = json_decode(file_get_contents('php://input'), true);
    $superficieId = isset($datos['id']) ? $datos['id'] : null;
    $nuevoNombre = isset($datos['nombre']) ? $datos['nombre'] : null;

    echo var_dump($datos) . "<br>";
    echo "Id de la superficie: " . $superficieId . "<br>";
    echo "Nombre de la superficie: " .$nuevoNombre . "<br>";
}
?>