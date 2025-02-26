<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

//$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

    $consulta = $conexion->prepare('SELECT
                                        titulo,
                                        id_autor,
                                        anno_publicacion,
                                        genero
                                        FROM Libros
  
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

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
      
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $id = $datos['id_prestamo'];

       //Primero hay que borrar las claves forasteras de otras tablas
       /* $delForeignKey1 = $conexion -> prepare('DELETE FROM clases WHERE extraescolar_id=?');
        $delForeignKey1 -> bindParam(1, $id);
        $delForeignKey1 -> execute();
        $delForeignKey1 = null;*/
        //Primero hay que borrar las claves forasteras de otras tablas
        /*$delForeignKey2 = $conexion -> prepare('DELETE FROM ubicacion WHERE extraescolar_id=?');
        $delForeignKey2 -> bindParam(1, $id);
        $delForeignKey2 -> execute();
        $delForeignKey2 = null;*/

        $delete = "DELETE FROM Prestamos where id_prestamo=:id";
        $consulta = $conexion->prepare($delete);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        
        if ($consulta->rowCount() > 0) {
        salidaDatos('Borrado realizado.', array( 'HTTP/1.1 200 OK'));
        $consulta = null;
        }   
        else {
                salidaDatos('No se encuentra el prestamo solicitado.', array('HTTP/1.1 404 Not Found'));
        }
       
        exit();

    }
//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('Error', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));


?>