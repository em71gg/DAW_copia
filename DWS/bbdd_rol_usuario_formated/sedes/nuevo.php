

<?php
	// Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
	require_once("../utiles/funciones.php");
	require_once("../utiles/variables.php");
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta nueva sede</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
	<header>
        <div class="header-container">
            
            <a href="../cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
    <h1>Alta de una nueva sede</h1>
    <?php

		// Crea las variables necesarias para introducir los campos y comprobar errores.
		$errores = [];
		$nombreSedeFinal="";
		$direccionSedeFinal="";	
		$comprobarValidacion=false;	

    	if ($_SERVER["REQUEST_METHOD"]=="POST")
    	{
			//Crea las variables con los requisitos de los campos (longitud del nombre de la sede y la dirección)
		    $comprobarValidacion=true;
		    $longitudMinNombre = 3;
			$longitudMaxNombre = 50;
			$longitudMinDireccion = 10;
			$longitudMaxDireccion = 255;
			
		    // Obtenemos el campo del nombre de la sede y dirección a partir de la función "obtenerValorCampo"
			//-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
	        //aquí usaria el comprobar validación en vez de repetir isset($_POST)
		    if(isset($_POST['nombre']) && $_POST['nombre'] !== "" ){//Este control se envía siempre por tanto hay que ver que no está vacio
								
				$nombre = obtenerValorCampo('nombre');
				// Nombre de la sede: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
				if (!validarLongitudCadena($nombre, $longitudMinNombre ,$longitudMaxNombre))
				{
					$errores['nombreSedeInadecuado'] = "El nombre de la sede debe tener entre 5 y 50 caracteres";
					
				}else{//si los datos son correctos antes de definir el nombre final compruebo que no exista una sede con ese nombre
					$conexion = conectarPDO($host, $user,$password, $bbdd);
					$consulta = $conexion -> prepare ('SELECT *  FROM sedes WHERE nombre = ?');//consulta de los nombres de las sedes existentes preparada
					$consulta -> bindParam(1, $nombre);
					$consulta -> execute();
					$registros = $consulta -> fetch(PDO::FETCH_ASSOC);
					if($registros){
						$errores['nombreSedeExistente']= "El nombre de esa sede ya existe.</br>Debes nombrar una nueva.";
					}else{
						
						$nombreSedeFinal = $nombre;
					}
					$conexion = null;//cierro la conexion y los registros
					$registros = null;
				}
				
			}else {
				$errores['nombreNoIntroducido'] = "Debe escribir un nombre para la sede";

			}
		    if(isset($_POST['direccion'])  && $_POST['direccion'] !== ""){
				//$direccionCampo = $_POST['direccion'];
				$direccion = obtenerValorCampo('direccion');
				// Dirección de la sede
				if (!validarLongitudCadena($direccion, $longitudMinDireccion, $longitudMaxDireccion)) 
				{
					$errores['direccionSedeInadecuada'] = "La direccion de la sede debe tener entre 10 y 255 caracteres";
				}else{
					$direccionSedeFinal = $direccion;
				}
			}else{
				$errores['direccionNoIntroducida'] = "Debe escribir una direccion para la sede";
			}
    	}
	
  	?>
	
  	<?php
  		//Si hay algún error, tenemos que mostrar los errores en la misma página, manteniendo los valores bien introducidos.
		if (count($errores)>0 ||  empty($_REQUEST))://Además de que haya errores debe haberse enviado el request ya que sino me enviaría a la pagina de listados directamente
			//echo "<p> el nombre de la sede no ha sido introducido" .$errores['nombreNoIntroducido'];  
  	?>
  		<form action="<?php print htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre de la sede -->
	            <input type="text" name="nombre" placeholder= "Sede" value="<?php echo $nombreSedeFinal?$nombreSedeFinal:''; ?>">
	            <?php
					if(!empty($errores['nombreNoIntroducido'])):
				?>
				<p class="error"><?php echo $errores['nombreNoIntroducido'] ?></p>
				<?php
				endif;
				?>
				<?php
					if (!empty($errores['nombreSedeInadecuado'])):
				?>
					<p class="error"><?php echo $errores['nombreSedeInadecuado'] ?></p>
				<?php 
					endif;
				?>
				<?php
					if (!empty($errores['nombreSedeExistente'])):
				?>
					<p class="error"><?php echo $errores['nombreSedeExistente'] ?></p>
				<?php 
					endif;
				?>
				
				
	        </p>
	        <p>
	            <!-- Campo dirección de la sede -->
	            <input type="text" name="direccion" placeholder="Dirección" value="<?php echo $direccionSedeFinal?$direccionSedeFinal:'';//Introducir valor ?>">
	            <?php
					if(!empty($errores['direccionNoIntroducida'])):
				?>
					<p class="error"><?php echo $errores['direccionNoIntroducida'] ?></p>
					<?php
	            	endif;
	            ?>
					<?php
						if (!empty($errores['direccionSedeInadecuada'])):
					?>
						<p class="error"><?php echo $errores['direccionSedeInadecuada'] ?></p>
					<?php 
						endif;
					?>
				
	        </p>
	        <p>
	            <!-- Botón submit -->
	            <input type="submit" value="Guardar">
	        </p>
	    </form>
  	<?php

			// Si no hay errores, conectar a la BBDD:
  		else:
			try{
  			$conexion = conectarPDO($host, $user, $password, $bbdd);

			// consulta a ejecutar
			$consulta = $conexion -> prepare
							("INSERT INTO
								sedes
								(nombre,  direccion)
                            VALUES
                                (?,?)
                            ");
			
			$nombreCol = $nombreSedeFinal;
			$direccionCol = $direccionSedeFinal;	 

			// preparar la consulta (usar bindParam)
			$consulta->bindParam(1, $nombreCol);
			$consulta->bindParam(2, $direccionCol);

			// ejecutar la consulta 
			$consulta->execute();
			$consulta =null;
			$conexion = null;

        	// redireccionamos al listado de sedes
  			header("Location: listado.php");
			exit(); //este no lo tenía en el archivo mandado, pero es mejor ponerlo porque me lleva fuera y no sigue con el resto del código que y o ya nbo quiero , porque me ha llevado al header location 
		}catch(PDOException $e){
			echo "Error al insetar la sede: " . $e -> getMessage();
		}
    	endif;
    ?>
   <div class="contenedor">
        <div class="enlaces">
            <a href="./listado.php">Volver al listado de sedes</a>
        </div>
   </div>
</body>
</html>