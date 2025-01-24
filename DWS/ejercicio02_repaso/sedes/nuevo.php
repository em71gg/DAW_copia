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
    <?php

		// Crea las variables necesarias para introducir los campos y comprobar errores. 
			

    	if ($_SERVER["REQUEST_METHOD"]=="POST")
    	{
			//Crea las variables con los requisitos de los campos (longitud del nombre de la sede y la dirección)
		      
		    
		    // Obtenemos el campo del nombre de la sede y dirección a partir de la función "obtenerValorCampo"
		      
		    
	    	//-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
	        // Nombre de la sede: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if (!validarLongitudCadena($, $ ,$)) 
	        {
	            
	        }
	        // Dirección de la sede
	        if (!validarLongitudCadena($, $, $)) 
	        {
	            
	        }
    	}
  	?>

  	<?php
  		//Si hay algún error, tenemos que mostrar los errores en la misma página, manteniendo los valores bien introducidos.
		if (/*¿errores?*/):
  
  	?>
  		<form action="<?php print htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre de la sede -->
	            <input type="text" name="nombre" placeholder="Sede" value="<?php //Introducir valor ?>">
	            <?php
					if (/*¿Existe errores en el nombre de la sede?*/)
	            ?>
	            	<p class="error"><?php //Pintar el error ?></p>
	            <?php 
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo dirección de la sede -->
	            <input type="text" name="direccion" placeholder="Dirección" value="<?php //Introducir valor ?>">
	            <?php
	            	if (/*¿Existen errores en la dirección?*/)
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
				

			// ejecutar la consulta 
			

        	// redireccionamos al listado de sedes
  			header("Location: listado.php");
  			
    	endif;
    ?>
   <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de sedes</a>
        </div>
   </div>
</body>
</html>