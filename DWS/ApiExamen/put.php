<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');
$conexion = conectarPDO($host, $user, $password, $bbdd);
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    //nos aseguramos de que las variables no queden undefined
    $tableId = isset($datos['id']) ? $datos['id'] : null;
    $sugienteColumna = isset($datos['sigcol']) ? $datos['sigcol'] : null;
    //etc con el resto de columnas
    $mensajesError = [];

    //Se validad los datos llegados de JSON y se avisa de los errores

    if ($tableId !== null) {

        //verificamos que existe registro con esa id
        $consultaExiste = $conexion->prepare("SELECT * FROM nombreTabla WHERE id = ?");
        $consultaExiste->bindParam(1, $equipoId);
        $consultaExiste->execute();
        if ($consultaExiste->rowCount() == 0) { //si no existe el equipo cargamos aviso de error
            $mensajesError[] = "No existe ese id.";
        }
    } else { //si no se ha pasado la se avisa
        $mensajesError[] = "Debe especificar la id de la petición";
    }

    //del mismo modo se hacen otras verificaciones

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
