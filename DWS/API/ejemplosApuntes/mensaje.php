<?php
include "config.php";
include "utils.php";
$dbConexion = conectar($db);
    // listar todos los mensajes o solo uno
if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id'])){
        //Mostrar un mensaje
            $select = "SELECT * FROM mensajes where id=:id";
            $consulta = $dbConexion->prepare($select);
            $consulta->bindParam(':id', $_GET['id']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }   else{
                    salidaDatos('', array('HTTP/1.1 404 Not Found'));
                }
        } else {
            //Mostrar lista de mensajes
            $select = "SELECT * FROM mensajes";
            $consulta = $dbConexion->prepare($select);
            $consulta->execute();
            $registros = [];
            while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
            }
            salidaDatos(json_encode($registros), 
            array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
    }
    //crear un nuevo mensaje
if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $insert = "INSERT INTO mensajes(nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)";
        $consulta = $dbConexion->prepare($insert);
        bindAllParams($consulta, $datos);
        $consulta->execute();
        $mensajeId = $dbConexion -> lastInsertId();
        if($mensajeId) {
            $datos['id'] = $mensajeId;
            salidaDatos(json_encode($datos), 
            array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
    }
    //borrar un mensaje
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $id = $datos['id'];
        $delete = "DELETE FROM mensajes where id=:id";
        $consulta = $dbConexion->prepare($delete);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
        salidaDatos('', array( 'HTTP/1.1 200 OK'));
        }   else {
                salidaDatos('', array('HTTP/1.1 404 Not Found'));
            }
        exit();
    }
        //actualizar un mensaje
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $mensajeId = $datos['id'];
        $campos = getParams($datos);
        $update = "UPDATE mensajes SET $campos WHERE id='$mensajeId'";
        $consulta = $dbConexion->prepare($update);
        bindAllParams($consulta, $datos);
        $consulta->execute();
        
        
        salidaDatos('', array( 'HTTP/1.1 200 OK'));
    }
//En caso de que ninguna de las opciones anteriores se haya ejecutado
        salidaDatos('', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>