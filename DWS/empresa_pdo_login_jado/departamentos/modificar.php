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
    <h1>Modificar departamento</h1>
    <?php
		// crea las variables para la comprobación de los datos y conectamos con la BBDD para obtener y pintar los datos de la id que acabamos de enviar a la página
		$errores = [];
    	$comprobarValidacion = false;
    	$idDepartamento = 0;
    	    	
    	if (count($_REQUEST) > 0) 
    	{
    		if (isset($_GET["idDepartamento"])) 
    		{
            	$idDepartamento = $_GET["idDepartamento"];

            	//Conectamos a la BBDD
				$conexion = conectarpdo($host, $user, $password, $bbdd);
        		// Montamos la consulta a ejecutar
				$select = "SELECT nombre, presupuesto, sede_id FROM departamentos WHERE id = ?";
		        // prepararamos la consulta
				$consulta = $conexion->prepare($select);
		        // parámetro (usamos bindParam)
				$consulta->bindParam(1, $idDepartamento);
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
					$departamento = $registro['nombre'];
	        		$presupuesto = $registro['presupuesto'];
					$idsede = $registro['sede_id'];
	            	$consulta = null;
		        	$conexion = null;
				}
            } 
            else 
            {
		    	// Comenzamos la comprobación de los datos introducidos.
				$comprobarValidacion = true;
				// Creamos las variables con los requisitos de cada campo
				$lonDepMin=3;
				$lonDepMax=100;	
			    // Obtenemos el campo del departamento, presupuesto y sede
			    $idDepartamento = obtenerValorCampo("id");
			    $departamento = obtenerValorCampo("nombre");
			    $presupuesto = obtenerValorCampo("presupuesto");
			    $idsede = obtenerValorCampo("sede");	//id de la sede
			 

				 //-----------------------------------------------------
		        // Validaciones
		        //-----------------------------------------------------
				// Comprueba que el id del departamento se corresponde con uno que tengamos 
				// conectamos a la bbdd
				$conexion = conectarpdo($host, $user, $password, $bbdd);
	        	// preparamos la consulta SELECT a ejecutar
				$select = "SELECT nombre, presupuesto, sede_id FROM departamentos WHERE id = ? ";
				// preparamos la consulta (bindParam)
				$consulta = $conexion->prepare($select);
				$consulta->bindParam(1, $idDepartamento);
				// ejecutamos la consulta 
				$consulta->execute();
				// comprobamos si algún registro 
				if ($consulta->rowCount() == 0)
				{
					//echo "No hay";
					//Si no lo hay, desconectamos y volvemos al listado original
					$consulta = null;
       				$conexion = null;
       				// redireccionamos al listado de departamentos
					header("Location: listado.php");
					exit();
				}

		        // Nombre del departamento: validamos la longitud. Si no es correcta, generamos el error.
		        if (!validarLongitudCadena($departamento, $lonDepMin ,$lonDepMax)) 
		        {
					$errores["departamento"] = 'El nombre del departamento no cumple los requisitos.';
        			$departamento = "";
		        } 
		        else 
		        {
		        	// Comprobar que no exita un departamento con ese nombre.
					//Para ello, te conectas a la bbdd, ejecutas un SELECT y comprueba si hay ya un departamento con ese nombre.
					$conexion = conectarpdo($host, $user, $password, $bbdd);
					$select = "SELECT nombre FROM departamentos WHERE nombre = ? AND  id != ?";
					$consulta = $conexion->prepare($select);
					$consulta->bindParam(1, $departamento);
					$consulta->bindParam(2, $idDepartamento);
					$consulta->execute();
					// comprobamos si, al ejecutar la consulta, tenemos más de un registro. En tal caso, generar el mensaje de error.
					if ($consulta->rowCount() > 0)
					{
						//Msj Error
						$errores["departamento"] = 'El nombre del departamento ya existe.';
        				$departamento = "";
					}
					
		        }

		        // Presupuesto del departamento: Validamos que sea entero positivo.
		        if (!validar_Entero_Positivo($presupuesto)) 
		        {
		            //Generar msj de error
					$errores["presupuesto"] = 'El presupuesto no cumple los requisitos.';
        			$presupuesto = "";
		        } 

		        // Nombre de la sede: : Validamos que sea entero positivo (el id)
		        if (!validar_Entero_Positivo($idsede))
		        {
					//Generar msj de error
					$errores["idsede"] = 'Se ha producido un error con el valor de la sede.';
        			$idsede = "";
		        }
		        
		       
			}
		    
    	}else{
			header("Location: listado.php");
			exit();
		} 
    	
  	?>

  	<?php
  		//Si hay errores, pintarlos en el correspondiente campo:
		if (!$comprobarValidacion || count($errores) > 0):
			//echo "<p>Errores: ".count($errores). "</p>";
			//print_r($errores);
  	?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<input type="hidden" name="id" value="<?php echo $idDepartamento ?>">
	    	<p>
	            <!-- Campo nombre del departamento -->
	            <input type="text" name="nombre" placeholder="Departamento" value="<?php echo $departamento//pintamos el departamento ?>">
	            <?php
	            	//Si hay error en el departamento...
					if (isset($errores["departamento"]) /*¿Existe errores en el nombre del departamento?*/):
	            ?>
	            	<p class="error"><?php $errores["departamento"]//pintamos el error del departamento ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo presupuesto del departamento -->
	            <input type="number" name="presupuesto" placeholder="Presupuesto" value="<?php echo $presupuesto//Pintamos el presupuesto ?>">
	            <?php
	            	if (isset($errores["presupuesto"])/*¿Existen errores en el presupuesto?*/):
	            ?>
	            	<p class="error"><?php echo $errores["presupuesto"]//Pintamos el error en el presupuesto ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo nombre de la sede -->
	            <select id="sede" name="sede">
	            	<option value="">Seleccione Sede</option>
	            <?php
	            	//Conectamos a la bbdd y hacemos un SELECT de las sedes para que aparezca en el desplegable del formulario.
					$conexion = conectarPDO($host, $user, $password, $bbdd);
					//Usamos un SELECT para traer los valores del id y en nombre de la sede.
            		$consulta = "SELECT id, nombre FROM sedes";
            		$resultado = resultadoConsulta($conexion, $consulta);
  					// Terminamos usando:
					while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
  				?>
  					<option value="<?php echo $row["id"]; ?>"  <?php echo $row["id"] == $idsede ? "selected" : "" ?>><?php echo $row["nombre"]; ?></option>
  				<?php
  					endwhile;
  				
  				?>
  				</select>
  				
	            <?php
	            	if (isset($errores["idsede"]) /*¿Existen errores en la sede?*/):
	            ?>
	            	<p class="error"><?php $errores["idsede"] //Pintar el error en la sede ?></p>
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
		// Si no hay errores
  		else:
  			
			//Nos conectamos a la BBDD
			$conexion = conectarpdo($host, $user, $password, $bbdd);
			// Creamos una variable con la consulta "UPDATE" a ejecutar
			$update = "update departamentos set nombre = :nombre, presupuesto = :presupuesto, sede_id = :idsede where id = :id";
			// preparamos la consulta (bindParam)
			$consulta = $conexion->prepare($update);
			$consulta->bindParam(':nombre', $departamento); 
			$consulta->bindParam(':presupuesto', $presupuesto); 
			$consulta->bindParam(':idsede', $idsede);
			$consulta->bindParam(':id', $idDepartamento);
			// ejecutamos la consulta 
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

        	// redireccionamos al listado de departamentos
  			header("Location: listado.php");
			echo "No hay errores";
			exit();
  			
    	endif;
    ?>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de departamentos</a>
        </div>
   	</div>
    
</body>
</html>