<?php
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = $datos['id'];
    $delete = "DELETE FROM nombreTabla where id=:id";
    $consulta = $conexion->prepare($delete);
    $consulta->bindParam(':id', $id);
    $consulta->execute();
    if ($consulta->rowCount() > 0) {
        salidaDatos('Oferta borrada con éxito', array('HTTP/1.1 200 OK'));
    } else {
        salidaDatos('Error al borrar el registro', array('HTTP/1.1 404 Not Found'));
        exit;
    }
    exit();
}
?>