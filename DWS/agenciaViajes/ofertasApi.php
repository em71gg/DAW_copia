<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);


if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id'])){
        //Mostrar un mensaje
            
            $consulta = $conexion->prepare("SELECT *	FROM ofertas WHERE id = :id");
            $consulta->bindParam(':id', $_GET['id']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }   else{
                    salidaDatos('', array('HTTP/1.1 404 Not Found'));
                }
        } elseif(isset($_GET['categoria_id'])){

        }else {
            //Mostrar lista de mensajes
            
            $consulta = $conexion->prepare("SELECT  * FROM ofertas WHERE categoria_id = :id");
            $consulta -> bindParam(':id', $_GET['categoría_id']);
            $consulta->execute();
            $registros = [];
            while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
            }
            salidaDatos(json_encode($registros), 
            array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
    }
salidaDatos('No se ha podido realizar la petición.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>