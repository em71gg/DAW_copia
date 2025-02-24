<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id'])){//consulta para seleccionar un elemento por id
        //Mostrar un mensaje
            $select = "SELECT 
                        a.nombre as Nombre,
                        a.apellidos as Apellidos,
                        a.edad as Edad,
                        COALESCE(e.nombre, '') as Actividad,
                        COALESCE(u.nombre, '') as Aula
                        FROM alumnos a
                        LEFT JOIN clases c ON a.id = c.alumno_id
                        LEFT JOIN extraescolar e ON c.alumno_id = e.id
                        LEFT JOIN ubicacion u ON e.id = u.extraescolar_id
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

    if(isset($_GET['conExtra'])){//ejemplo para listado que cunpla una condición diferente al id
                            //el id se pasa por postman creando la clave 'conExtra'con valor 1

        $consulta = $conexion->prepare("SELECT
                                        a.nombre as Nombre,
                                        a.apellidos as Apellidos,
                                        a.edad as Edad,
                                        COALESCE(e.nombre, '') as Actividad,
                                        COALESCE(u.nombre, '') as Aula
                                        FROM alumnos a
                                        JOIN clases c ON a.id = c.alumno_id
                                        JOIN extraescolar e ON c.alumno_id = e.id
                                        LEFT JOIN ubicacion u ON e.id = u.extraescolar_id
                                        ");
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

    $consulta = $conexion->prepare("SELECT
                                        a.nombre as Nombre,
                                        a.apellidos as Apellidos,
                                        a.edad as Edad,
                                        COALESCE(e.nombre, '') as Actividad,
                                        COALESCE(u.nombre, '') as Aula
                                        FROM alumnos a
                                        LEFT JOIN clases c ON a.id = c.alumno_id
                                        LEFT JOIN extraescolar e ON c.alumno_id = e.id
                                        LEFT JOIN ubicacion u ON e.id = u.extraescolar_id
                                    ");
    $consulta->execute();
    $registros = [];//Array que recojerá los registros de la consulta y será codificado en json como respuesta
    while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
        $registros[] = $registro;
    }
    salidaDatos(
        json_encode($registros),
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
    );
}



?>