<?php
 /**
 * Ejercicio - API
 *
 * @author Escriba aquí su nombre
 */

  require_once("../utiles/config.php");
  require_once("../utiles/funciones.php");
  $datos = "";
  $campeonFecha = null;

  $conexion = conectarPDO($database);

  // ESCRIBA AQUI EL CÓDIGO PHP NECESARIO
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = json_decode(file_get_contents('php://input'), true);

    //Nos aseguramos que las variables que usaremos no queden undefined
    $torneoId = isset($datos['torneo_id']) ? $datos['torneo_id'] : null;
    $tenistaId = isset($datos['tenista_id']) ? $datos['tenista_id'] : null;
    $fechaTorneo = isset($datos['anno']) ? $datos['anno'] : null;

    /*
    Se tiene que controlar que un torneo sólo lo puede ganar en un mismo año un sólo tenista.
    Se consulta la tabla titulos fpor año y torneo y si existe registro se da el aviso de que no se puede asignar
    campeón para el troneo en ese año
    */
    if ($torneoId !== null && $fechaTorneo !== null && $tenistaId !== null){
      $checkTituloGanado = $conexion -> prepare("SELECT tenista_id FROM titulos WHERE anno = ? AND torneo_id = ?");
      $checkTituloGanado -> bindParam(1, $fechaTorneo);
      $checkTituloGanado -> bindParam(2, $torneoId);
      $checkTituloGanado -> execute();
  
      $campeon = $checkTituloGanado -> fetch(PDO::FETCH_ASSOC);
      
  
      if (!$campeon) {
        $campeonFecha = $campeon['tenista_id'];
        $insert = "INSERT INTO titulos(anno, tenista_id, torneo_id) VALUES (:anno, :tenista_id, :torneo_id)";
        $consulta = $conexion->prepare($insert);
        bindAllParams($consulta, $datos);
        $consulta->execute();
        $mensajeId = $conexion -> lastInsertId();
        if($mensajeId) {
            $datos['id'] = $mensajeId;
            salidaDatos(json_encode($datos), 
            array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
      }
      else {
        $buscaCampeon = $conexion -> prepare("SELECT nombre FROM tenistas WHERE id =?");
        $buscaCampeon -> bindParam(1, $campeonFecha);
        $buscaCampeon -> execute();
        $resultado = $buscaCampeon -> fetch(PDO::FETCH_ASSOC);
        if ($resultado){
          $nombreCampeon = $resultado['nombre'];
        } 

        salidaDatos(json_encode(["mensaje" => "No se puede asignar ganador al torneo, en esa fecha fue ganado por $nombreCampeon."]), array('HTTP/1.1 400 Bad Request'));
        exit;
      }
    }
    else {
        salidaDatos(json_encode(["mensaje" => "Necesita enviar los datos: anno, tenista_id y torneo_id para asignar el campeon al torneo."]), array('HTTP/1.1 400 Bad Request'));
        exit;
    }

  }

  //En caso de que ninguna de las opciones anteriores se haya ejecutado
  salidaDatos('No se ha podido realizar la petición', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
 
?>