<?php

require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');
$conexion = conectarPDO($host, $user, $password, $bbdd);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $consulta = $conexion->prepare("SELECT

                        e.nombre,
                        e.plazas,
                        u.nombre as aula
                        FROM extraescolar e 
                        JOIN clases c ON e.id = c.extraescolar_id
                        Join ubicacion u ON c.extraescolar_id =u.extraescolar_id
                        group BY e.nombre    
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    $mensajesError = []; //creamos contenedor de errores
    //Nos aseguramos que las variables que usaremos no queden undefined
    if (!isset($datos['nombre']) || !isset($datos['plazas'])) {
        //Si hay alguna undefined se crea este mensaje
        salidaDatos('Debe completar todos los parámetros de la petición JSON.' . PHP_EOL .
            'Los datos obligatorios son:' . PHP_EOL .
            'nombre.' . PHP_EOL .
            'plazas.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }

    $chekcNombre = $conexion->prepare("SELECT *
    FROM extraescolar
    WHERE nombre=?");
    $checkNombre->bindParam(1, $datos['nombre']);
    $checkNombre->execute();

    if ($checkNombre->rowCount() > 0) {
        $mensajesError[] = 'El nombre de la actividad ya está en uso.';
    }

    if($datos['plazas'] <6 || $datos['plazas'] >14){
        $mensajesError[] = 'La plazas de la actividad deben estar entre 6 y 14.';
    }

    if (count($mensajesError) == 0) {
        $alta = $conexion->prepare("INSERT 
                                    INTO extraescolar 
                                    (nombre,
                                    plazas ) 
                                    VALUES
                                    (:nombre, 
                                    :plazas, 
                                    )");
        bindAllParams($alta, $datos);
        $alta->execute();
        $idAlta = $conexion->lastInsertId();
        $alta = null;
        if ($idAlta) {

            salidaDatos("Alta realizada con éxito en 24 horas será revisada y activada, id del alta = "
                . $idAlta . ".", array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
    } else {
        
        $mensajeJson = implode(PHP_EOL, $mensajesError);
        salidaDatos(json_encode($mensajeJson), array('HTTP/1.1 400 Bad Request'));
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    //nos aseguramos de que las variables no queden undefined
    
    $id = isset($datos['id']) ? $datos['id'] : null;
    $nombre = isset($datos['nombre']) ? $datos['nombre'] : null;
    $nombre = isset($datos['plazas']) ? $datos['plazas'] : null;
    
    

    if ($id !== null) {

        //verificamos que existe registro con esa id
        $consultaExiste = $conexion->prepare("SELECT * FROM extraescolar WHERE id = ?");
        $consultaExiste->bindParam(1, $id);
        $consultaExiste->execute();
        if ($consultaExiste->rowCount() == 0) { //si no existe el equipo cargamos aviso de error
            $mensajesError[] = "No existe ese id.";
        }
    } else { //si no se ha pasado la se avisa
        $mensajesError[] = "Debe especificar la id de la petición";
    }

    if ($checkNombre !== null){
        $checkNombre = $conexion->prepare("SELECT *
        FROM extraescolar
        WHERE nombre=?");
        $checkNombre->bindParam(1, $datos['nombre']);
        $checkNombre->execute();

        if ($checkNombre->rowCount() > 0) {
            $mensajesError[] = 'El nombre de la actividad ya está en uso.';
        }
    }
        
        if($datos['plazas'] <6 || $datos['plazas'] >14){
            $mensajesError[] = 'La plazas de la actividad deben estar entre 6 y 14.';
        }

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $update = "UPDATE extraescolares SET $campos WHERE id = :id";
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

salidaDatos('No se ha podido atender la petición', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>