<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
      
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $id = $datos['id'];

       //Primero hay que borrar las claves forasteras de otras tablas
        $delForeignKey1 = $conexion -> prepare('DELETE FROM clases WHERE extraescolar_id=?');
        $delForeignKey1 -> bindParam(1, $id);
        $delForeignKey1 -> execute();
        $delForeignKey1 = null;

        $delForeignKey2 = $conexion -> prepare('DELETE FROM ubicacion WHERE extraescolar_id=?');
        $delForeignKey2 -> bindParam(1, $id);
        $delForeignKey2 -> execute();
        $delForeignKey2 = null;

        $delete = "DELETE FROM extraescolar where id=:id";
        $consulta = $conexion->prepare($delete);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        
        if ($consulta->rowCount() > 0) {
        salidaDatos('Borrado realizado.', array( 'HTTP/1.1 200 OK'));
        $consulta = null;
        }   
        else {
                salidaDatos('No se encuentra el tenista recibido.', array('HTTP/1.1 404 Not Found'));
        }
       
        exit();

    }

?>