<?php
	require_once("../funciones.php");

	$conexion = conectar_pdo($host, $user, $password, $bbdd);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id'])){
        //Mostrar un mensaje
            $select = "SELECT  
						e.id AS Id,
						e.nombre AS Nombre,
						e.edad_minima AS `Edad mínima`,
						d.nombre AS `Nombre del deporte`,
						d.numero_jugadores AS `Número de jugadores del deporte`,
						IFNULL(
							JSON_ARRAYAGG(
								JSON_OBJECT(
									'nombre', a.nombre,
									'apellidos', a.apellidos,
									'edad', a.edad
								)
							), JSON_ARRAY()
						) AS Alumnos
						FROM 
							equipos e
						LEFT JOIN 
							deportes d ON e.deporte_id = d.id
						LEFT JOIN 
							equipos_alumnos ea ON e.id = ea.equipo_id
						LEFT JOIN 
							alumnos a ON ea.alumno_id = a.id
						WHERE 
							e.id = :id
						GROUP BY 
							e.id
					";
            $consulta = $conexion->prepare($select);
            $consulta->bindParam(':id', $_GET['id']);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                salidaDatos (json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }   else{
                    salidaDatos('', array('HTTP/1.1 404 Not Found'));
                }
        } else {
            //Mostrar lista de mensajes
            $select = "SELECT  
						e.id AS Id,
						e.nombre AS Nombre,
						e.edad_minima AS `Edad mínima`,
						d.nombre AS `Nombre del deporte`
						
						FROM 
							equipos e
						JOIN 
							deportes d ON e.deporte_id = d.id
						ORDER BY e.id
						";
            $consulta = $conexion->prepare($select);
            $consulta->execute();
            $registros = [];
            while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
            }
            salidaDatos(json_encode($registros), 
            array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
    }

	if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		// Transformamos el JSON de entrada de datos a un array asociativo
		$datos = json_decode(file_get_contents('php://input'), true);
		$mensajeId = $datos['id'];
		$edadMinima = isset($datos['edad_minima']) ? $datos['edad_minima'] : null;

		//Verificar si el equipo existe
		$consultaExiste = $conexion -> prepare("SELECT * FROM equipos WHERE id = ?");
		$consultaExiste -> bindParam(1, $mensajeId);
		$consultaExiste -> execute();
		
		
		if($consultaExiste-> rowCount() == 0){//si no existe el equipo avisamos y salimos
			salidaDatos(json_encode(["mensaje" => "No existe ese equipo."]), array('HTTP/1.1 400 Bad Request'));
			exit;
		}
		$resultadoExiste = $consultaExiste -> fetch(PDO::FETCH_ASSOC);

		// Verificar si el equipo tiene alumnos asociados
		$consultaAlumnos = $conexion->prepare("SELECT COUNT(*) AS total FROM equipos_alumnos WHERE equipo_id = ?");
		$consultaAlumnos->execute([$mensajeId]);
	
		if ($resultadoAlumnos['total'] > 0) {
			// El equipo tiene alumnos, no se puede actualizar
			salidaDatos(json_encode(["mensaje" => "Equipo no se puede actualizar por tener ya alumnos"]), array('HTTP/1.1 400 Bad Request'));
			exit;
		}
	
		// Validar que la edad mínima esté entre 7 y 14
		if ($edadMinima !== null && ($edadMinima < 7 || $edadMinima > 14)) {
			salidaDatos(json_encode(["mensaje" => "La edad mínima debe estar entre 7 y 14"]), array('HTTP/1.1 400 Bad Request'));
			exit;
		}
	
		// Si pasa las validaciones, se procede a la actualización
		$campos = getParams($datos);
		$update = "UPDATE equipos SET $campos WHERE id = :id";
		$consulta = $conexion->prepare($update);
		bindAllParams($consulta, $datos);
		$consulta->execute();
		//En caso de que ninguna de las opciones anteriores se haya ejecutado
		salidaDatos(json_encode(["mensaje" => "Equipo actualizado correctamente"]), array('HTTP/1.1 200 OK'));
	}
		
?>