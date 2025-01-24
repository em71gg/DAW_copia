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
    <title>Modificar piezas</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
<body>
    <h1>Modificar piezas</h1>
    <?php
		 $nombre = "";
		 $color = "";
		 $precio = "";
		 //$categoria ="";
		 $errores = [];
		 $registroErrores = false;
		 if(count($_REQUEST)>0){

			if(isset($_GET['idPieza'])){//si me viene por el get.
				$idPieza = $_GET['idPieza']; //Tomo el valor del control en una variable 
                //conecto y cojo el resto de variables para pintar los campos
                $conexion = conectarPDO($host, $user, $password, $bbdd);
				try{
					$consulta = $conexion -> prepare ("SELECT * FROM pieza WHERE id= $idPieza");
					$consulta -> execute();

					if($consulta -> rowCount() == 0){//Si el get no da un valor existente en la base de datos es que me
													//ha entrado por otra parte y lo llevo al index.
						header('location: index.html');
					}
					else{//Si lo hay tomo el resto de valores
						$registro = $consulta -> fetch(PDO::FETCH_ASSOC);
						$nombre = $registro['nombre'];
						$color = $registro['color'];
						$precio = $registro['precio'];
						//$categoria = $registro['id_categoria'];
					}
				}catch(PDOException $e){
					echo "<p>" . $e -> getMessage() . "</p>";
				}finally{
					desconectarPDO($consulta, $conexion);
				}
                
			}
			else{
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					$longMinNombre = 3;
					$longMaxNombre =50;
					$longMinColor = 3;
					$longMaxColor = 50;
					$idPieza = $_POST['id'];

					$nombre = obtenerValorCampo('nombre');
					$color = obtenerValorCampo('color');
					$precio = obtenerValorCampo('precio');
					
					if(!empty($nombre)){//Si el nombre se ha enviado se comprueba que cumpla los criterios
						if(!validarLongitudCadena($nombre, $longMinNombre, $longMaxNombre)){
							$errores['nombre'] = "Debe proporcionar un nombre que tenga  entre 3 y 50 caracteres";
						}else{//Compruebo si el nombre ya existe
							$conexion = conectarPDO($host, $user, $password, $bbdd); //Como tiene su try lo dejo fuera del siguiente
							try{//consulto a la base de datos para comprobar si existe una piexa con el nombre dado en el campo
								$consulta = $conexion -> prepare("SELECT nombre FROM pieza WHERE nombre = ?");
								$consulta -> bindParam(1, $nombre);
								$consulta -> execute();
		
								if($consulta -> rowCount()>0){//Si existe creo el error
									$errores['nombreExistente'] = "Ya existe esa pieza, debe dar de alta una nueva";
								}else{//Si no creo la variable que usaré en la consulta de inserción asignándole el valor de la variable $nombre
									$nombrePieza = $nombre;
								}
							}catch(PDOException $e){
								echo "<p>" . $e -> getMessage() . "</p>";
							}finally{
								desconectarPDO($consulta, $conexion);
							}
						}
					}
					else{//Si no llega ningún nombre o llega en blanco
						$errores['faltaNombre'] = "Debe escribir un nombre de producto";
					}
					if(!empty($color)){//Si el color se ha enviado se comprueba que cumpla los criterios
						if(!validarLongitudCadena($color, $longMinColor, $longMaxColor)){
							$errores['color'] = "El color de la pieza debe tener entre 3 y 50 caracteres.";
						}else{
							$colorPieza = $color;
						}
					}
					else{//Si deja el color en blanco
						$errores['faltaColor'] = "Debe dar el color de la pieza.";
					}
					if(!empty($precio)){
				
						if(!validarDecimalPositivo($precio)){
							$errores['precio'] = "El precio de la pieza debe ser un valor positivo.";
						}else{
							$precioPieza = $precio;
						}
					}
					else{
						$errores['faltaPrecio'] = "Debe introducir un precio para la pieza.";
					 }
					
					if(count($errores)== 0){//Si no hay errores en la validacion de los campos se hace el update en la base de datos.
						$conexion = conectarPDO($host, $user, $password, $bbdd)	;
						try{
							$consulta = $conexion -> prepare("UPDATE
																	pieza 
																SET 
																	 nombre = :nombre, 
																	 color = :color, 
																	 precio = :precio
																WHERE id = :id
																");
							$consulta -> bindParam(':nombre', $nombrePieza);
							$consulta -> bindParam(':color', $colorPieza);
							$consulta -> bindParam(':precio', $precioPieza);
							$consulta -> bindParam(':id', $idPieza);
							$consulta -> execute();
							if($consulta -> rowCount()>0){//Si se ha efectuado el insert
								echo "Pieza actualizada";
								header('refresh:3 ; url = listado.php');
								desconectarPDO($consulta, $conexion);
								exit();
							}
		
						}catch(PDOException $e){
							echo"Error al actualizar la pieza en la base de datos.</br>";//mensaje para el usuario.
							echo "<p>" . $e -> getMessage() . "</p>"; //mensaje para mi.
						}finally{
							desconectarPDO($consulta, $conexion);
							
						}
					}else{//Si hay errores el flag lo pongo en true.
							$registroErrores = true;
						}

				}

			}

		 }else{//Si entra por metodos fraudulentos lo devuelvo al index
			header('location: index.html');
		 }
  	?>

	<?php
        if($registroErrores || !empty($_REQUEST))://Si hay errores o acabamos de llegar al formulario
    ?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<input type="hidden" name="id" value="<?php echo $idPieza?>">
	    	<p>
	            <!-- Campo nombre  -->
	            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre ?>">
				<?php
				if(!empty($errores['nombre'])):
	            ?>
				<p class= "error"><?php echo $errores['nombre']?></p>
                <?php
                   endif;
                ?>
				<?php
				if(!empty($errores['nombreExistente'])):
	            ?>
				<p class= "error"><?php echo $errores['nombreExistente']?></p>
                <?php
                   endif;
                ?>
	            <?php
				if(!empty($errores['faltaNombre'])):
	            ?>
				<p class= "error"><?php echo $errores['faltaNombre']?></p>
                <?php
                   endif;
                ?>
	        </p>
	        <p>
	            <!-- Campo color  -->
	            <input type="text" name="color" placeholder="Color" value="<?php echo $color?>">
	            <?php
				if(!empty($errores['color'])):
	            ?>
				<p class= "error"><?php echo $errores['color']?></p>
                <?php
                   endif;
                ?>
				 <?php
				if(!empty($errores['faltaColor'])):
	            ?>
				<p class= "error"><?php echo $errores['faltaColor']?></p>
                <?php
                   endif;
                ?>
	        </p>
	        <p>
	            <!-- Campo precio -->
	            <input type="number" name="precio" placeholder="Precio"  value="<?php echo $precio?>" step="0.1">
				<?php
				if(!empty($errores['precio'])):
	            ?>
				<p class= "error"><?php echo $errores['precio']?></p>
                <?php
                   endif;
                ?>
				<?php
				if(!empty($errores['faltaPrecio'])):
	            ?>
				<p class= "error"><?php echo $errores['faltaPrecio']?></p>
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
		endif;
    ?>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de departamentos</a>
        </div>
   	</div>
    
</body>
</html>