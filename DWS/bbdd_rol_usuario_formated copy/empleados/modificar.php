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
    <header>
        <div class="header-container">
            
            <a href="../cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
    <h1>Modificar empleado</h1>
<?php
	// crea las variables para la comprobación de los datos y conectamos con la BBDD para obtener y pintar los datos de la id que acabamos de enviar a la página
    $errores=[];
    
    $validarErrores=false;	

    $nombre = '';
    $email = '';
    $apellidos = '';
    $salario = '';
    $hijos = '';
    $departamento = '';
    $nacionalidad = '';
    
    if (count($_REQUEST) > 0){

        if(isset($_GET['idEmpleado'])){
            $idEmpleado = $_GET["idEmpleado"];

            //Obtenemos los datos del empleado. Para ello
            //Conectamos a la BBDD
            $conexion = conectarPDO($host, $user, $password, $bbdd);

            //$consulta = $conexion -> prepare ("SELECT * FROM empleados Where id=?");
            $consulta = $conexion -> prepare 
                ("SELECT 
                    e.nombre, 
                    e.apellidos,
                    e.email,
                    e.hijos, 
                    e.salario,
                    p.id as pais_id,
                    p.nacionalidad,
                    d.id as departamento_id,
                    d.nombre as departamento,
                    e.id
                FROM 
                    empleados e
                INNER JOIN 
                    departamentos d ON e.departamento_id = d.id 
                INNER JOIN 
                    paises p ON e.pais_id = p.id
                WHERE 
                    e.id=?");

            $consulta -> bindParam(1, $idEmpleado);

            $consulta -> execute();	
            
            if ($consulta->rowCount() == 0){
                //Si no lo hay, desconectamos y volvemos al listado original
                desconectarPDO($consulta, $conexion);
                header('location: listado.php');
                exit();
            }else{
                // Si hay algún registro, Obtenemos el resultado (usamos fetch())
                $registro = $consulta->fetch(PDO::FETCH_ASSOC);
                $nombre = $registro['nombre'];
                $apellidos = $registro['apellidos'];
                $email = $registro['email'];
                $salario = $registro['salario'];
                $hijos = $registro['hijos'];
                $departamento = $registro['departamento_id'];
                $nacionalidad = $registro['pais_id'];
                /*
                echo"<p>En el get: </p>";
                echo "id empleado: " . $idEmpleado ."</br>";
                echo "nombre empleado: " .$nombre ."</br>";
                echo "apellidos empleado: " .$apellidos ."</br>";
                echo "email empleado: " .$email ."</br>";
                echo "hijos empleado: " .$hijos ."</br>";
                echo "sueldo empleado: " .$salario . "</br>";
                echo "id nacionalidad empleado: " .$nacionalidad ."</br>";
                echo "id departamento:" .$departamento ."</br>";
                */
            }
        }else{
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $idEmpleado = $_POST['id'];
				
                $longMinNombre = 3;
                $longMaxNombre = 50;
                $longMinApellidos = 3;
                $longMaxApellidos = 150;
                $minHijos = 0;
                $maxHijos = 10;
                $longitudMaximaEmail = 120;

                $nombre = obtenerValorCampo('nombre');
                $email = obtenerValorCampo('email');
                $apellidos = obtenerValorCampo('apellidos');
                $salario = obtenerValorCampo('salario');
                $hijos = obtenerValorCampo('numeroHijos');
                $departamento = obtenerValorCampo('departamento');
                $nacionalidad = obtenerValorCampo('nacionalidad');

                $conexion = conectarPDO($host, $user, $password, $bbdd);

                $consulta = $conexion -> prepare ("SELECT * FROM empleados Where id=?");

                $consulta -> bindParam(1, $idEmpleado);

                $consulta -> execute();

                if ($consulta->rowCount() == 0){
                    //Si no lo hay, desconectamos y volvemos al listado original
                    desconectarPDO($consulta, $conexion);
                    header('location: listado.php');
                    exit();
                }
                // Nombre del empleado: validamos la longitud. Si no es correcta, generamos el error.
                if ($nombre ==="" || !validarLongitudCadena($nombre, $longMinNombre ,$longMaxNombre)) 
                {
                    //Generar msj de error
                    $errores['errorNombre'] = 'Debe introducir un nombre que tenga entre 3 y 50 caracteres.';
                }

                // Apellidos del empleado: validamos la longitud. Si no es correcta, generamos el error.
                if ($apellidos === "" || !validarLongitudCadena($apellidos, $longMinApellidos ,$longMaxApellidos)) 
                {
                    //Generar msj de error
                    $errores['errorApellidos'] = 'Debe introducir sus apellidos, entre 3 y 150 caracteres.';
                }

                // Correo electrónico del empleado: validamos que sea un email (validarEmail) y la longitud máxima.
                if ($email === "" || !validarEmail($email))
                {
                    //Generar msj de error
                    $errores['errorValidEmail'] = 'Debe introducir un email válido.';
                }
                if (strlen($email)>$longitudMaximaEmail)
                {
                    //Generar msj de error
                    $errores['errorLongEmail'] = 'La direción email debe tener un máximo de 120 caracteres.';
                }
                // El número de hijos del empleado: validamos con validarEnteroLimites()
                if ($hijos ==="" || !validarEnteroLimites($hijos, $minHijos,$maxHijos)){
                        //Generar msj de error
					    $errores['errorHijos'] = 'Debe introducir un valor entre 0 y 10.';
                }
                // Salario del empleado: validamos que sea decimal positivo validarDecimalPositivo().
                if ($salario === "" || !validarDecimalPositivo($salario))
                {
                    //Generar msj de error
                    $errores['errorSalario'] = 'Debe introducir un valor con dos decimales.';
                } 
                
                // Nombre del departamento (el id): validamos con validarEnteroLimites()
                if ( !validarEnteroPositivo($departamento))
                {
                    //Generar msj de error
                    $errores['errorDepartamento'] = 'La id del departamento no es válida.';

                }
                // Nacionalidad del empleado (el id): validamos con validarEnteroLimites()
                if (!validarEnteroPositivo($nacionalidad))
                {
                    //Generar msj de error
                    $errores['errorNacionalidad'] = 'La id del pais no es válida.';
                    
                }
                //si no hay errores
                if(count($errores)==0){
                    
                    //Nos conectamos a la BBDD
                    $conexion = conectarPDO($host, $user, $password, $bbdd);
                    // Creamos una variable con la consulta "UPDATE" a ejecutar
                    $consulta = $conexion -> prepare ("UPDATE 
                                                            empleados 
                                                        SET 
                                                            nombre = ?, 
                                                            apellidos = ?, 
                                                            email = ?, 
                                                            hijos = ?, 
                                                            salario = ?, 
                                                            departamento_id = ?, 
                                                            pais_id = ? 
                                                        WHERE id = ?
                                                    ");
                    // preparamos la consulta (bindParam)
                    $consulta->bindParam(1, $nombre);
                    $consulta->bindParam(2, $apellidos);
                    $consulta->bindParam(3, $email);
                    $consulta->bindParam(4, $hijos);
                    $consulta->bindParam(5, $salario);
                    $consulta->bindParam(6, $departamento);
                    $consulta->bindParam(7, $nacionalidad);
                    $consulta->bindParam(8, $idEmpleado);
                    
                    // ejecutamos la consulta 
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
  			<input type="hidden" name="id" value="<?php echo $idEmpleado ?>">
	    	<p>
	            <!-- Campo nombre del empleado -->
	            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre//pintamos nombre del empleado ?>">
	            <?php
	            	//Si hay error en el nombre...	
					if(!empty($errores['errorNombre'])):           
				?>
	            	<p class="error"><?php echo $errores['errorNombre']//Pintamos el error en la sede ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo apellidos del empleado -->
	            <input type="text" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos//pintamos apellidos del empleado ?>">
	            <?php
	            	if (!empty($errores["errorApellidos"])):
	            ?>
	            	<p class="error"><?php echo $errores["errorApellidos"];//Pintamos el error en los apellidos ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo correo electrónico del empleado -->
	            <input type="text" name="email" placeholder="Correo electrónico" value="<?php echo $email//pintamos email del empleado ?>">
	            <?php
	            	if (!empty($errores["errorValidEmail"])):
	            ?>
	            	<p class="error"><?php echo $errores["errorValidEmail"];//Pintamos el error en el email ?></p>
	            <?php
	            	endif;
	            ?>
				<?php
	            	if (!empty($errores["errorLongEmail"])):
	            ?>
	            	<p class="error"><?php echo $errores["errorLongEmail"];//Pintamos el error en el email ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo salario del empleado -->
	            <input type="number" step="0.01" name="salario" placeholder="Salario" value="<?php echo $salario;//pintamos salario del empleado ?>">
	            <?php
	            	if (!empty($errores["errorSalario"])):
	            ?>
	            	<p class="error"><?php echo $errores["errorSalario"]; //Pintamos el error en el salario ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo número de hijos del empleado -->
	            <input type="number" name="numeroHijos" placeholder="Número de hijos" value="<?php echo $hijos//pintamos hijos del empleado ?>">
	            <?php
	            	if (!empty($errores["hijos"])):
	            ?>
	            	<p class="error"><?php //Pintamos el error en los hijos ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo nacionalidad del empleado -->
	            <select id="nacionalidad" name="nacionalidad">
	            	<option value="">Seleccione Nacionalidad</option>
	            <?php
				//nos conectamos a la bbdd y pintamos las diferentes nacionalidades en el desplegable, ordenado por nacionalidad.
	            	$conexion = conectarPDO($host, $user, $password, $bbdd);

	            	$consulta = "SELECT id, nacionalidad FROM paises ORDER BY nacionalidad";
	            	
	            	$resultado = resultadoConsulta($conexion, $consulta);

  					while ($row = $resultado->fetch(PDO::FETCH_ASSOC)):
  				?>
  					<option value="<?php echo $row["id"]; ?>" <?php echo $row["id"] == $nacionalidad ? "selected" : "" ?>><?php echo $row["nacionalidad"]; ?></option>
  				<?php
  					endwhile;

  					$consulta = null;

        			$conexion = null;
  				?>
  				</select>
  				
	            <?php
	            	//Si hay error en la nacionalidad...
                    if(isset($errores['errorNacionalidad'])):
	            ?>
	            	<p class="error"><?php echo $errores['errorNacionalidad'];//Pintamos el error en la nacionalidad ?></p>
	            <?php
	            	endif;
	            ?>
	        </p>
	        <p>
	            <!-- Campo departamento del empleado -->
	            <select id="departamento" name="departamento">58
	            	<option value="">Seleccione Departamento</option>
	            <?php
				//nos conectamos a la bbdd y pintamos los diferentes departamentos en el desplegable, ordenado por el nombre del departamento.
	            	$conexion = conectarPDO($host, $user, $password, $bbdd);

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
	            	//Si hay error en el departamento...
                    if(isset($errores['errorDepartmento'])):
	            ?>
	            	<p class="error"><?php echo $errores['errorDepartmento'];//Pintamos el error en el departamento ?></p>
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