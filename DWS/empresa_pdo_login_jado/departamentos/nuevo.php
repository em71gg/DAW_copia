<?php
    // Incluye ficheros de variables y funciones
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
	// Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../index.php");
?>
<?php
	// Crea las variables necesarias para introducir los campos y comprobar errores.
	$errores = [];
   	$comprobarValidacion = false;
   	$departamento = "";
   	$presupuesto = "";
	$idsede = "";
		
   	if ($_SERVER["REQUEST_METHOD"]=="POST")
   	{
	    $comprobarValidacion = true;
		//Crea las variables con los requisitos de longitud en el campo nombre del departamento 
		$lonDepMin=3;
		$lonDepMax=100;			    
	    // Obtenemos el campo del nombre del departamento, presupuesto y sede a partir de la función "obtenerValorCampo"
	    $departamento = obtenerValorCampo("nombre");
	    $presupuesto = obtenerValorCampo("presupuesto");
		$idsede = obtenerValorCampo("sede");

    	//-----------------------------------------------------
        // Validaciones
        //-----------------------------------------------------
        // Nombre del departamento: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
        if (!validarLongitudCadena($departamento, $lonDepMin ,$lonDepMax)) 
        {
			$errores["departamento"] = 'El nombre del departamento no cumple los requisitos.';
        	$departamento = "";
        } 
        else 
        {
        	// En caso de que los datos sean correctos, comprobar que no exita un departamento con ese nombre.
			// Para ello, conectaros a la bbdd, usar el comando SELECT en departamento y buscar el nombre de departamento que se ha introducido.
			// Si el resultado es distinto de nulo, informar de que el departamento ya existe.
	        	
			$conexion = conectarPDO($host, $user, $password, $bbdd);
			$consulta = "SELECT nombre FROM departamentos WHERE nombre='".$departamento. "'";
			//echo "<p>La consulta queda: <br>" . $consulta . "</p>" . PHP_EOL;
			$resultado = resultadoConsulta($conexion, $consulta);
			if ($resultado->rowCount()!=0){
				$errores["departamento"] = 'El nombre del departamento ya existe.';
        	}
			//else{
			//	echo "<p>El nombre no exitía: BIEN.</p>" . PHP_EOL;
			//} 

        }

        // Presupuesto del departamento: entero positivo
        if (!validar_Entero_Positivo($presupuesto)) 
        {
			$errores["presupuesto"] = 'El presupuesto no cumple los requisitos.';
        	$presupuesto = "";
        } 

        // Nombre de la sede. Usamos la función validarEnteroPositivo() porque el valor del campo sede será el id.
        if (!validar_Entero_Positivo($idsede))
        {
			$errores["idsede"] = 'Se ha producido un error con el valor de la sede.';
        	$idsede = "";
        }
   	}

	// Si no hay errores, conectar a la BBDD:
	if ($comprobarValidacion && count($errores) == 0):
		$conexion = conectarPDO($host, $user, $password, $bbdd);

		// consulta a ejecutar
		$insert = "insert into departamentos (nombre, presupuesto, sede_id) values (:nombre, :presupuesto, :sede_id)";

		// preparar la consulta (usar bindParam)
		$consulta = $conexion->prepare($insert);

		$consulta->bindParam(':nombre', $departamento); 
		$consulta->bindParam(':presupuesto', $presupuesto);
		$consulta->bindParam(':sede_id', $idsede);

		// ejecutar la consulta y captura de la excepcion (try/catch)
		try{
			$consulta->execute();
			$consulta = null;
        	$conexion = null;
		}catch (PDOException $exception) 
		{
		   exit($exception->getMessage());
		}		
       	// redireccionamos al listado de departamentos
		header("Location: listado.php");
		exit();
	
		//Si hay algún error, tenemos que mostrar los errores en la misma página, manteniendo los valores bien introducidos.
  	else:
	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alta nuevo departamento</title>
	<link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
	<h1>Alta de un nuevo departamento</h1>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    	<p>
            <!-- Campo nombre del departamento -->
            <input type="text" name="nombre" placeholder="Departamento" value="<?php echo $departamento //Introducir valor ?>">
            <?php
            	if (isset($errores["departamento"]) /*¿Existe errores en el nombre del departamento?*/):
            ?>
            	<p class="error"><?php echo $errores["departamento"]//Pintar el error ?></p>
            <?php
            	endif;
            ?>
        </p>
        <p>
        <!-- Campo presupuesto del departamento -->
            <input type="number" name="presupuesto" placeholder="Presupuesto" value="<?php echo $presupuesto //Introducir valor ?>">
            <?php
            	if (isset($errores["presupuesto"])/*¿Existen errores en el presupuesto?*/):
            ?>
            	<p class="error"><?php echo $errores["presupuesto"] //Pintar el error ?></p>
            <?php
            	endif;
            ?>
        </p>
        <p>
            <!-- Campo nombre de la sede -->
            <select id="sede" name="sede">
            	<option value="">Seleccione Sede</option>
            <?php
				//Conectar a la base de datos para tomar los posibles valores de las sedes.
				
            	$conexion = conectarPDO($host, $user, $password, $bbdd);
				//Usamos un SELECT para traer los valores del id y en nombre de la sede.
            	$consulta = "SELECT id, nombre FROM sedes";
            	
            	$resultado = resultadoConsulta($conexion, $consulta);
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
//Usamos el $row para darle los valores al desplegable de las sedes, siendo el id el valor que toma la variable $sede (o como lo hayáis llamado) y el nombre lo que aparece en el desplegable.
  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $idsede ? "selected" : "" ?>><?php echo $row["nombre"]; ?></option>
  				<?php
				endwhile;
  					
				$resultado = null;
       			$conexion = null;
  				?>
			</select>
	
            <?php
            	if (isset($errores["idsede"]) /*¿Existen errores en la sede?*/):
            ?>
            	<p class="error"><?php echo $errores["idsede"] //Pintar el error ?></p>
            <?php
            	endif;

            ?>
	        </p>
	        <p>
	            <!-- Botón submit -->
	            <input type="submit" value="Guadar">
	        </p>
	</form>
   <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de departamentos</a>
        </div>
   </div>
</body>
</html>
<?php
	endif;
?>