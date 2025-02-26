<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

//$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

    $consulta = $conexion->prepare('SELECT
                                        nombre,
                                        email,
                                        telefono,
                                        fecha_registro,
                                        edad
                                        FROM Miembros
  
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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    $mensajesError = []; //creamos contenedor de errores
    //Nos aseguramos que las variables que usaremos no queden undefined
    if (!isset($datos['nombre']) || !isset($datos['email']) || !isset($datos['edad'])
    || !isset($datos['telefono'])) {
        //Si hay alguna undefined se crea este mensaje
        salidaDatos('Debe completar todos los parámetros de la petición JSON.' . PHP_EOL .
            'Los datos obligatorios son:' . PHP_EOL .
            'nombre.' . PHP_EOL .
            'email.' . PHP_EOL . 
            'telefono.' . PHP_EOL . 
            'edad', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }

    $checkNombreemail = $conexion->prepare('SELECT *
                                            FROM Miembros
                                            WHERE email=?');
    
    $checkNombreemail -> bindParam(1, $datos['email']);
    $checkNombreemail->execute();

    if ($checkNombreemail->rowCount() > 0) {
        $mensajesError[] = 'Ese email ya está registrado.';
    }

    $checkNombre = $conexion->prepare('SELECT *
                                        FROM Miembros
                                        WHERE nombre=?');
    
    $checkNombre -> bindParam(1, $datos['nombre']);
    $checkNombre->execute();

    if ($checkNombre->rowCount() > 0) {
        $mensajesError[] = 'Ese nombre está registrado.';
    }

    if($datos['edad'] <16 ){
        $mensajesError[] = 'La edad debe aser superior a 16.';
    }

    if (count($mensajesError) == 0) {
        $alta = $conexion->prepare("INSERT 
                                    INTO Miembros 
                                    (nombre,
                                    email,
                                    telefono,
                                    edad) 
                                    VALUES
                                    (:nombre, 
                                    :email,
                                    :telefono,
                                    :edad)
                                    ");
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
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);

    //nos aseguramos de que las variables no queden undefined
    
    $id = isset($datos['id_miembro']) ? $datos['id_miembro'] : null;
    $nombre = isset($datos['nombre']) ? $datos['nombre'] : null;
    $email = isset($datos['email']) ? $datos['email'] : null;
    $telefono = isset($datos['telefono']) ?  $datos['telefono'] :null;
    $edad = isset($datos['edad']) ? $datos['edad'] : null;
    $mensajesError = [];


    if ($id !== null) {

        //verificamos que existe registro con esa id
        $consultaExiste = $conexion->prepare('SELECT * FROM Miembros WHERE id_miembro = ?');
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
                                            FROM Miembros
                                            WHERE nombre=? AND id_miembro != ?
                                            ');
    $checkNombre->bindParam(1, $datos['nombre']);
    $checkNombre -> bindParam(2, $datos['id_miembro']);
    $checkNombre->execute();

    if ($checkNombre->rowCount() > 0) {
        $mensajesError[] = 'Ese nombre ya está en uso';
    }

    if($email == null) $mensajesError[] = 'Debe indicar un número de email disponibles.';
    
    $checkEmail = $conexion -> prepare('SELECT *
                                        FROM Miembros
                                        WHERE email=? AND id_miembro != ?
                                         ');
    $checkEmail -> bindParam(1, $datos['email']);
    $checkEmail -> bindParam(2, $datos['id_miembro']);
    $checkEmail ->execute();

    if ($checkNombre->rowCount() > 0) {
        $mensajesError[] = 'Ese email ya está en uso';
    }

    if($datos['edad'] <16 ){
        $mensajesError[] = 'La edad debe ser superior a 16.';
    }
     

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $consulta = $conexion -> prepare("UPDATE Miembros SET $campos WHERE id_miembro = :id_miembro");
        bindAllParams($consulta, $datos);
        $consulta->execute();
        //En caso de que ninguna de las opciones anteriores se haya ejecutado
        salidaDatos("Registro actualizado correctamente", array('HTTP/1.1 200 OK'));
    } else {

        $mensajeJson = implode(PHP_EOL, $mensajesError);
        salidaDatos($mensajeJson, array('HTTP/1.1 400 Bad Request'));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
      
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $id = $datos['id_miembro'];

       //Primero hay que borrar las claves forasteras de otras tablas
        $delForeignKey1 = $conexion -> prepare('DELETE FROM Prestamos WHERE id_miembro=?');
        $delForeignKey1 -> bindParam(1, $id);
        $delForeignKey1 -> execute();
        $delForeignKey1 = null;
        

        $delete = "DELETE FROM Miembros where id_miembro=:id";
        $consulta = $conexion->prepare($delete);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        
        if ($consulta->rowCount() > 0) {
        salidaDatos('Borrado realizado.', array( 'HTTP/1.1 200 OK'));
        $consulta = null;
        }   
        else {
                salidaDatos('No se encuentra el miembro solicitado.', array('HTTP/1.1 404 Not Found'));
        }
       
        exit();

    }


//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

?>