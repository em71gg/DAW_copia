<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);


if ($_SERVER['REQUEST_METHOD'] == 'GET') { //consulta para obtener una oferta filtrada por id
    if (isset($_GET['id'])) {
        //Mostrar un mensaje

        $consulta = $conexion->prepare("SELECT * FROM ofertas where id=:id");
        $consulta->bindParam(':id', $_GET['id']);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
            salidaDatos(
                json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            salidaDatos('Error', array('HTTP/1.1 404 Not Found'));
        }
    } elseif (isset($_GET['categoria'])) { //consulta pra obtener un listado de ofertas por 
        $consulta = $conexion->prepare("SELECT 
                                                o.nombre, o.descripcion, o.fecha_actividad
                                            FROM ofertas o
                                            JOIN categorias c ON o.categoria_id = c.id
                                            WHERE c.categoria = ?");
        $consulta->bindParam(1, $_GET['categoria']);
        $consulta->execute();
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    } 
    elseif(isset($_GET['displonible'])){
        $consulta = $conexion->prepare("SELECT 
                                            o.nombre,
                                            o.fecha_actividad,
                                            o.aforo - COALESCE(COUNT(s.oferta_id), 0) AS plazas_disponibles
                                        FROM ofertas o
                                        LEFT JOIN solicitudes s ON o.id = s.oferta_id
                                        GROUP BY o.nombre
                                        HAVING plazas_disponibles > 0");
        
        $consulta->execute();
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }
    else {
        //Mostrar lista de mensajes

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


//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('Error', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>