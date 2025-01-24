<?php
 /**
 * Ejercicio - API
 *
 * @author Escriba aquí su nombre
 */

  require_once("../utiles/config.php");
  require_once("../utiles/funciones.php");
  $datos = "";

  // ESCRIBA AQUI EL CÓDIGO PHP NECESARIO
  $conexion = conectarPDO($database);
  $mensajesError = [];
  $nombreSuperficieRegistrado = null;
  $nuevoNombre = null;
  

  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $datos = json_decode(file_get_contents('php://input'), true);
    $superficieId = isset($datos['id']) ? $datos['id'] : null;
    $nuevoNombre = isset($datos['nombre']) ? $datos['nombre'] : null;

    if ($superficieId !== null) {
      $consulta = $conexion -> prepare("SELECT * FROM superficies WHERE id = ?");
      $consulta -> bindParam(1, $superficieId);
      $consulta -> execute();
      

      if($consulta -> rowCount() == 0) {
        
        $mensajesError[]= "La superficie $superficieId no existe.";
        /*salidaDatos(json_encode(["mensaje" => "La superficie $superficieId no existe."]), array('HTTP/1.1 400 Bad Request'));
        exit;*/
      }else{
        $resultado = $consulta ->fetch(PDO::FETCH_ASSOC);
        $nombreSuperficieRegistrado = $resultado["nombre"];
        if ($nombreSuperficieRegistrado == $nuevoNombre) {
          $mensajesError[] = "La superfiece ya tiene ese nombre";
        }
      }
      
    }
      
    if ($nuevoNombre !== null && count($mensajesError) == 0) {
      $campos = getParams($datos);
      $consultaPut = $conexion -> prepare("UPDATE superficies SET $campos WHERE id='$superficieId'");
      bindAllParams($consultaPut,$datos);
      $consultaPut -> execute();
    
      if ($consultaPut -> rowCount() >0) {
        salidaDatos('Superficie actulizada con éxito.', array( 'HTTP/1.1 200 OK'));
        exit;
      }
      else {
        salidaDatos('No se ha podido atender la petición', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
      }
    }
    $mensajeJson = implode("Ademas: ", $mensajesError);
    salidaDatos(json_encode([$mensajeJson]), array('HTTP/1.1 400 Bad Request'));
  }
  salidaDatos('No se ha podido atender la petición', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>