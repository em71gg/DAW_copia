<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');
$conexion = conectarPDO($host, $user, $password, $bbdd);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if($_GET['conExtra'] == 1){

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
                                        LEFT JOIN ubicacion u ON e.id = u.extraescolar_id");
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
        $consultaExiste = $conexion->prepare("SELECT * FROM alumnos WHERE id = ?");
        $consultaExiste->bindParam(1, $id);
        $consultaExiste->execute();
        if ($consultaExiste->rowCount() == 0) { //si no existe el equipo cargamos aviso de error
            $mensajesError[] = "No existe ese id.";
        }
    } else { //si no se ha pasado la se avisa
        $mensajesError[] = "Debe especificar la id de la petición";
    }

    if ($nombre == null){
        
            $mensajesError[] = 'DEbe indicar un nombre.';
    }
    if($apellidos == null) $mensajesError[] = 'DEbe indicar un apellido';
    if($edad !== null){
        if($edad <8 || $edad >16){
            $mensajesError[] = 'La edad debe estar comprendida entre 8 y 16 años.';
        }
    }else{$mensajesError[] = 'Debe indicar una edad';} 
        
        

    if (count($mensajesError) == 0) {
        $campos = getParams($datos);
        $update = "UPDATE alumnos SET $campos WHERE id = :id";
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
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
      
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $id = $datos['id'];

       
        $delForeignKey = $conexion -> prepare("DELETE FROM clases WHERE alumno_id=?");
        $delForeignKey -> bindParam(1, $id);
        $delForeignKey -> execute();
        $delForeignKey = null;

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
    salidaDatos('No se ha podido atender la petición', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>