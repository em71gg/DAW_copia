<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {


    if(isset($_GET['conExtra'])){//ejemplo para listado que cunpla una condición diferente al id
                            //el id se pasa por postman creando la clave 'conExtra'con valor 1 o vacio

        $consulta = $conexion->prepare('SELECT
                                    a.nombre AS NOmbre,
                                    a.apellidos AS Apellidos,
                                    a.edad AS Edad,
                                    e.nombre AS Actividad,
                                    COALESCE(u.nombre, "") AS Aula
                                    FROM alumnos a
                                    JOIN clases c ON a.id=c.alumno_id
                                    LEFT JOIN extraescolar e ON c.extraescolar_id=e.id
                                    LEFT JOIN ubicacion u ON e.id=u.extraescolar_id
                                        ');
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

    $consulta = $conexion->prepare('SELECT
                                    a.nombre AS NOmbre,
                                    a.apellidos AS Apellidos,
                                    a.edad AS Edad,
                                    COALESCE(e.nombre, "") AS Actividad,
                                    COALESCE(u.nombre, "") AS Aula
                                    FROM alumnos a
                                    LEFT JOIN clases c ON a.id=c.alumno_id
                                    LEFT JOIN extraescolar e ON c.extraescolar_id=e.id
                                    LEFT JOIN ubicacion u ON e.id=u.extraescolar_id
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
    $apellidos = isset($datos['apellidos']) ? $datos['apellidos'] : null;
    $edad = isset($datos['edad']) ? $datos['edad'] : null;
    $mensajesError = [];
    
    

    if ($id !== null) {

        //verificamos que existe registro con esa id
        $consultaExiste = $conexion->prepare('SELECT * FROM alumnos WHERE id=?');
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
    if($apellidos == null) $mensajesError[] = 'Debe indicar los apellidos.';
    
    if($edad !== null) {
        if($edad <8 || $edad >16){
            $mensajesError[] = 'la edad debe estar entre 8 y 16.';
        }
    }else{
        $mensajesError[] = 'Debe indicar la edad.';
    }
    
        

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $consulta = $conexion -> prepare("UPDATE alumnos SET $campos WHERE id = :id");
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
        $id = $datos['id'];

       //Primero hay que borrar las claves forasteras de otras tablas
        $delForeignKey1 = $conexion -> prepare('DELETE FROM clases WHERE extraescolar_id=?');
        $delForeignKey1 -> bindParam(1, $id);
        $delForeignKey1 -> execute();
        $delForeignKey1 = null;

        $delForeignKey2 = $conexion -> prepare('DELETE FROM ubicacion WHERE extraescolar_id=?');
        $delForeignKey2 -> bindParam(1, $id);
        $delForeignKey2 -> execute();
        $delForeignKey2 = null;

        $delete = "DELETE FROM alumnos where id=:id";
        $consulta = $conexion->prepare($delete);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        
        if ($consulta->rowCount() > 0) {
        salidaDatos('Borrado realizado.', array( 'HTTP/1.1 200 OK'));
        $consulta = null;
        }   
        else {
                salidaDatos('No se encuentra el tenista recibido.', array('HTTP/1.1 404 Not Found'));
        }
       
        exit();

    }



?>