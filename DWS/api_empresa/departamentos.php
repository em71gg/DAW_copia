<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$mensajesError =[];//array para recoger errores personalizados. Lo s get no suelen necesitarlos

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id'])){//consulta para seleccionar un elemento por id
        //Mostrar un mensaje
            $consulta = $conexion -> prepare('SELECT 
                        d.nombre as Nombre,
                        s.nombre as Sede,
                        s.direccion AS Direccion,
                        d.presupuesto as Presupuesto,
                        (SELECT COUNT(*) FROM empleados WHERE departamento_id = :id) AS "Número de Empleados"
                        
                        FROM departamentos d
                        LEFT JOIN sedes s ON d.sede_id = s.id
                        
                        WHERE d.id = :id');
            $consulta->bindParam(':id', $_GET['id']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else{
                salidaDatos('No se encuentra un registro con id='.$_GET["id"].'.', array('HTTP/1.1 404 Not Found'));
            }
    }

    if(isset($_GET['conExtra'])){//ejemplo para listado que cunpla una condición diferente al id
                            //el id se pasa por postman creando la clave 'conExtra'con valor 1

        $consulta = $conexion->prepare('SELECT
                                        d.nombre as Nombre,
                        s.nombre as Sede,
                        s.direccion AS Direccion,
                        d.presupuesto as Presupuesto,
                        (SELECT COUNT(*) FROM empleados WHERE departamento_id = d.id) AS "Número de Empleados"
                        
                        FROM departamentos d
                        LEFT JOIN sedes s ON d.sede_id = s.id
                        
                        WHERE (SELECT COUNT(*) FROM empleados WHERE departamento_id = d.id) >0
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
                                        d.nombre as Nombre,
                                        s.nombre as Sede,
                                        s.direccion AS Direccion,
                                        d.presupuesto as Presupuesto,
                                        (SELECT COUNT(*) FROM empleados WHERE departamento_id = d.id) AS "Número de Empleados"
                                        
                                        FROM departamentos d
                                        LEFT JOIN sedes s ON d.sede_id = s.id
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
        $id = $datos['id'];

       //Primero hay que borrar las claves forasteras de otras tablas
        $delForeignKey1 = $conexion -> prepare("DELETE FROM empleados WHERE departamento_id=?");
        $delForeignKey1 -> bindParam(1, $id);
        $delForeignKey1 -> execute();
        $delForeignKey1 = null;

        $delete = "DELETE FROM departamentos where id=:id";
        $consulta = $conexion->prepare($delete);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        
        if ($consulta->rowCount() > 0) {
        salidaDatos('Borrado realizado.', array( 'HTTP/1.1 200 OK'));
        $consulta = null;
        }   
        else {
                salidaDatos('No se encuentra el departamento recibido.', array('HTTP/1.1 404 Not Found'));
        }
       
        exit();

    }



?>