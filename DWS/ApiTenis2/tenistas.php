<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id'])){//consulta para seleccionar un elemento por id
        //Mostrar un mensaje
            $select = "SELECT 
                        id as Identificador,
                        nombre as Nombre
                        a.apellidos as Apellidos,
                        altura, as Altura
                        anno_nacimiento as Nacimiento,
                        array con los titulos
                        SELECT JSON_ARRAYAGG(
                        
                        FROM tenistas j
                        JOIN titulos t ON j.id = t.tenista_id
                        JOIN torneos c ON t.torneo_id = c.id
                        WHERE a.id = :id";
            $consulta = $conexion->prepare($select);
            $consulta->bindParam(':id', $_GET['id']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else{
                salidaDatos('No se encuentra un registro con id='.$_GET["id"].'.', array('HTTP/1.1 404 Not Found'));
            }
    }

}



?>