

<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id_miembro'])){//consulta para seleccionar un elemento por id
        //Mostrar un mensaje
            $consulta = $conexion -> prepare('SELECT 
                        m.nombre as Nombre,
                        l.titulo,
                        p.fecha_prestamo,
                        p.fecha_devolucion
                        FROM Miembros m
                        LEFT JOIN Prestamos p ON m.id_miembro = p.id_miembro
                        LEFT JOIN Libros l ON p.id_libro = l.id_libro
                        WHERE m.id_miembro = :id');
            $consulta->bindParam(':id', $_GET['id_miembro']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else{
                salidaDatos('No se encuentra un registro con id='.$_GET["id"].'.', array('HTTP/1.1 404 Not Found'));
            }
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

?>