<?php

require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');
$conexion = conectarPDO($host, $user, $password, $bbdd);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) { //consulta para obtener una oferta filtrada por id
        $registros = [];
        $consulta = $conexion->prepare("SELECT * FROM ... where id=:id");
        $consulta->bindParam(':id', $_GET['id']);
        $consulta->execute();
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        if (count($registros) == 0) {
            salidaDatos('No existe ninguna oferta de la categoría '. $_GET['categoria'] . '.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    } else {
        $consulta = $conexion->prepare("SELECT * FROM ofertas");
        $consulta->execute();
        $registros = [];
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }
}

?>
