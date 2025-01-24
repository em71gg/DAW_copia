<?php
    // Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<body>
    <header>
        <div class="header-container">
            
            <a href="../cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
    <h1>Modificar departamento</h1>
    <?php

    $errores=[];
    $nombre = "";
    $presupuesto = "";
    $sede = "";
    $registroErrores = false;
    	

        if (count($_REQUEST) > 0) {
            if (isset($_GET["idDepartamento"])) {
                $idDepartamento = $_GET["idDepartamento"];
                //echo "id departemaent: " . $idDepartamento;
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                try{
                    $consulta = $conexion -> prepare
                 ("SELECT                       
                    nombre, 
                    presupuesto, 
                    sede_id
                    FROM 
                    departamentos
                    WHERE
                        id=?");

                    $consulta -> bindParam(1, $idDepartamento);

                    $consulta ->execute();
                    echo "consulta : " . $consulta ->rowCount();

                    if ($consulta->rowCount() == 0){
                        //Si no lo hay, desconectamos y volvemos al listado original
                        desconectarPDO($consulta, $conexion);
                        header('location: listado.php');
                        //exit();
                    }else{
                        // Si hay algún registro, Obtenemos el resultado (usamos fetch())
                        $registro = $consulta->fetch(PDO::FETCH_ASSOC);

                        $nombre= $registro['nombre'];
                        $presupuesto = $registro['presupuesto'];
                        $sede = $registro['sede_id'];
                    }

                }catch(PDOException $e){
                    echo "<p>" . $e -> getMessage() . "</p>";
                }finally{
                    desconectarPDO($consulta, $conexion);
                }
            }
            else{

                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    
                    $longMinNombre = 3;
                    $longMaxNombre = 100;
                    $idDepartamento = $_POST['id'];
                    
                    $nombre = obtenerValorCampo('nombre');
                    $presupuesto = obtenerValorCampo('presupuesto');
                    $sede = obtenerValorCampo('sede');

                   // echo "monbre: " . $nombre ."</br>";
                    //echo "presupuesto: " . $presupuesto."</br>";

                    $conexion = conectarPDO($host, $user, $password, $bbdd);

                    $consulta =$conexion -> prepare 
                    ("SELECT * FROM departamentos WHERE id= ?");

                    $consulta -> bindParam(1, $idDepartamento);

                    $consulta -> execute();

                    if ($consulta->rowCount() == 0){
                         //Si no lo hay, desconectamos y volvemos al listado original
                        desconectarPDO($consulta, $conexion);
                        header('location: listado.php');
                        exit();
                    }
                    if (!validarLongitudCadena($nombre, $longMinNombre ,$longMaxNombre)){
                         //Generar msj de error
                        $errores['errorNombre'] = 'Debe introducir un nombre que tenga entre 3 y 100 caracteres.';
                    }else{
                        $conexion = conectarPDO($host, $user, $password, $bbdd);

                        $consulta =$conexion -> prepare 
                        ("SELECT * FROM departamentos WHERE nombre= ?");
    
                        $consulta -> bindParam(1, $nombre);
    
                        $consulta -> execute();
                        if ($consulta->rowCount() > 1){
                            $errores['errorNombreExiste'] = 'Debe introducir un que no exista.';
                        }
                    }
                    if (!validarEnteroPositivo($presupuesto)) {
                        $errores['errorPresupuesto'] = 'Debe introducir un numero positivo.';
                    }
                    if (!validarEnteroPositivo($sede)){
                        $errores['errorSede'] = 'Error en el id de la sede.';
                    }

                    echo "monbre: " . $nombre ."</br>";
                    echo "prespupuesto: " . $presupuesto."</br>";
                    if(count($errores)===0){
                        //Nos conectamos a la BBDD
                        $conexion = conectarPDO($host, $user, $password, $bbdd);
                        $consulta = $conexion -> prepare
                            ("UPDATE
                                departamentos

                                SET
                                    nombre = ?,
                                    presupuesto = ?,
                                    sede_id = ?
                                WHERE id = ?");

                        $consulta -> bindParam(1, $nombre);
                        $consulta -> bindParam(2, $presupuesto);
                        $consulta -> bindParam(3, $sede);
                        $consulta -> bindParam(4, $idDepartamento);
                        try{
                            $consulta -> execute();
                        }catch ( PDOException $exception){
                            exit($exception->getMessage());
                        }

                        $consulta = null;
                        $conexion = null;
                        // redireccionamos al listado de empleados
                        header("Location: listado.php");
                        exit();
                    }
                    
                }

            }
            
        }

    ?>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <?php
                echo "monbre: " . $nombre ."</br>";
                echo "prespupuesto: " . $presupuesto."</br>";
            ?>

	    	<input type="hidden" name="id" value="<?php echo $idDepartamento ?>">
	    	<p>
	            <!-- Campo nombre del departamento -->
	            <input type="text" name="nombre" placeholder="Departamento" value="<?php echo $nombre//pintamos el departamento ?>">
	            <?php
	            	//Si hay error en el departamento...
                    if(!empty($errores['errorNombre'])):
	            ?>
	            	<p class="error"><?php echo $errores['errorNombre']//pintamos el error del departamento ?></p>
	            <?php
	            	endif;
	            ?>
                <?php
	            	//Si nombre departamento existe
                    if(!empty($errores['errorNombreExiste'])):
	            ?>
	            	<p class="error"><?php echo $errores['errorNombreExiste']//pintamos el error del departamento ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo presupuesto del departamento -->
	            <input type="number" name="presupuesto" placeholder="Presupuesto" value="<?php echo $presupuesto//Pintamos el presupuesto ?>">
	            <?php
	            	//Si hay error en el presupuesto
                    if(!empty($errores['errorPresupuesto'])):
	            ?>
	            	<p class="error"><?php echo $errores['errorPresupuesto']//Pintamos el error en el presupuesto ?></p>
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
                    $consulta = ("SELECT * FROM sedes");

                   $resultado = resultadoConsulta($conexion, $consulta);

  					// Terminamos usando:
					while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
  				?>
  					<option value="<?php echo $row["id"]; ?>"  <?php echo $row["id"] == $sede ? "selected" : "" ?>><?php echo $row["nombre"]; ?></option>
  				<?php
  					endwhile;
  				
  				?>
  				</select>
  				
	            <?php
	            	//Si hay error en la sede...
                    if(!empty($errores['errorSede'])):
	            ?>
	            	<p class="error"><?php //Pintar el error en la sede ?></p>
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
            <a href="listado.php">Volver al listado de departamentos</a>
        </div>
   	</div>
    
</body>
</html>