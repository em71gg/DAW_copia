<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    //nos aseguramos de que las variables no queden undefined
    
    $id = isset($datos['id']) ? $datos['id'] : null;
    $nombre = isset($datos['nombre']) ? $datos['nombre'] : null;
    $plazas = isset($datos['plazas']) ? $datos['plazas'] : null;
    $edad = isset($datos['edad']) ? $datos['edad'] : null;
    $mensajesError = [];
    
    

    if ($id !== null) {

        //verificamos que existe registro con esa id
        $consultaExiste = $conexion->prepare('SELECT * FROM extraescolar WHERE id = ?');
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

    //Para comprobar que no existen registros diferentes al que quiero modificar 
    //con el nombre que estoy dándole
    $checkNombre = $conexion->prepare('SELECT *
                                            FROM extraescolar
                                            WHERE nombre=? AND id != ?
                                            ');
    $checkNombre->bindParam(1, $datos['nombre']);
    $checkNombre -> bindParam(2, $datos['id']);
    $checkNombre->execute();

    if ($checkNombre->rowCount() > 0) {
        $mensajesError[] = 'Ese nombre ya está en uso';
    }
    if($plazas == null) $mensajesError[] = 'Debe indicar un número de plazas disponibles.';
    if($plazas <6 || $plazas >20){
        $mensajesError[] = 'El número de plzas debe estar entre 6 y 20.';
    }
    
        

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $consulta = $conexion -> prepare("UPDATE extraescolar SET $campos WHERE id = :id");
        bindAllParams($consulta, $datos);
        $consulta->execute();
        //En caso de que ninguna de las opciones anteriores se haya ejecutado
        salidaDatos("Registro actualizado correctamente", array('HTTP/1.1 200 OK'));
    } else {

        $mensajeJson = implode(PHP_EOL, $mensajesError);
        salidaDatos($mensajeJson, array('HTTP/1.1 400 Bad Request'));
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>