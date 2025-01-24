<?php
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar departamento</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
<body>
    <h1>Modificar empleado</h1>
    <?php
		// crea las variables para la comprobación de los datos y conectamos con la BBDD para obtener y pintar los datos de la id que acabamos de enviar a la página
		$errores = [];
    	$comprobarValidacion = false;
    	$limiteInferiorHijos = 0;
    	$limiteSuperiorHijos = 10;
    	$idEmpleado = 0;
    	    	
    	if (count($_REQUEST) > 0) 
    	{

    		if (isset($_GET["idEmpleado"])) 
    		{
            	$idEmpleado = $_GET["idEmpleado"];
            	//Obtenemos los datos del empleado. Para ello
            	//Conectamos a la BBDD
				$conexion = conectarpdo($host, $user, $password, $bbdd);
        		// Montamos la consulta a ejecutar
				$select = "SELECT nombre, apellidos, email, salario, hijos, pais_id, departamento_id FROM empleados WHERE id = ? ";
		        // prepararamos la consulta
				$consulta = $conexion->prepare($select);
		        // parámetro (usamos bindParam)
				$consulta->bindParam(1, $idEmpleado);
		        // ejecutamos la consulta 
				$consulta->execute();
		        // comprobamos si hay algún registro 
				if ($consulta->rowCount() == 0)
				{
					//Si no lo hay, desconectamos y volvemos al listado original
					$consulta = null;
					$conexion = null;
					header("Location: listado.php");
					exit();
				}
				else 
				{
					// Si hay algún registro, Obtenemos el resultado (usamos fetch())
					$registro = $consulta->fetch();
					$nombre = $registro['nombre'];
		        	$apellidos = $registro['apellidos'];
		        	$email = $registro['email'];
		        	$salario = $registro['salario'];
		        	$hijos = $registro['hijos'];
		        	$nacionalidad = $registro['pais_id'];
		        	$departamento = $registro['departamento_id'];
			        
				}
            } 
            else 
            {
				$comprobarValidacion = true;
				$nombre = "";
   				$longitudMinimaNombre = 3;
				$longitudMaximaNombre = 50;
   				$apellidos = "";
   				$longitudMinimaApellidos = 3;
				$longitudMaximaApellidos = 150;
   				$email = "";
   				$longitudMaximaEmail = 120;
   				$salario = "";
   				$hijos = "";
				$limiteInferiorHijos = 0;
   				$limiteSuperiorHijos = 10;
				$nacionalidad = "";
   				$departamento = "";
				// Comenzamos la comprobación de los datos introducidos.
				// Creamos las variables con los requisitos de cada campo (función "obtenerValorCampo")
    			$idEmpleado = obtenerValorCampo("id");
			    $nombre = obtenerValorCampo("nombre");
			    $apellidos = obtenerValorCampo("apellidos");
			    $email = obtenerValorCampo("email");
			    $salario = obtenerValorCampo("salario");
			    $hijos = obtenerValorCampo("numeroHijos");
			    $nacionalidad = obtenerValorCampo("nacionalidad");
			    $departamento = obtenerValorCampo("departamento");
			    
		    	//-----------------------------------------------------
		        // Validaciones
		        //-----------------------------------------------------
		        // Compruebo que el id del empleado se corresponde con uno que tengamos 
	        	//conectamos a la bbdd
				$conexion = conectarpdo($host, $user, $password, $bbdd);
	        	// preparamos la consulta SELECT a ejecutar
				$select = "SELECT nombre, apellidos, email, salario, hijos, pais_id, departamento_id FROM empleados WHERE id = ? ";
				// preparamos la consulta (bindParam)
				$consulta = $conexion->prepare($select);
				$consulta->bindParam(1, $idEmpleado);
				// ejecutamos la consulta 
				$consulta->execute();
				// comprobamos si algún registro 
				if ($consulta->rowCount() == 0)
				{
					//Si no lo hay, desconectamos y volvemos al listado original
					$consulta = null;
					$conexion = null;
					header("Location: listado.php");
					exit();
				}
		        // Nombre del empleado: validamos la longitud. Si no es correcta, generamos el error.
		        if (!validarLongitudCadena($nombre, $longitudMinimaNombre, $longitudMaximaNombre)) 
		        {
					//Generar msj de error
					$errores["nombre"] = "El nombre del empleado no cumple los requisitos.";
					$nombre="";

		        }

		        // Apellidos del empleado: validamos la longitud. Si no es correcta, generamos el error.
		        if (!validarLongitudCadena($apellidos, $longitudMinimaApellidos ,$longitudMaximaApellidos)) 
		        {
					//Generar msj de error
					$errores["apellidos"] = "Los apellidos del empleado no cumplen los requisitos.";
					$apellidos="";
		        }

		        // Correo electrónico del empleado: validamos que sea un email (validarEmail) y la longitud máxima.
		        if (!validarEmail($email))
		        {
		            //Generar msj de error
					$errores["email"] = "El correo electrónico del empleado no cumple los requisitos.";
					$email = "";

		        }
		        elseif (strlen($email)>$longitudMaximaEmail)
		        {
					//Generar msj de error
					$errores["email"] = "El correo electrónico supera la longitud máxima.";
					$email = "";
		        }

		        // El número de hijos del empleado: validamos con validarEnteroLimites()
		        if (!validarEnteroLimites($hijos, $limiteInferiorHijos, $limiteSuperiorHijos))
		        {
		           //Generar msj de error
					$errores["hijos"] = "El número de hijos está fuera de rango.";
					$hijos = "";
		        }

		        // Salario del empleado: validamos que sea decimal positivo validarDecimalPositivo().
		        if (!validarDecimalPositivo($salario))
		        {
		            //Generar msj de error
					$errores["salario"] = "El salario debe ser un decimal positivo.";
					$salario = "";
		        } 

		        // Nombre del departamento (el id): validamos con validarEnteroLimites()
		        if (!validar_Entero_Positivo($departamento))
		        {
		            //Generar msj de error
					$errores["departamento"] = "Se ha producido un error con el valor del departamento.";
					$departamento = "";
		        }
		        

		        // Nacionalidad del empleado (el id): validamos con validarEnteroLimites()
		        if (!validar_Entero_Positivo($nacionalidad))
		        {
		            //Generar msj de error
					$errores["nacionalidad"] = "Se ha producido un error con el valor de la nacionalidad.";
					$nacionalidad = "";
		        }
		    }
    	}
		else{
			header("Location: listado.php");
			exit();
		}
  	?>

  	<?php
  		//Si hay errores, pintarlos en el correspondiente campo:
		  if (!$comprobarValidacion || count($errores) > 0):
  	?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  			<input type="hidden" name="id" value="<?php echo $idEmpleado ?>">
	    	<p>
	            <!-- Campo nombre del empleado -->
	            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre //pintamos nombre del empleado ?>">
	            <?php
	            	//Si hay error en el nombre...
					if (isset($errores["nombre"])):
	            ?>
	            	<p class="error"><?php echo $errores["nombre"] //Pintamos el error en la sede ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo apellidos del empleado -->
	            <input type="text" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos //pintamos apellidos del empleado ?>">
	            <?php
	            	if (isset($errores["apellidos"])):
	            ?>
	            	<p class="error"><?php echo $errores["apellidos"] //Pintamos el error en los apellidos ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo correo electrónico del empleado -->
	            <input type="text" name="email" placeholder="Correo electrónico" value="<?php echo $email //pintamos email del empleado ?>">
	            <?php
	            	if (isset($errores["email"])):
	            ?>
	            	<p class="error"><?php echo $errores["email"] //Pintamos el error en el email ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo salario del empleado -->
	            <input type="number" step="0.01" name="salario" placeholder="Salario" value="<?php echo $salario //pintamos salario del empleado ?>">
	            <?php
	            	if (isset($errores["salario"])):
	            ?>
	            	<p class="error"><?php echo $errores["salario"] //Pintamos el error en el salario ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo número de hijos del empleado -->
	            <input type="number" name="numeroHijos" placeholder="Número de hijos" value="<?php echo $hijos //pintamos hijos del empleado ?>">
	            <?php
	            	if (isset($errores["hijos"])):
	            ?>
	            	<p class="error"><?php echo $errores["hijos"] //Pintamos el error en los hijos ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo nacionalidad del empleado -->
	            <select id="nacionalidad" name="nacionalidad">
	            	<option value="">Seleccione Nacionalidad</option>
	            <?php
				//nos conectamos a la bbdd y pintamos las diferentes nacionalidades en el desplegable, ordenado por nacionalidad.
	            	$conexion = conectarPDO($host, $user, $password, $bbdd);

	            	$consulta = "SELECT id, nacionalidad FROM paises ORDER BY nacionalidad";
	            	
	            	$resultado = resultadoConsulta($conexion, $consulta);

  					while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $nacionalidad ? "selected" : "" ?>><?php echo $row["nacionalidad"]; ?></option>
  				<?php
  					endwhile;

  					$consulta = null;

        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	//Si hay error en la nacionalidad...
					if (isset($errores["nacionalidad"])):
	            ?>
	            	<p class="error"><?php echo $errores["nacionalidad"] //Pintamos el error en la nacionalidad ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo departamento del empleado -->
	            <select id="departamento" name="departamento">
	            	<option value="">Seleccione Departamento</option>
	            <?php
				//nos conectamos a la bbdd y pintamos los diferentes departamentos en el desplegable, ordenado por el nombre del departamento.
	            	$conexion = conectarPDO($host, $user, $password, $bbdd);

	            	$consulta = "SELECT id, nombre FROM departamentos ORDER BY nombre";
	            	
	            	$resultado = resultadoConsulta($conexion, $consulta);

  					while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $departamento ? "selected" : ""?>><?php echo $row["nombre"]; ?></option>
  				<?php
  					endwhile;
  					
  					$consulta = null;

        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	//Si hay error en el departamento...
					if (isset($errores["departamento"])):
	            ?>
	            	<p class="error"><?php echo $errores["departamento"] //Pintamos el error en el departamento ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Botón submit -->
	            <input type="submit" value="Guadar">
	        </p>
	    </form>
  	<?php
  		//Si no hay errores:
		else:
  			//Nos conectamos a la BBDD
			  $conexion = conectarpdo($host, $user, $password, $bbdd);
			// Creamos una variable con la consulta "UPDATE" a ejecutar
			$insert = "update empleados set nombre = :nombre, apellidos = :apellidos, email = :email, salario = :salario, hijos = :hijos, pais_id = :nacionalidad, departamento_id = :departamento WHERE id = :idEmpleado";
			// preparamos la consulta (bindParam)
			$consulta = $conexion->prepare($insert);

			$consulta->bindParam(':nombre', $nombre);
			$consulta->bindParam(':apellidos', $apellidos); 
			$consulta->bindParam(':email', $email);
			$consulta->bindParam(':salario', $salario); 
			$consulta->bindParam(':hijos', $hijos);
			$consulta->bindParam(':nacionalidad', $nacionalidad); 
			$consulta->bindParam(':departamento', $departamento);
			$consulta->bindParam(':idEmpleado', $idEmpleado);

			// ejecutamos la consulta 

			
			// ejecutar la consulta y mostramos el error
			try 
			{
				$consulta->execute();
			}
			catch (PDOException $exception)
			{
           		exit($exception->getMessage());
        	}

			$consulta = null;

        	$conexion = null;

        	// redireccionamos al listado de empleados
  			header("Location: listado.php");
			exit();
  			
    	endif;
    ?>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de empleados</a>
        </div>
   	</div>
    
</body>
</html>