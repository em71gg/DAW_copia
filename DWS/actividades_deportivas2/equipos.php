<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id'])){//consulta para seleccionar un elemento por id
        //Mostrar un mensaje
            $select = 'SELECT 
                         e.id,
                        e.nombre AS Nombre,
                        edad_minima AS "Edad Mínima",
                        d.nombre AS Deporte,
                        d.numero_jugadores AS "Numero de jugadores"
                        FROM equipos e
                        JOIN deportes d ON e.deporte_id = d.id
                        WHERE e.id = ?
                        ';

            $consulta = $conexion->prepare($select);
            $consulta->bindParam(1, $_GET['id']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else{
                salidaDatos('No se encuentra un registro con id='.$_GET["id"].'.', array('HTTP/1.1 404 Not Found'));
            }
    }

    

    $consulta = $conexion->prepare('SELECT
                                        e.id,
                                        e.nombre AS Nombre,
                                        edad_minima AS "Edad Mínima",
                                        d.nombre AS Deporte
                                        FROM equipos e
                                        JOIN deportes d ON e.deporte_id = d.id
                                        ORDER BY e.id
                                    ');
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

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    //nos aseguramos de que las variables no queden undefined
    
    $id = isset($datos['id']) ? $datos['id'] : null;
    $nombre = isset($datos['nombre']) ? $datos['nombre'] : null;
    $edad_minima = isset($datos['edad_minima']) ? $datos['edad_minima'] : null;
    $deporte_id = isset($datos['deporte_id']) ? $datos['deporte_id'] : null;
    $mensajesError = [];
    
    

    if ($id !== null) {

        //verificamos que existe registro con esa id
        $consultaExiste = $conexion->prepare("SELECT * FROM equipos WHERE id = ?");
        $consultaExiste->bindParam(1, $id);
        $consultaExiste->execute();
        if ($consultaExiste->rowCount() == 0) { //si no existe el equipo cargamos aviso de error
            $mensajesError[] = "No existe ese id.";
        }
    } else { //si no se ha pasado la se avisa
        $mensajesError[] = "Debe especificar la id de la petición";
    }

    if ($nombre == null){
        
            $mensajesError[] = 'Debe indicar un nombre.';
    }
    if($edad_minima == null) $mensajesError[] = 'Debe indicar una edad mínima entre 7 y 14 años.';
    if($edad_minima <7 || $edad_minima >14){
        $mensajesError[] = 'La edad mínima entre 7 y 14 años.';
    }

    $CheckAlumnosSelect = "SELECT
                            COUNT(*)
                            FROM alumnos a
                            JOIN equipos_alumnos e ON a.id = e.alumno_id
                            JOIN equipos ep ON e.equipo_id = ep.id
                            WHERE ep.id = ?";
    $CheckAlumnos = $conexion -> prepare ($CheckAlumnosSelect);
    $CheckAlumnos -> bindParam(1, $id);
    $CheckAlumnos ->execute();

    if($CheckAlumnos >0){
        $mensajesError[] = 'El equipo  no se puede actualizar porque ya tiene jugadores.';
    }


    
        

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $update = "UPDATE equipos SET $campos WHERE id = :id";
        $consulta = $conexion->prepare($update);
        bindAllParams($consulta, $datos);
        $consulta->execute();
        //En caso de que ninguna de las opciones anteriores se haya ejecutado
        salidaDatos("Registro actualizado correctamente", array('HTTP/1.1 200 OK'));
    } else {

        $mensajeJson = implode(PHP_EOL, $mensajesError);
        salidaDatos($mensajeJson, array('HTTP/1.1 400 Bad Request'));
    }
}

?>