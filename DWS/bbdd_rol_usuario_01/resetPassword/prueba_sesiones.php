<?php
    session_name('reset');
    session_start();
    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');
    
    $erroresMail = [];
    $erroresPass = [];
    $estado = isset($_SESSION['estado']) ? $_SESSION['estado'] : 0;
    $passwordForm = "";
    $validatedPassword = "";
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        session_unset();
        session_destroy();
        session_start(); // Reiniciar sesión limpia
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if ($estado == 0) {//comprobar que email está en base de datos.
            $emailForm = obtenerValorCampo('email');
            if (!empty($emailForm)) {//Si el campo del formulario viene por la vía correcta se hace la consulta.
                if (validarEmail($emailForm)) {
                    $conexion = conectarPDO($host, $user, $password, $bbdd);
                    $consulta = $conexion -> prepare("SELECT * FROM usuarios WHERE email = ?");
                    $consulta -> bindParam(1, $emailForm);

                    $consulta -> execute();
                    desconectarPDO($consulta, $conexion);
                    if ($consulta -> rowCount() >0) {//si la consulta obtiene algún resultado damos valor a estado 1 lo que
                                                    //nos permitirá pasar al siguiente paso y creamos la variable de sesion email,
                                                    //necesaria para identificar posteriormente al usuario en el update.
                        $_SESSION['estado'] = 1;
                        $_SESSION['email'] = $emailForm;
                        }else {}

                
                }
                else {
                    $erroresMail[] = "Formato email incorrecto.";
                }
                
            }
            else{//Si viene por la vía correcta se devuelve al login.
                echo "EL campo email del formulario en el primer paso viene vacío.";
                header('refresh: 3, url=../login.php');
            }
        }

        if ($estado == 1) {//Se valida que la contraseña cumple con las condiciones y se envía un correo de confirmación que 
                        //se encargará dfe hacer el update con la contraseña. 
            $passwordForm = obtenerValorCampo('password1');
            $passwordConf = obtenerValorCampo('password2');
            if (empty($passwordForm) || empty($passwordConf)) {//Valida que los dos campos estén cumplimentados.
                $erroresPass[] = "Dede introducir la contraseña en los dos campos";
            }else {
                if ($passwordForm == $passwordConf) {//Valida que los dos campos sean iguales y envía el correo.
                    $validatedPassword = validarContrasena($passwordConf); 
                    //$validatedPassword = $passwordConf
                    if ( $validatedPassword === true || $passwordConf === '12345') {//si la contraseña cumple los requisitos
                                                                                //o es la universal 12345, se envía el correo
                                                                                //con el linkn que hara el update de la contraseña.
                        $token = bin2hex(openssl_random_pseudo_bytes(16));
                        $conexion = conectarPDO($host, $user,$password, $bbdd);
                        $consulta = $conexion -> prepare ("INSERT INTO usuarios")
                            
                        )
                        /* Envío De Email Con Token */
                        // Cabecera
                        $headers = [
                            "From" => "dwes@php.com",
                            "Content-type" => "text/plain; charset=utf-8"
                        ];
                        // Variables para el email

                        $emailEncode = urlencode($validatedEmail);
                        
                        $tokenEncode = urlencode($token);
                        // Texto del email
                        $textoEmail = "
                        Hola!\n 
                        Pulsa en el link para activar tu contraseña.\n
                        Para activar entra en el siguiente enlace:\n
                         http://localhost:3000/DWS/bbdd_rol_usuario/resetPassword/prueba_sesiones.php?email=$emailEncode&token=$tokenEncode&password=$validatedPassword
                            ";
                        // Envio del email
                        mail($email, 'Activa tu contraseña', $textoEmail, $headers);
                        /* Redirección a login.php con GET para informar del envío del email */
                       //header('Location: identificarse.php?registrado=1');
                        //exit();
                    }else {
                        $erroresPass[] = $validatedPassword;
                    }

                }
                else{
                    $erroresPass[] = "Las dos contraseñas deben ser iguales";
                }
            }
            /*else {
                $_SESSION['estado'] = 3;
            }*/
        }
        if ($estado === 1) {
            session_unset();
            session_destroy();
        }
       $estado = $_SESSION['estado'];

       
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {//si me llega el get es del email compruebo ue tengo las variables que necesito
                                            // y hago el update
        if (isset($_GET['email']) && isset($_GET['token']) && isset($_GET['password'])) {
            $decodedEmail = urldecode($_GET['email']);
            $decodedToken = urldecode($_GET['token']);
            $decodedPassword = urldecode($_GET['password']);
            $conexion = conectarPDO($host, $user, $password, $session);
            $consulta = $conexion -> prepare ("UPDATE usuarios 
                                                SET password = ?
                                                WHERE email = ? AND token = ?
                                                ");
            $consulta -> bindParam(1, $decodedPassword);
            $consulta -> bindParam(2, $decodedEmail);
            $consulta -> bindParam(3, $decodedToken);
            if ($consulta -> rowCount()>0) {
                $estado = 2;
            }
            else {
                $estado = 3;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    
    <h1>Actualizar contraseña</h1>   
    <?php echo "<p>El valor del estado de la sesion actual es = " . $estado . "</p>
                <p>El valor del passwordForm = " . $passwordForm . "</p>
                <p>El valor del ValidatedPassword = " . $validatedPassword . "</p>"; ?>             
    
    <?php if ($estado === 0): /*paso1*/?>
        <!-- Mostramos errores por HTML -->
        <?php if (count($erroresMail) > 0): ?>
            <ul class="errores">
                <?php 
                    foreach ($erroresMail as $error) 
                    {
                        echo '<li>' . $error . '</li>';
                    } 
                ?> 
            </ul>
        <?php endif; ?>
            <!-- Formulario -->
             <p>Introduzca y envíe su correo</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <p>
                    <!-- Campo de Email -->
                    <label>
                        Correo electrónico
                        <input type="text" name="email">
                    </label>
                </p>
                <input type="submit" value="Enviar">
            </form>

    <?php elseif ($estado === 1): /*paso2*/?>
        <?php if (count($erroresPass) >0): ?>
            <ul class="errores">
                <?php 
                    foreach ($erroresPass as $error) 
                    {
                        echo '<li>' . $error . '</li>';
                    } 
                ?> 
            </ul>
        <?php endif; ?>
            <!-- Campo de Contraseña -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">    
            <p>
                <label>
                    Contraseña
                    <input type="password" name="password1">
                </label>
            </p>
            <p>
                <label>
                    Confirme contraseña
                    
                    <input type="password" name="password2">
                </label>
            </p>
        
            <p>
                <!-- Botón submit -->
                <input type="submit" value="Actualizar contraseña">
            </p>
        </form>
    
    <?php elseif ($estado === 2): ?>
            <h1>Contraseña actualizada</h1>
    <?php elseif ($estado === 3): ?>
        <h1>No se ha podido actualizar la contraseña</h1>
    <?php endif; ?>
</body>
</html>