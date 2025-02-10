<?php
require_once("../funciones.php");

$conexion = conectar_pdo($host, $user, $password, $bbdd);
$mensajesError = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        //Mostrar un mensaje
                $select = " SELECT
                
                e.id AS Id,
                e.nombre AS Nombre,
                e.edad_minima AS `Edad mínima`,
                d.nombre AS `Nombre del deporte`,
                d.numero_jugadores AS `Número de jugadores del deporte`,
                IF(
                    COUNT(a.id) > 0, 
                    JSON_ARRAYAGG(
                    /*Si quiero salida array*/
                        /*JSON_ARRAY(
                            a.nombre,
                            a.apellidos,
                            a.edad
                        )*/
                        /*si quiero salida json object*/
                        JSON_OBJECT(
                            'nombre', a.nombre,
                            'apellidos', a.apellidos,
                            'edad', a.edad
                        )
                    ),
                    '[]'
                ) AS Alumnos
            FROM 
                equipos e
            LEFT JOIN 
                deportes d ON e.deporte_id = d.id
            LEFT JOIN 
                equipos_alumnos ea ON e.id = ea.equipo_id
            LEFT JOIN 
                alumnos a ON ea.alumno_id = a.id
            WHERE 
                e.id = :id
            GROUP BY 
                e.id
                ";

					
        $consulta = $conexion->prepare($select);
        $consulta->bindParam(':id', $_GET['id']);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
            $datosConsulta = $consulta->fetch(PDO::FETCH_ASSOC);
            $datosConsulta['Alumnos'] = json_decode($datosConsulta['Alumnos'], true);
            salidaDatos(
                json_encode($datosConsulta),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            salidaDatos('', array('HTTP/1.1 404 Not Found'));
        }
    } else {
        //Mostrar lista de mensajes
        $select = "SELECT  
						e.id AS Id,
						e.nombre AS Nombre,
						e.edad_minima AS `Edad mínima`,
						d.nombre AS `Nombre del deporte`
						
						FROM 
							equipos e
						JOIN 
							deportes d ON e.deporte_id = d.id
						ORDER BY e.id
						";
        $consulta = $conexion->prepare($select);
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
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);
    $equipoId = isset($datos['id']) ? $datos['id'] : null;
    $edadMinima = isset($datos['edad_minima']) ? $datos['edad_minima'] : null;
    $deporteId = isset($datos['deporte_id']) ? $datos['deporte_id'] : null;
    $nombreId = isset($datos['nombre']) ? $datos['nombre'] : null;


    if ($equipoId !== null) {
        //Verificar si el equipo existe
        $consultaExiste = $conexion->prepare("SELECT * FROM equipos WHERE id = ?");
        $consultaExiste->bindParam(1, $equipoId);
        $consultaExiste->execute();


        if ($consultaExiste->rowCount() == 0) { //si no existe el equipo cargamos aviso de error
            $mensajesError[] = "No existe ese equipo.";
           
        }
        // Verificar si el equipo tiene alumnos asociados
        $consultaAlumnos = $conexion->prepare("SELECT COUNT(*) AS total FROM equipos_alumnos WHERE equipo_id = ?");
        $consultaAlumnos->execute([$equipoId]);
        $alumnosAsociados = $consultaAlumnos->fetch(PDO::FETCH_ASSOC);

        if ($alumnosAsociados['total'] > 0) {
            // El equipo tiene alumnos, no se puede actualizar
            $mensajesError[] = "Equipo no se puede actualizar por tener ya alumnos";
        }
        
    } else { //Si la variable no viene fijada en el json entonces tenemos un error que debemos avisar
        $mensajesError[] = "Debe especificar la id del equipo en la petición";
    }
    if ($edadMinima !== null) {
        if ($edadMinima < 7 || $edadMinima > 14) {
            $mensajesError[] = "La edad mínima debe estar entre 7 y 14. ";
        }
    } else {
        $mensajesError[] = "Debe especificar la edad minima. ";
    }

    if ($deporteId !== null) { // comprobamos que el deporte existe
        $checkDeporteId = $conexion->prepare("SELECT nombre FROM deportes WHERE id = ?");
        $checkDeporteId->bindParam(1, $deporteId);
        $checkDeporteId->execute();
        if ($checkDeporteId->rowCount() == 0) { //Si no existre el deporte en nuestra base de datos damos error
            $mensajesError[] = "Ese deporte no está disponible. ";
        }
    } else {
        $mensajesError[] = "Debe especificar un deporte id. ";
    }
    if ($nombreId !== null) {
        //verificar que noesta siendo usad ese nombre
        $checkName = $conexion->prepare("SELECT * FROM equipos WHERE nombre = ?");
        $checkName->bindParam(1, $nombreId);
        $checkName->execute();
        if ($checkName->rowCount() > 0) {
            $mensajesError[] = "Ese nombre ya pertecene a otro equipo";
        }
    }

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $update = "UPDATE equipos SET $campos WHERE id = :id";
        $consulta = $conexion->prepare($update);
        bindAllParams($consulta, $datos);
        $consulta->execute();
        //En caso de que ninguna de las opciones anteriores se haya ejecutado
        salidaDatos("Equipo actualizado correctamente", array('HTTP/1.1 200 OK'));
    } else {

        $mensajeJson = implode(PHP_EOL, $mensajesError);
        salidaDatos($mensajeJson, array('HTTP/1.1 400 Bad Request'));
    }
    //En caso de que ninguna de las opciones anteriores se haya ejecutado
    salidaDatos('No se a podido atender la petición.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
}
?>