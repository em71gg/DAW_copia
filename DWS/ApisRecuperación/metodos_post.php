<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    $mensajesError = []; //creamos contenedor de errores
    //Nos aseguramos que las variables que usaremos no queden undefined
    if (!isset($datos['nombre']) || !isset($datos['apellidos']) || !isset($datos['edad'])) {
        //Si hay alguna undefined se crea este mensaje
        salidaDatos('Debe completar todos los parámetros de la petición JSON.' . PHP_EOL .
            'Los datos obligatorios son:' . PHP_EOL .
            'nombre.' . PHP_EOL .
            'apellidos.' . PHP_EOL . 
            'edad', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }

    $checkNombreApellidos = $conexion->prepare('SELECT *
    FROM alumnos
    WHERE nombre=? AND apellidos=?');
    $checkNombreApellidos->bindParam(1, $datos['nombre']);
    $checkNombreApellidos -> bindParam(2, $datos['apellidos']);
    $checkNombreApellidos->execute();

    if ($checkNombreApellidos->rowCount() > 0) {
        $mensajesError[] = 'Ese alumno ya está dado de alta.';
    }

    if($datos['edad'] <6 || $datos['edad'] >14){
        $mensajesError[] = 'La edad debe estar entre 6 y 14.';
    }

    if (count($mensajesError) == 0) {
        $alta = $conexion->prepare('INSERT 
                                    INTO alumnos 
                                    (nombre,
                                    apellidos,
                                    edad) 
                                    VALUES
                                    (:nombre, 
                                    :apellidos,
                                    :edad )
                                    ');
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
        salidaDatos($mensajeJson, array('HTTP/1.1 400 Bad Request'));
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>