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
    <title>Modificar una categoría</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Modificar una categoría</h1>
    <?php
		$errores = [];
        $nombre= "";
        $nuevoNombre = "";
        $registroErrores = false;
        if(count($_REQUEST)>0){

            if(isset($_GET['idCategoria'])){//si me viene por el get.
                $idCategoria = $_GET['idCategoria']; //Tomo el valor del control en una variable 
                //conecto y cojo el resto de variables para pintar los campos del formulario al cargar
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                try{
                    $consulta = $conexion -> prepare ("SELECT * FROM categoria WHERE id= $idCategoria");
                    $consulta -> execute();

                    if($consulta -> rowCount() == 0){//Si el get no da un valor existente en la base de datos
                        header('location: index.html');
                    }else{//Si lo hay tomo el resto de valores
                        $registro = $consulta -> fetch(PDO::FETCH_ASSOC);
                        $nombre = $registro['nombre'];
                        }
                }catch(PDOException $e){
                    echo "<p>" . $e -> getMessage() . "</p>";
                }finally{
                    desconectarPDO($consulta, $conexion);
                }
                
            }
            else{
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $longMinCat = 3;
                    $LongMaxCat = 50;
                    $idCategoria = $_POST['id'];
                    $nombre = obtenerValorCampo('nombre');
                    if(!empty($nombre)){
                        if(!validarLongitudCadena($nombre, $longMinCat, $LongMaxCat)){
                            $errores['errorNombre'] = "El nombre de la categoria debe tener entre 3 y 50 caracteres";
                        }
                        else{
                            
                            try{
                                $conexion = conectarPDO($host, $user, $password, $bbdd);
                                $consulta = $conexion -> prepare ( "SELECT nombre FROM categoria where nombre = ?");
                                $consulta -> bindParam(1, $nombre);
                                $consulta -> execute();
                                if($consulta -> rowCount() >0){
                                    $errores['nombreExistente'] = "Ya existe una categoría con ese nombre";
                                }else{
                                    $nuevoNombre = $nombre;
                                    //echo "nombre categoria" . $nombreCategoria;
                                }
                                }catch(PDOException $e){
                                    echo "<p>" . $e -> getMessage() . "</p>";
                                }finally{
                                    desconectarPDO($consulta, $conexion);
                                }
                        }
                    }else{
                        $errores['nombreNoIndicado'] = "Debe dar un nombre a la categoría";
                    }
                    if(count($errores) == 0){
                        try{
                            $conexion = conectarPDO($host, $user, $password, $bbdd);
                            echo "id categoria post: " . $idCategoria;
                            $consulta = $conexion -> prepare("UPDATE categoria SET nombre = ? WHERE id = ?");
                            $consulta -> bindParam(1, $nuevoNombre);
                            $consulta -> bindParam(2, $idCategoria);
                            $consulta -> execute();
                            header('location: listado.php');
                        }catch(PDOException $e){
                            echo "<p>" . $e -> getMessage() . "</p>";
                        }finally{
                            desconectarPDO($consulta, $conexion);
                        }
                    }else{
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
            <input type="hidden" name="id" value="<?php echo $idCategoria?>">
	    	<p>
	            <!-- Campo nombre de la categoría -->
	            <input type="text" name="nombre" placeholder="Categoría" value="<?php echo $nombre ?>">
	            <?php
                    if(!empty($errores['errorNombre'])):
                ?>
                <p class= "error"><?php echo $errores['errorNombre']?></p>
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
                    if(!empty($errores['nombreNoIndicado'])):
                ?>
                <p class= "error"><?php echo $errores['nombreNoIndicado']?></p>
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
        endif;
    ?>  
    
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de sedes</a>
        </div>
   	</div>
    
</body>
</html>