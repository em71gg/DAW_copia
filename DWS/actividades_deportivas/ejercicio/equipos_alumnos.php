<?php
	require_once("../funciones.php");

	$conexion = conectar_pdo($host, $user, $password, $bbdd);

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // Transformamos el JSON de entrada de datos a un array asociativo
        $datos = json_decode(file_get_contents('php://input'), true);
        //Nos aseguramos que las variables que usaremos no queden undefined
        $equipoId = isset($datos['equipo_id']) ? $datos['equipo_id'] : null;
        $alumnoId = isset($datos['alumno_id']) ? $datos['alumno_id'] : null;
        $mensajesError = [];
        /*
        Criterios para insertar:
            comprobar existe id_equipo : equipos -> id
            comprobar existe id_alumno : alumnos -> id
            comprobar que el id_alumno no está en otro equipo : no existe el par equipo_id / alumno_id en equipos_alumnos
            comprobar que el alumno tiene edad_minima paraestar en ese equipo : alumnos -> edad
            comprobar que la edad <= edad_minima +2 : equipos -> edad_minima
            comprobar que el equipo no está completo : deportes -> numero_jugadores
        */
       
        
        if ($alumnoId !== null) {
            //consulta para comprobar la id del alumno y si existe rescatar su edad
            $checkIdAlumno = $conexion ->prepare ("SELECT id, edad FROM alumnos WHERE id = ?");
            $checkIdAlumno -> bindParam(1, $alumnoId);
            $checkIdAlumno -> execute();

            if($checkIdAlumno -> rowCount() == 0) {//Si no existe un alumno con esa id se sale dando el mensaje.
                $mensajesError[]= "El alumno indicado no existe.";
                /*salidaDatos(json_encode(["mensaje" => "El alumno indicado no existe."]), array('HTTP/1.1 400 Bad Request'));
                    exit;*/
            }else{
                //Al existir el alumno obtengo la edad.
                $alumno = $checkIdAlumno ->fetch(PDO::FETCH_ASSOC);
                $edadAlumno = $alumno['edad'];

                $checkPerteneceEquipo = $conexion -> prepare("SELECT 
                                                                equipo_id, 
                                                                alumno_id
                                                            FROM equipos_alumnos 
                                                            WHERE alumno_id = ?");
                $checkPerteneceEquipo -> bindParam(1, $alumnoId);
                $checkPerteneceEquipo ->execute();

                if ($checkPerteneceEquipo -> rowCount() >0) {//si ya existe la asociación de este alumno con un equipo se sale dando el mensaje.
                    $mensajesError[] = "El alumno indicado ya pertenece a un equipo.";
                    /*salidaDatos(json_encode(["mensaje" => ""]), array('HTTP/1.1 400 Bad Request'));
                    exit;*/
                }
            }
            
        }
       
        if ($equipoId !== null) {
             //consulta para comprobar que el id del equipo existe, rescatar la edad mínima y el numero de jugadores
            $chekIdEquipo = $conexion -> prepare ("SELECT 
            edad_minima,
            deporte_id,
            numero_jugadores
            FROM equipos e
            JOIN deportes d ON e.id = d.id 
            WHERE e.id = ?");
            $chekIdEquipo -> bindParam(1, $equipoId);
            $chekIdEquipo -> execute();

            if($chekIdEquipo -> rowCount() == 0) {//Si no existe un equipo con esa id se sale dando el mensaje.
                $mensajesError[] = "El equipo indicado no existe.";
                /*salidaDatos(json_encode(["mensaje" => "El equipo indicado no existe."]), array('HTTP/1.1 400 Bad Request'));
                    exit;*/
            }
            else{
                //si llego aquí es que $equipoId recogida de la petición json es válida
                //rescato la edad_minima
                $equipo = $chekIdEquipo -> fetch(PDO::FETCH_ASSOC);
                $edadMinima = $equipo['edad_minima'];
                $numMaxJugadores = $equipo['numero_jugadores'];
                //Consulta para obtener los miembros ed un equipo
                $miembrosEquipo = $conexion -> prepare("SELECT COUNT(*) AS miembros FROM equipos_alumnos WHERE equipo_id = ?");
                $miembrosEquipo -> bindParam(1, $equipoId);
                $miembrosEquipo ->execute();

                $datosEquipo = $miembrosEquipo -> fetch(PDO::FETCH_ASSOC);
                $miembros = $datosEquipo['miembros'];

                if ($miembros >= $numMaxJugadores) {
                    $mensajesError[]= "El equipo tiene ya su máximo de jugadores.";
                    /*salidaDatos(json_encode(["mensaje" => "El equipo tiene ya su máxino de jugadores, el alumno no puede ser incluido."]), array('HTTP/1.1 400 Bad Request'));
                    exit;*/
                }
                if ($edadAlumno < $edadMinima) {//si no llega a la edad
                    $mensajesError[]= "El alumno indicado no tiene la edad minima necesaria para pertencer al equipo.";
                    /*salidaDatos(json_encode(["mensaje" => "El alumno indicado no tiene la edad mínima necesaria para pertencer al equipo."]), array('HTTP/1.1 400 Bad Request'));
                    exit;*/
                }
        
                if ($edadAlumno > $edadMinima + 2) {//si supera la edad
                    $mensajesError[]= "El alumno indicado supera edad permitida para pertenecer al equipo.";
                    /*salidaDatos(json_encode(["mensaje" => "El alumno indicado supera edad permitida para pertenecer al equipo."]), array('HTTP/1.1 400 Bad Request'));
                    exit;*/
                }
            }
  
        }
        if(count($mensajesError) == 0) {
            $insert = "INSERT INTO equipos_alumno(equipo_id, alumno_id) VALUES (:equipo_id, :alumno_id)";
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
            
            $mensajeJson = implode("Ademas: ", $mensajesError);
            salidaDatos(json_encode([$mensajeJson]), array('HTTP/1.1 400 Bad Request'));
        }
    
    }
    //En caso de que ninguna de las opciones anteriores se haya ejecutado
    salidaDatos('No se a podido atender la petición.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

?>