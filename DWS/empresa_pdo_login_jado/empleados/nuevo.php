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
   	$nEmpleado = "";
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

   	if ($_SERVER["REQUEST_METHOD"]=="POST")
   	{
	    $comprobarValidacion = true;
 
	 	// Obtenemos los diferentes campos del formulario a partir de la función "obtenerValorCampo"
		$nEmpleado = obtenerValorCampo("nombre");
		$apellidos = obtenerValorCampo("apellidos");
		$email = obtenerValorCampo("email");
		$salario = obtenerValorCampo("salario");
		$hijos = obtenerValorCampo("numeroHijos");
		$idNacionalidad = obtenerValorCampo("nacionalidad");
		$idDepartamento = obtenerValorCampo("departamento");

    	//-----------------------------------------------------
        // Validaciones
        //-----------------------------------------------------
        // Nombre del empleado: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
        if (!validarLongitudCadena($nEmpleado, $longitudMinimaNombre ,$longitudMaximaNombre)) 
        {
			$errores["nombre"] = 'El nombre del empleado no cumple los requisitos.';
        	$nEmpleado = "";
        }

        // Apellidos del empleado: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
        if (!validarLongitudCadena($apellidos, $longitudMinimaApellidos, $longitudMaximaApellidos))
        {
			$errores["apellidos"] = 'Los apellidos del empleado no cumple los requisitos.';
        	$apellidos = ""; 
        }

        // Correo electrónico del empleado: Debe ser un email válido y con la longitud correcta. Si no, preparad las variables para mostrar el error.
        if (!validarEmail($email))
        {
            $errores["email"] = 'El email del empleado no cumple los requisitos.';
        	$email = "";
        }
        elseif (strlen($email)>$longitudMaximaEmail)
        {
			$errores["email"] = 'El email del empleado no cumple los requisitos.';
        	$email = "";
        }

        // El número de hijos del empleado a partir de la función "validarEnteroLimites"
        if (!validarEnteroLimites($hijos, $limiteInferiorHijos,$limiteSuperiorHijos))
        {
			$errores["hijos"] = 'El número de hijos del empleado no cumple los requisitos.';
        	$hijos = ""; //??
        }

        // Salario del empleado a partir de la función "validarDecimalPositivo"
        if (!validarDecimalPositivo($salario))
        {
			$errores["salario"] = 'El salario no cumple los requisitos.';
        	$salario = ""; //??
        } 

        // Nombre del departamento a partir de la función "validarEnteroPositivo", ya que usaremos el id
        if (!validar_Entero_Positivo($idDepartamento))
        {
            $errores["departamento"] = 'El departamento no es correcto.';
        	$idDepartamento = ""; //??
        }
	        
        // Nacionalidad del empleado a partir de la función "validarEnteroPositivo", ya que usaremos el id
        if (!validar_Entero_Positivo($idNacionalidad))
        {
			$errores["nacionalidad"] = 'La nacionalidad no es correcta.';
        	$idNacionalidad = ""; //??
        }
	        
   	}
?>

<?php
	// Si no hay errores, conectar a la BBDD:
	if ($comprobarValidacion && count($errores) == 0):
		$conexion = conectarPDO($host, $user, $password, $bbdd);
  			
		// consulta a ejecutar (insert)
		$insert = "insert into empleados (nombre, apellidos, email, salario, hijos, departamento_id, pais_id) values (:nombre, :apellidos, :email, :salario, :hijos, :departamento_id, :pais_id)";

		// preparar la consulta (usar bindParam)
		$consulta = $conexion->prepare($insert);

		$consulta->bindParam(':nombre', $nEmpleado); 
		$consulta->bindParam(':apellidos', $apellidos);
		$consulta->bindParam(':email', $email);
		$consulta->bindParam(':salario', $salario); 
		$consulta->bindParam(':hijos', $hijos);
		$consulta->bindParam(':departamento_id', $idDepartamento);
		$consulta->bindParam(':pais_id', $idNacionalidad);

		// ejecutar la consulta y captura de la excepcion
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
    <h1>Alta de un nuevo empleado</h1>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    	<p>
            <!-- Campo nombre del empleado -->
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nEmpleado //Introducir valor ?>">
            <?php
            	if (isset($errores["nombre"]) /*¿Existe errores en el nombre del empleado?*/):
            ?>
            	<p class="error"><?php echo $errores["nombre"] //Pintar el error ?></p>
            <?php
            	endif;
            ?>
        </p>
        <p>
            <!-- Campo apellidos del empleado -->
            <input type="text" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos //Introducir valor ?>">
            <?php
            	if (isset($errores["apellidos"]) /*¿Existe errores en los apellidos del empleado?*/):
            ?>
            	<p class="error"><?php echo $errores["apellidos"]//Pintar el error ?></p>
            <?php
            	endif;
            ?>
        </p>
        <p>
            <!-- Campo correo electrónico del empleado -->
            <input type="text" name="email" placeholder="Correo electrónico" value="<?php echo $email//Introducir valor ?>">
            <?php
            	if (isset($errores["email"]) /*¿Existe errores en el email del empleado?*/):
            ?>
            	<p class="error"><?php echo $errores["email"] //Pintar el error ?></p>
            <?php
            	endif;
            ?>
        </p>
        <p>
            <!-- Campo salario del empleado -->
            <input type="number" step="0.01" name="salario" placeholder="Salario" value="<?php echo $salario //Introducir valor ?>">
            <?php
            	if (isset($errores["salario"]) /*¿Existe errores en el salario del empleado?*/):
            ?>
            	<p class="error"><?php echo $errores["salario"]//Pintar el error ?></p>
            <?php
            	endif;
            ?>
        </p>
        <p>
            <!-- Campo número de hijos del empleado -->
            <input type="number" name="numeroHijos" placeholder="Número de hijos" value="<?php echo $hijos //Introducir valor ?>">
    	    <?php
            	if (isset($errores["hijos"]) /*¿Existe errores en el número de hijos del empleado?*/):
            ?>
            	<p class="error"><?php echo $errores["hijos"] //Pintar el error ?></p>
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

			//Usamos un SELECT para traer los valores del id y la nacionalidad (ordenar por nacionalidad).
			//Obtenemos el resultado de la consulta con la función "resultadoConsulta($conexion, $consulta)"
			$consulta = "SELECT id, nacionalidad FROM paises ORDER BY nacionalidad";
	        $resultado = resultadoConsulta($conexion, $consulta);

			while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
  		?>
  				<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $nacionalidad ? "selected" : ""?>><?php echo $row["nacionalidad"]; ?></option>
  		<?php
			endwhile;

			$consulta = null;
   			$conexion = null;
		?>

		</select>
        <?php
           	if (isset($errores["nacionalidad"]) /*¿Existen errores en la nacionalidad?*/):
        ?>
           	<p class="error"><?php $errores["nacionalidad"] //Pintar el error ?></p>
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

				//Usamos un SELECT para traer los valores del id y el departamento (ordenar por nombre).
				//Obtenemos el resultado de la consulta con la función "resultadoConsulta($conexion, $consulta)"
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
	          	if (isset($errores["departamento"]) /*¿Existen errores en la departamento?*/):
	        ?>
	           	<p class="error"><?php $errores["departamento"] //Pintar el error ?></p>
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
            <a href="listado.php">Volver al listado de empleados</a>
        </div>
   </div>
</body>
</html>
<?php
	endif;
?>