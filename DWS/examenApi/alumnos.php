<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');
$conexion = conectarPDO($host, $user, $password, $bbdd);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $consulta = $conexion->prepare("SELECT
                                        a.nombre as Nombre,
                                        a.apellidos,
                                        COALESCE(e.nombre, '') as Actividad,
                                        u.nombre as Aula
                                        FROM alumnos a
                                        JOIN clases c ON a.id = c.alumno_id
                                        JOIN extraescolar e ON c.extraescolar_id = e.id
                                        JOIN ubicacion u ON e.id = u.extraescolar_id");
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

?>