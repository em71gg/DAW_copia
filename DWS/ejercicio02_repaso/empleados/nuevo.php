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
    <?php
	// Crea las variables necesarias para introducir los campos y comprobar errores.
      /*$errores = [];
    	$comprobarValidacion = false;
    	$limiteInferiorHijos = 0;
    	$limiteSuperiorHijos = 10;
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
    	$nacionalidad = "";
    	$departamento = "";*/

    	if ($_SERVER["REQUEST_METHOD"]=="POST")
    	{
		    
		    $comprobarValidacion = true;
		    
		 // Obtenemos los diferentes campos del formulario a partir de la función "obtenerValorCampo"
		  
		    
	    	//-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
	        // Nombre del empleado: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if (!validarLongitudCadena($, $ ,$)) 
	        {
	            
	        }

	        // Apellidos del empleado: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if (!validarLongitudCadena($, $, $))
	        {
	            
	        }

	        // Correo electrónico del empleado: Debe ser un email válido y con la longitud correcta. Si no, preparad las variables para mostrar el error.
	        if (!validarEmail($))
	        {
	            
	        }
	        elseif (strlen($)>$)
	        {
				
	        }

	        // El número de hijos del empleado a partir de la función "validarEnteroLimites"
	        if (!validarEnteroLimites($, $,$))
	        {
	            
	        }

	        // Salario del empleado a partir de la función "validarDecimalPositivo"
	        if (!validarDecimalPositivo($))
	        {
	            
	        } 


	        // Nombre del departamento a partir de la función "validarEnteroPositivo", ya que usaremos el id
	        if (!validarEnteroPositivo($))
	        {
	            
	        }
	        
	        // Nacionalidad del empleado a partir de la función "validarEnteroPositivo", ya que usaremos el id
	        if (!validarEnteroPositivo($))
	        {
				
	        }
	        
    	}
  	?>

  	<?php
  		//Si hay algún error, tenemos que mostrar los errores en la misma página, manteniendo los valores bien introducidos.
		if (/*¿errores?*/):
  
  	?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre del empleado -->
	            <input type="text" name="nombre" placeholder="Nombre" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existe errores en el nombre del empleado?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo apellidos del empleado -->
	            <input type="text" name="apellidos" placeholder="Apellidos" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existe errores en los apellidos del empleado?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo correo electrónico del empleado -->
	            <input type="text" name="email" placeholder="Correo electrónico" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existe errores en el email del empleado?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo salario del empleado -->
	            <input type="number" step="0.01" name="salario" placeholder="Salario" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existe errores en el salario del empleado?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo número de hijos del empleado -->
	            <input type="number" name="numeroHijos" placeholder="Número de hijos" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existe errores en el número de hijos del empleado?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
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

  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $nacionalidad ? "selected" : ""?>><?php echo $row["nacionalidad"]; ?></option>
  				<?php
  					endwhile;

  					$consulta = null;
        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	if (/*¿Existen errores en la nacionalidad?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
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
	            	if (/*¿Existen errores en la departamento?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
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
  			$conexion = conectarPDO($host, $user, $password, $bbdd);
  			
			// consulta a ejecutar (insert)

			// preparar la consulta (usar bindParam)
			
			
			// ejecutar la consulta y captura de la excepcion
			
        	// redireccionamos al listado de departamentos
  			header("Location: listado.php");
  			
    	endif;
    ?>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de empleados</a>
        </div>
   </div>
</body>
</html>