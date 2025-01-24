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
    <?php
		// Crea las variables necesarias para introducir los campos y comprobar errores.
			

    	if ($_SERVER["REQUEST_METHOD"]=="POST")
    	{
		    //Crea las variables con los requisitos de longitud en el campo nombre del departamento 
						    
		    // Obtenemos el campo del nombre del departamento, presupuesto y sede a partir de la función "obtenerValorCampo"
		    
	    	//-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
	        // Nombre del departamento: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if (!validarLongitudCadena($, $ ,$)) 
	        {

	        } 
	        else 
	        {
	        	// En caso de que los datos sean correctos, comprobar que no exita un departamento con ese nombre.
				// Para ello, conectaros a la bbdd, usar el comando SELECT en departamento y buscar el nombre de departamento que se ha introducido.
				// Si el resultado es distinto de nulo, informar de que el departamento ya existe.
	        	
				$conexion = conectarPDO($host, $user, $password, $bbdd);

	        }

	        // Presupuesto del departamento: entero positivo
	        if (!validarEnteroPositivo($)) 
	        {
	           
	        } 

	        // Nombre de la sede. Usamos la función validarEnteroPositivo() porque el valor del campo sede será el id.
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
	            <!-- Campo nombre del departamento -->
	            <input type="text" name="nombre" placeholder="Departamento" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existe errores en el nombre del departamento?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo presupuesto del departamento -->
	            <input type="number" name="presupuesto" placeholder="Presupuesto" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existen errores en el presupuesto?*/)
	            ?>
	            	<p class="error"><?php echo //Pintar el error ?></p>
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
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $sede ? "selected" : "" ?>><?php echo $row["nombre"]; ?></option>
  				<?php
  					endwhile;
  					
  					$resultado = null;
        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	if (/*¿Existen errores en la sede?*/)
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
  			
			// consulta a ejecutar
			

			// preparar la consulta (usar bindParam)
			

			// ejecutar la consulta y captura de la excepcion (try/catch)
			

        	// redireccionamos al listado de departamentos
  			header("Location: listado.php");
  			
    	endif;
    ?>
   <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de departamentos</a>
        </div>
   </div>
</body>
</html>