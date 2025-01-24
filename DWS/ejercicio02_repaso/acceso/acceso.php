<?php
    require_once("../utiles/funciones.php");
    require_once("../utiles/variables.php");

    //ver si vienen datos del post cotinuo, si no devuelvo a login.
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //obtengo el valor de email para comprobar si existe en la BBDD
        $email = obtenerValorCampo('email');

        //comprobamos que el email llega bien y existe en la bbDD
        if (!empty($email)) {//este if puede ser redundante si compruebo una cadena vacia contra la base de datos ya lo tendría
            //conecto a base de datos
            try {
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                //consulta para obtener la contraseña asociada al email. Sirve como validación del email ya que si no existe en la BBDD no encontrará nada
                $consulta = $conexion -> prepare ("SELECT clave FROM empleados WHERE email = ?");
                $consulta -> bindParam(1, $email);
                //ejecuto la consulta
                $consulta -> execute();
                //si la consulta da resultados obtengo la contraseña y la valido con la introducida en el formulario
                if ($consulta -> rowCount()>0) {
                    $row = $consulta -> fetch();
                    $contrasena = $row['clave'];
                }
            } catch (PDOException $e) {
                echo "<p>" . $e -> getMessage() . "</p>";
            } finally {
                desconectarPDO($consulta, $conexion);
            }
            
            //cargamos la contraseña del formulario para validarla con la obtenida de la BBDD
            $contrasenaFormulario = isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : null;
            //Si las contraseñas coinciden, creo la sesion la variable de sesion y reconduzco a zona privada.
            if (password_verify($contrasenaFormulario, $contrasena)) {
                session_name('sesion-privada');
                session_start();
                $_SESSION['email'] = $_REQUEST['email'];
                //echo "variable sesion = " . $_SESSION['email'];
                header('location: ../index.php');
                exit();
            }
        } else {
            echo "Debe introducir un correo y una contraseña válidos".PHP_EOL;
            header('refresh: 3; url=../index.php');
        }
    } else {
        header('location: ../index.php');
    }

?>