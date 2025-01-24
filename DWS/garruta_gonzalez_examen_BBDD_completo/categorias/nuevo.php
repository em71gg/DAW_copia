<?php
    // Incluye ficheros de variables y funciones
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta nueva categoría</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Alta de una nueva categoría</h1>
    <?php
        $nombre="";
        $errores = [];
        $registroErrores = false;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $longMinCat = 3;
            $LongMaxCat = 50;
            $nombre = obtenerValorCampo('nombre');
            

            if(!empty($nombre)){
                if(!validarLongitudCadena($nombre, $longMinCat, $LongMaxCat)){
                    $errores['nombre'] = "El nombre de la categoría debe tener entre 3 y 50 caracteres";
                }else{
                    try{
                        $conexion = conectarPDO($host, $user, $password, $bbdd);
                        $consulta = $conexion -> prepare ("SELECT nombre FROM categoria WHERE nombre = ?");
                        $consulta -> bindParam(1, $nombre);
                        $consulta -> execute();

                        if($consulta -> rowCount() >0){
                            $errores['nombreExistente'] = "Ya existe esa categoría, debe dar de alta una nueva.";
                        }else{
                            $nombreCategoria = $nombre;
                        }
                    }catch(PDOException $e){
                        echo "<p>" . $e -> getMessage() . "</p>";
                    }finally{
                        desconectarPDO($consulta, $conexion);
                    }
                    
                }
                

            }else{
                $errores['faltaNombre'] = "Debe introducir un nombre de categoría";
            }

            if(count($errores) == 0){
                try{
                    $conexion = conectarPDO($host, $user, $password, $bbdd);
                    $consulta = $conexion -> prepare("INSERT INTO categoria (nombre) VALUES (?)");
                    $consulta -> bindParam(1, $nombreCategoria);
                    $consulta -> execute();

                    echo "Categoría dada de alta";
						header('refresh:3 ; url = listado.php');
						desconectarPDO($consulta, $conexion);
						exit();
                }catch(PDOException $e){
                    echo "<p>" . $e -> getMessage() . "</p>";
                    
                }finally{
                    desconectarPDO($consulta, $conexion);
                }
            }else{
                $registroErrores = true;
            }
        }
    ?>

    <?php
        if($registroErrores || EMPTY($_REQUEST)):
    ?>
  		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	    	<p>
	            <!-- Campo nombre de la categoría -->
	            <input type="text" name="nombre" placeholder="Categoría" value="<?php echo $nombre ?>">
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
	            <!-- Botón submit -->
	            <input type="submit" value="Guardar">
	        </p>
	    </form>
  	
	<?php
    endif;
    ?>
   <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de categorías</a>
        </div>
   </div>
</body>
</html>