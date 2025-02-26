<?php
 /**
 * Ejercicio - API 
 *
 * @author Escriba aquí su nombre
 */

  require_once("../utiles/config.php");
  require_once("../utiles/funciones.php");
  $datos = "";
  $conexion = conectarPDO($database);
  
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
      
      $consulta = $conexion -> prepare ("SELECT 
                                              id, 
                                              nombre, 
                                              apellidos, 
                                              altura, 
                                              anno_nacimiento AS 'año de nacimiento',
                                              (
                                                  /*Si hubiese que devolver un json*/
                                                  SELECT 
                                                      JSON_OBJECTAGG(anno, titulos)
                                                  FROM (
                                                      SELECT 
                                                          anno, 
                                                          JSON_ARRAYAGG(
                                                              (SELECT nombre FROM torneos WHERE id = titulos.torneo_id)
                                                          )
                                               /*Para devolver un array*/
                                               /* SELECT 
                                                            JSON_ARRAYAGG(
                                                                JSON_ARRAY(anno, titulos)
                                                            )
                                                        FROM (
                                                            SELECT 
                                                                anno, 
                                                                JSON_ARRAYAGG(
                                                                    (SELECT nombre FROM torneos WHERE id = titulos.torneo_id)
                                                                )*/
                                                          AS titulos
                                                      FROM titulos
                                                      WHERE tenista_id = ?
                                                      GROUP BY anno
                                                  ) AS titulos_anno
                                              ) AS titulos
                                          FROM
                                              tenistas 
                                          WHERE
                                              id = ?
                                        ");
        $consulta -> bindParam(1, $_GET['id']);
        $consulta -> bindParam(2, $_GET['id']);
        $consulta -> execute();
    if ($consulta->rowCount() > 0) {
        $datosTenista = $consulta->fetch(PDO::FETCH_ASSOC); //obtiene los datos como un array asociativo.
        $datosTenista['titulos'] = json_decode($datosTenista['titulos'], true); // convierte la cadena JSON en un objeto JSON válido.
        salidaDatos(json_encode($datosTenista), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));//se usa para enviar la respuesta en formato JSON adecuado.
    }  
    else{
          salidaDatos('No se encuentra el tenista pedido.', array('HTTP/1.1 404 Not Found'));
      }
    }
    
                                      
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
      
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        $id = $datos['id'];

        //Entitulos tenista_id hace referencia como foreign key a id de tenistas, lueog debo borrar primero el registro vinculado en titulos 
        $delForeignKey = $conexion -> prepare("DELETE FROM titulos WHERE tenista_id=?");
        $delForeignKey -> bindParam(1, $id);
        $delForeignKey -> execute();
        $delForeignKey = null;
        $delete = "DELETE FROM tenistas where id=:id";
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
  //En caso de que ninguna de las opciones anteriores se haya ejecutado
  salidaDatos('No se ha podido realizar la petición.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>