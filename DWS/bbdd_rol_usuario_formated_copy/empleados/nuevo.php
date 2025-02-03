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
    <title>Alta nuevo departamento</title>
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
    <h1>Alta de un nuevo empleado</h1>
    <?php
	// Crea las variables necesarias para introducir los campos y comprobar errores.
        $errores = [];
    	$comprobarValidacion = false;
    	$limiteInferiorHijos = 0;
    	$limiteSuperiorHijos = 10;
    	$nombre = "";//campo
    	$longitudMinimaNombre = 3;
		$longitudMaximaNombre = 50;
    	$apellidos = "";//campo
    	$longitudMinimaApellidos = 3;
		$longitudMaximaApellidos = 150;
    	$email = "";//campo
    	$longitudMaximaEmail = 120;
    	$salario = "";//campo
    	$hijos = "";//campo
    	$nacionalidad = "";//nacionalidad
    	$departamento = "";

    	if ($_SERVER["REQUEST_METHOD"]=="POST")
    	{
		    
		    $comprobarValidacion = true;
		    
		 // Obtenemos los diferentes campos del formulario a partir de la función "obtenerValorCampo"
		  
			$nombre = obtenerValorCampo('nombre');
			$apellidos = obtenerValorCampo('apellidos');
			$email = obtenerValorCampo('email');
			$salario = obtenerValorCampo('salario');
			$hijos = obtenerValorCampo('numeroHijos');
			$nacionalidad = obtenerValorCampo('nacionalidad');
			$departamento = obtenerValorCampo('departamento');
	    	//-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
	        // Nombre del empleado: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if (!validarLongitudCadena($nombre, $longitudMinimaNombre ,$longitudMaximaNombre)) 
	        {
	            $errores['nombre'] = "El nombre debe tener entre 3 y 50 caracteres.";
	        }

	        // Apellidos del empleado: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if (!validarLongitudCadena($apellidos, $longitudMinimaApellidos, $longitudMaximaApellidos))
	        {
	            $errores['apellidos'] = "Los apellidos deben tener entre 3 y 150 caracteres.";
	        }

	        // Correo electrónico del empleado: Debe ser un email válido y con la longitud correcta. Si no, preparad las variables para mostrar el error.
	        if (!validarEmail($email))
	        {
	            $errores['email'] = "El correo electrónico debe ser válido.";
	        }
	        elseif (strlen($email)>$longitudMaximaEmail)
	        {
				$errores['longitudEmail'] = "El correo electrónico debe tener un máximo de 120 caracteres";
	        }

	        // El número de hijos del empleado a partir de la función "validarEnteroLimites"
	        if (!validarEnteroLimites($hijos, $limiteInferiorHijos,$limiteSuperiorHijos))
	        {
	            $errores['hijos'] = "El número de hijos debe ser un valor entre 0 y 10.";
	        }

	        // Salario del empleado a partir de la función "validarDecimalPositivo"
	        if (!validarDecimalPositivo($salario))
	        {
	            $errores['salario'] = "Debe seleccionar un salario positivo.";
	        } 


	        // Nombre del departamento a partir de la función "validarEnteroPositivo", ya que usaremos el id
	        if (!validarEnteroPositivo($departamento))
	        {
	            $errores['departamento'] = "Debe seleccionar una id de departamento de los disponibles en le desplegable.";
	        }
	        
	        // Nacionalidad del empleado a partir de la función "validarEnteroPositivo", ya que usaremos el id
	        if (!validarEnteroPositivo($nacionalidad))
	        {
				$errores['nacionalidad'] = "Debe seleccionar una id de nacionalidad de las disponibles en el desplegable.";
	        }
	        
    	}
  	?>

  	<?php
  		//Si hay algún error, tenemos que mostrar los errores en la misma página, manteniendo los valores bien introducidos.
		if (count($errores)>0 || empty($_REQUEST)):
  
  	?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre del empleado -->
	            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['nombre'] )):
	            ?>
	            	<p class="error"><?php echo $errores['nombre']//Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo apellidos del empleado -->
	            <input type="text" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['apellidos'])):
	            ?>
	            	<p class="error"><?php echo $errores['apellidos'] //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo correo electrónico del empleado -->
	            <input type="text" name="email" placeholder="Correo electrónico" value="<?php echo $email//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['email'])/*¿Existe errores en el email del empleado?*/):
	            ?>
	            	<p class="error"><?php echo $errores['email']//Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
				<?php
	            	if (!empty($errores['LongitudEmail'])/*¿Existe errores en el email del empleado?*/):
	            ?>
	            	<p class="error"><?php echo $errores['LongitudEmail']//Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo salario del empleado -->
	            <input type="number" step="0.01" name="salario" placeholder="Salario" value="<?php echo $salario//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['salario'])/*¿Existe errores en el salario del empleado?*/):
	            ?>
	            	<p class="error"><?php echo $errores['salario'] //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo número de hijos del empleado -->
	            <input type="number" name="numeroHijos" placeholder="Número de hijos" value="<?php echo $hijos//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['hijos'])/*¿Existe errores en el número de hijos del empleado?*/):
	            ?>
	            	<p class="error"><?php echo $errores['hijos']//Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo nacionalidad del empleado -->
	            <select id="nacionalidad" name="nacionalidad">
	            	<option value="">Seleccione Nacionalidad</option>
	            <?php
				//Conectar a la base de datos para tomar los posibles valores de las nacionalidades.
	            	$conexion = conectarPDO($host, $user, $password, $bbdd);
					$consulta = ("SELECT
									id, nacionalidad
								FROM paises
								ORDER BY nacionalidad"
							);
					$resultado = resultadoConsulta($conexion, $consulta);
						

				//Usamos un SELECT para traer los valores del id y la nacionalidad (ordenar por nacionalidad).
				//Obtenemos el resultado de la consulta con la función "resultadoConsulta($conexion, $consulta)"
					while($row = $resultado ->fetch(PDO::FETCH_ASSOC)):
  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $nacionalidad ? "selected" : ""?>><?php echo $row["nacionalidad"]; ?></option>
  				<?php
  					endwhile;

  					$consulta = null;
        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	if (!empty($errores['nacionalidad'])/*¿Existen errores en la nacionalidad?*/):
	            ?>
	            	<p class="error"><?php echo $errores['nacionalidad']//Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo departamento del empleado -->
	            <select id="departamento" name="departamento">
	            	<option value="">Seleccione Departamento</option>
	            <?php
	            	//Conectar a la base de datos para tomar los posibles valores de las nacionalidades.
	            	$conexion = conectarPDO($host, $user, $password, $bbdd);

				//Usamos un SELECT para traer los valores del id y la nacionalidad (ordenar por nacionalidad).
				$conexion = conectarPDO($host, $user, $password, $bbdd);
					$consulta = (
								"SELECT
								 id, nombre 
								 FROM departamentos
								 
								 ORDER BY nombre"
							);
					$resultado = resultadoConsulta($conexion, $consulta);


					while($row = $resultado ->fetch(PDO::FETCH_ASSOC)):
				//Obtenemos el resultado de la consulta con la función "resultadoConsulta($conexion, $consulta)"

  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $departamento ? "selected" : ""?>><?php echo $row["nombre"]; ?></option>
  				<?php
  					endwhile;
  					
  					$consulta = null;
        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	if (!empty($errores['departamento'])/*¿Existen errores en la departamento?*/):
	            ?>
	            	<p class="error"><?php echo $errores['departamento']//Pintar el error ?></p>
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
	// Si no hay errores, conectar a la BBDD:
  		else:
			try{
				$conexion = conectarPDO($host, $user, $password, $bbdd);
				
				// consulta a ejecutar (insert)
				$consulta = $conexion -> prepare ("INSERT INTO empleados (nombre, apellidos, email, salario, hijos, departamento_id, pais_id)
							VALUES (?, ?, ?, ?, ?, ?, ?)");
				
				// preparar la consulta (usar bindParam)

				
				$consulta->bindParam(1, $nombre);
				$consulta->bindParam(2, $apellidos);
				$consulta->bindParam(3, $email);
				$consulta->bindParam(4, $salario);
				$consulta->bindParam(5, $hijos);
				$consulta->bindParam(6, $departamento);
				$consulta->bindParam(7, $nacionalidad);
				
				// ejecutar la consulta y captura de la excepcion
				$consulta -> execute();
				// redireccionamos al listado de departamentos
				$consulta = null;
				$conexion =null;
				header("Location: listado.php");
			}catch(PDOException $e){
				echo "Error al insertar el nuevo empleado: " .$e -> getMessage();
			}
    	endif;
    ?>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de empleados</a>
        </div>
   </div>
</body>
</html>