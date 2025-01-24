<?php
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
?>
<?php
	$comprobarValidacion = false;
	// crea las variables para la comprobación de los datos y conectamos con la BBDD para obtener y pintar los datos de la id que acabamos de enviar a la página
	$errores = [];
   	$idSede = 0;
	$sede="";
	$direccion="";
   	
   	if (count($_REQUEST) > 0)
   	{
		if (isset($_GET["idSede"]))
	    {
			$idSede = $_GET["idSede"];
           	//Conectamos a la BBDD
			$conexion = conectarpdo($host, $user, $password, $bbdd);
       		// Montamos la consulta a ejecutar
			$select = "SELECT nombre, direccion FROM sedes s WHERE s.id = ? ";
	        // prepararamos la consulta
			$consulta = $conexion->prepare($select);
	        // parámetro (usamos bindParam)
			$consulta->bindParam(1, $idSede);
	        // ejecutamos la consulta 
			$consulta->execute();
	        // comprobamos si hay algún registro
			if ($consulta->rowCount() == 0)
			{
				//Si no lo hay, desconectamos y volvemos al listado original
				//echo "<p>No hay</p>";
				$consulta = null;
				$conexion = null;
				header("Location: listado.php");
				exit();
			}
			else 
			{
				// Si hay algún registro, Obtenemos el resultado (usamos fetch())
				//echo "<p>Si hay</p>";
				$registro = $consulta->fetch();
				$sede = $registro['nombre'];
	        	$direccion = $registro['direccion'];
	            $consulta = null;
		        $conexion = null;			        
			}
       	} 
       	else 
       	{
			$comprobarValidacion = true;
			// Comenzamos la comprobación de los datos introducidos.
			// Creamos las variables con los requisitos de cada campo
			$lonSedMin=3;
			$lonSedMax=50;
			$lonDirMin=10;
			$lonDirMax=255;
       		// Obtenemos el campo del nombre de la sede y dirección
		    $idSede = obtenerValorCampo("id");
		    $sede = obtenerValorCampo("nombre");
		    $direccion = obtenerValorCampo("direccion");
			    
		    //-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
			// Comprueba que el id de la sede se corresponde con una que tengamos 
			//conectamos a la bbdd
			$conexion = conectarpdo($host, $user, $password, $bbdd);
        	// preparamos la consulta SELECT a ejecutar
			$select = "SELECT nombre, direccion FROM sedes s WHERE s.id = ? ";
			// preparamos la consulta (bindParam)
			$consulta = $conexion->prepare($select);
	        // parámetro (usamos bindParam)
			$consulta->bindParam(1, $idSede);
	        // ejecutamos la consulta 
			$consulta->execute();
				
			// comprobamos si algún registro 
			if ($consulta->rowCount() == 0)
			{
				//Si no lo hay, desconectamos y volvemos al listado original
				$consulta = null;
       			$conexion = null;
       			// redireccionamos al listado de sedes
				//header("Location: listado.php");
				exit();
			}
			
	        // Nombre de la sede: validamos la longitud. Si no es correcta, generamos el error.
	        if (!validarLongitudCadena($sede, $lonSedMin ,$lonSedMax)) 
	        {
				//Generar msj de error
				$errores["sede"] = 'El nombre de la sede no cumple los requisitos.';
        		$sede = "";
	        }
	        // Dirección de la sede: validamos la longitud. Si no es correcta, generamos el error.
	        if (!validarLongitudCadena($direccion, $lonDirMin, $lonDirMax)) 
	        {
				//Generar msj de error
				$errores["direccion"] = 'La dirección de la sede no cumple los requisitos.';
            	$direccion = "";
	        }
       	}
   	} 
	else{
		header("Location: listado.php");
		exit();
	}
?>

<?php
	//Si no hay errores:
	if ($comprobarValidacion && count($errores) == 0):
		//Nos conectamos a la BBDD
		$conexion = conectarpdo($host, $user, $password, $bbdd);
		// Creamos una variable con la consulta "UPDATE" a ejecutar
		$update = "update sedes set nombre = :nombre, direccion = :direccion where id = :id";
		// preparamos la consulta (bindParam)
		$consulta = $conexion->prepare($update);
		$consulta->bindParam(':nombre', $sede); 
		$consulta->bindParam(':direccion', $direccion); 
		$consulta->bindParam(':id', $idSede);
		// ejecutamos la consulta 
		$consulta->execute();
		$consulta = null;
       	$conexion = null;
       	// redireccionamos al listado de sedes
  		//header("Location: listado.php");
		exit();

	//Si hay errores, pintarlos en el correspondiente campo:
	else:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar una sede</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Modificar una sede</h1>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  			<input type="hidden" name="id" value="<?php echo $idSede ?>">
	    	<p>
	            <!-- Campo nombre de la sede -->
	            <input type="text" name="nombre" placeholder="Sede" value="<?php echo $sede //pintamos la sede ?>">
	            <?php
	            	//Si hay error en la sede...
					if (isset($errores["sede"])):
				
	            ?>
	            	<p class="error"><?php echo $errores["sede"] //Pintamos el error en la sede ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo dirección de la sede -->
	            <input type="text" name="direccion" placeholder="Dirección" value="<?php echo $direccion //pintamos la dirección ?>">
	            <?php
	            	//Si hay error en la dirección...
					if (isset($errores["direccion"])):
	            ?>
	            	<p class="error"><?php echo $errores["direccion"] //Pintamos el error en la dirección ?></p>
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
        	<a href="listado.php">Volver al listado de sedes</a>
    	</div>
   	</div>
</body>
</html>
<?php
   	endif;
?>