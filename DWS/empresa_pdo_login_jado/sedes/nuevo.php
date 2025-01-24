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
   	$sede = "";
   	$direccion = "";

	if ($_SERVER["REQUEST_METHOD"]=="POST")
   	{
		$comprobarValidacion = true;
		//Crea las variables con los requisitos de los campos (longitud del nombre de la sede y la dirección)
	    $lonSedMin=3;
		$lonSedMax=50;
		$lonDirMin=10;
		$lonDirMax=255;
	    
	    // Obtenemos el campo del nombre de la sede y dirección a partir de la función "obtenerValorCampo"
	    $sede = obtenerValorCampo("nombre");
	    $direccion = obtenerValorCampo("direccion");  
	    
    	//-----------------------------------------------------
        // Validaciones
        //-----------------------------------------------------
        // Nombre de la sede: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
        // Nombre de la sede
	
		if (!validarLongitudCadena($sede, $lonSedMin ,$lonSedMax)) 
        {
            $errores["sede"] = 'El nombre de la sede no cumple los requisitos.';
        	$sede = "";
        }
        // Dirección de la sede
        if (!validarLongitudCadena($direccion, $lonDirMin, $lonDirMax)) 
        {
            $errores["direccion"] = 'La dirección de la sede no cumple los requisitos.';
            $direccion = "";
        }
	}
  	
	// Si no hay errores, conectar a la BBDD:
	if ($comprobarValidacion && count($errores) == 0):
		$conexion = conectarPDO($host, $user, $password, $bbdd);

		// consulta a ejecutar
		$insert = "insert into sedes (nombre, direccion) values (:nombre, :direccion)";

		// preparar la consulta (usar bindParam)
		$consulta = $conexion->prepare($insert);
		$consulta->bindParam(':nombre', $sede); 
		$consulta->bindParam(':direccion', $direccion);	

		// ejecutar la consulta 
		$consulta->execute();

		$consulta = null;
       	$conexion = null;
       	// redireccionamos al listado de sedes
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
    <title>Alta nueva sede</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Alta de una nueva sede</h1>
  		<form action="<?php print htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre de la sede -->
	            <input type="text" name="nombre" placeholder="Sede" value="<?php echo $sede //Introducir valor ?>">
	            <?php
					if (isset($errores["sede"]) /*¿Existe errores en el nombre de la sede?*/):
	            ?>
	            	<p class="error"><?php echo $errores["sede"] //Pintar el error ?></p>
	            <?php 
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo dirección de la sede -->
	            <input type="text" name="direccion" placeholder="Dirección" value="<?php echo $direccion //Introducir valor ?>">
	            <?php
	            	if (isset($errores["direccion"]) /*¿Existen errores en la dirección?*/):
	            ?>
	            	<p class="error"><?php echo $errores["direccion"] //Pintar el error ?></p>
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
			<a href="nuevo.php">Añadir</a>
        </div>
   </div>
</body>
</html>

<?php
	endif;
?>