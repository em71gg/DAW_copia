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
    <h1>Alta de un nuevo departamento</h1>
    <?php
		// Crea las variables necesarias para introducir los campos y comprobar errores.

		$errores = [];
		$nombrefinal="";
		$presupuestoFinal="";
		$idFinal="";
		$sede="";

    	if ($_SERVER["REQUEST_METHOD"]=="POST")
    	{
		    //Crea las variables con los requisitos de longitud en el campo nombre del departamento 
			$longitudMinNomDept = 3;
			$longitudMaxNomdept = 100;
						    
		    // Obtenemos el campo del nombre del departamento, presupuesto y sede a partir de la función "obtenerValorCampo"
		    
	    	//-----------------------------------------------------
	        // Validaciones
	        //-----------------------------------------------------
	        // Nombre del departamento: Debe tener la longitud exigida. Si no, preparad las variables para mostrar el error.
	        if(isset($_POST['nombre']) && $_POST['nombre'] !== 0){
				$nombre = obtenerValorCampo('nombre');
				if (!validarLongitudCadena($nombre, $longitudMinNomDept ,$longitudMaxNomdept)){
					$errores['nombreDptoInadecuado'] = "El nombre del departamento debe tener entre 3 y 100 caracteres";

				} 
				else 
				{
					// En caso de que los datos sean correctos, comprobar que no exita un departamento con ese nombre.
					// Para ello, conectaros a la bbdd, usar el comando SELECT en departamento y buscar el nombre de departamento que se ha introducido.
					// Si el resultado es distinto de nulo, informar de que el departamento ya existe.
					
					$conexion = conectarPDO($host, $user, $password, $bbdd);
					$consulta = $conexion -> prepare('SELECT nombre  FROM departamentos WHERE nombre = ?');//consulta de los nombres de los departamentos existentes
					$consulta -> bindParam(1, $nombre);				
					
					$consulta-> execute();

					

					
					
					if($consulta -> rowCount()>0){
						$errores['nombreDeptExistente'] = "Ya existe un departamento con ese nombre.</br>Debes nombrar uno nuevo";
					}else{
						$nombrefinal=$nombre;
					}
					$conexion= null;//cierro registro y conexión con la base de datos
					$registro=null;		
				}
			}else{
				$errores['nombreDeptVacio'] = "Debes asignar un nombre al departamento";
			}
			
			

	        // Presupuesto del departamento: entero positivo
			if (isset($_POST['presupuesto']) && $_POST['presupuesto'] !==""){
				$presupuesto=obtenerValorCampo('presupuesto');//aunque se espera un número filtro la entrada  
				if (!validarEnteroPositivo($presupuesto)) 
				{
				$errores['presupuestoNoValido'] = "Debe introducir un presupuesto que sea entero y positivo";
				}else{
					$presupuestoFinal = $presupuesto;
				}

				// Nombre de la sede. Usamos la función validarEnteroPositivo() porque el valor del campo sede será el id.
			}else{
				$errores['presupuestovacio'] = "No se puede dejar el presupuesto en blanco.</br>Debe introducir un presupuesto que sea entero y positivo";
			}

			if (isset($_POST['sede']) && $_POST['sede'] !==""){
				$sede = obtenerValorCampo('sede');
				if (!validarEnteroPositivo($sede))
				{
					$errores['idNoValida'] = "Debes selecionar algún campo";
				}else{
					$idFinal = $sede;
				}
			}else{
				$errores['idVacia']= "Debes seleccionar algún campo";
			}
    	}
  	?>

  	<?php
  		//Si hay algún error, tenemos que mostrar los errores en la misma página, manteniendo los valores bien introducidos.
		if (count($errores)>0 || empty($_REQUEST) ){
  
  	?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre del departamento -->
	            <input type="text" name="nombre" placeholder="Departamento" value="<?php echo $nombrefinal?$nombrefinal:'';//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['nombreDptoInadecuado'])){
						echo "<p class='error'>" . $errores['nombreDptoInadecuado'] . "</p>";
					}
					if (!empty($errores['nombreDeptExistente'])){
						echo "<p class='error'>" . $errores['nombreDeptExistente'] . "</p>";
					}
					if (!empty($errores['nombreDeptVacio'])){
						echo "<p class='error'>" . $errores['nombreDeptVacio'] . "</p>";
					}
	            ?>
	        </p>
	        <p>
	            <!-- Campo presupuesto del departamento -->
	            <input type="number" name="presupuesto" placeholder="Presupuesto" value="<?php $presupuestoFinal?$presupuestoFinal:'';//Introducir valor ?>">
	            <?php
	            	if (!empty($errores['presupuestoNoValido'])){
						echo "<p class='error'>" . $errores['presupuestoNoValido'] . "</p>";
					}
					if (!empty($errores['presupuestoVacio'])){
						echo "<p class='error'>" . $errores['presupuestoVacio'] . "</p>";
					}
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
	            	if (!empty($errores['idNoValida'])) {
						echo "<p class='error'>" . $errores['idNoValida'] . "</p>";
					}
					if (!empty($errores['idVacia'])) {
						echo "<p class='error'>" . $errores['idVacia'] . "</p>";
					}
	            ?>
	            
	        </p>
	        <p>
	            <!-- Botón submit -->
	            <input type="submit" value="Guadar">
	        </p>
	    </form>
  	<?php

		// Si no hay errores, conectar a la BBDD:
		}	else{
				try{
					$conexion = conectarPDO($host, $user, $password, $bbdd);
					
					// consulta a ejecutar
					$consulta = $conexion -> prepare 
											("INSERT INTO
													departamentos
													(nombre, presupuesto, sede_id)

												VALUES
													(?,?,?)

											");
					$consulta -> bindParam(1, $nombrefinal);
					$consulta -> bindParam(2, $presupuestoFinal);
					$consulta -> bindParam(3, $idFinal);
					$consulta -> execute();

					$consulta = null;
					$conexion = null;

					// preparar la consulta (usar bindParam)
					

					// ejecutar la consulta y captura de la excepcion (try/catch)
					

					// redireccionamos al listado de departamentos
					header("Location: listado.php");

					
					$exit();
				}catch(PDOException $e){
					echo "Error al insertar el nuevo departamento: " . $e -> getMessage();
				}
			}
    ?>
   <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de departamentos</a>
        </div>
   </div>
</body>
</html>