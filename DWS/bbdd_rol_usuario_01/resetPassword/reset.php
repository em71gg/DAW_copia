<?php
    session_name('reset');
    session_start();
    require_once('../utiles/variables.php');
    require_once('../utiles/funciones.php');
    $erroresMail = [];
    $erroresPass = [];
    //$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : "";
    //$checkMail = false;
    //$rol = isset($_REQUEST['rol']) ? $_REQUEST['rol'] : "";
    //$passwordForm = isset($_REQUEST["password"]) ? $_REQUEST["password"] : "";
  
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
       if (!isset($_SESSION['checkMail'])) $_SESSION['checkMail'] = false;
        $email = obtenerValorCampo('email'); //Obtengo el campo email
        
        if(!$_SESSION['checkMail']){
            if(!empty($email)){ //compruebo que no está vacío
                if (!validarEmail($email)) {//Compruebo que el correo tenga un formato válido.
                    $erroresMail[] = "Campo Email no tiene un formato válido";
                }else{//Si todo ok compruebo que existe el correo electrónico y pertenece a un usuario activo.
                    $conexion = conectarPDO($host, $user, $password, $bbdd);
                    $consulta = $conexion -> prepare ("SELECT COUNT(*) as numero 
                                                        FROM usuarios 
                                                        WHERE email = :email AND activo = 1"
                                                        );
                    $consulta -> bindParam(':email', $email);
    
                    $consulta -> execute();
    
                    if($consulta ->rowCount() > 0){//si existe el campo con ese correo pongo el flag a true
                        $_SESSION['checkMail'] = true;
                        $_SESSION['email'] = $email;
                    }else{//si no existe el campo recojo error y redirijo a login
                        $erroresMail[] = "Email no es válido";
                        header('refresh: 3, url: ../login.php');
                    }
                }
            }else{//si está vacío creo error
                $erroresMail[] = "Es necesario que ingrese su correo.";
            }
        }
        

        if($_SESSION['checkMail'] == true){//Entro si el mail ha sido verificado anteriormente
            $passwordForm = obtenerValorCampo('password');//obtengo el valor de la contraseña del formulario
            $password1 = obtenerValorCampo('password1');
            if(!empty($passwordForm && $password1 === $passwordForm)){
                $password = $passwordForm;
                echo $password;
            }else{//error contraseña vacía.
                $erroresPass[] = 'Debe introducir su contraseña y los dos campos deben coincidir';
            }

            if (count($erroresPass) === 0) {
                /* Registro En La Base De Datos */
                // Prepara INSERT
                
                $token = bin2hex(openssl_random_pseudo_bytes(16));

                $conexion = conectarPDO($host, $user, $password, $bbdd);
                $consulta = $conexion->prepare("UPDATE 
                                                usuarios 
                                                SET password = ?, token = ?, activo = ? 
                                                WHERE email = ?"
                                                );
                $consulta->bindParam(1, $password);
                $consulta->bindParam(2, $token);
                $consulta->bindParam(3, 0);
                $consulta->bindParam(4, $_SESSION['email']);
                $consulta->execute();

                if($consulta -> rowCount()>0) {
                    $headers = [
                        "From" => "dwes@php.com",
                        "Content-type" => "text/plain; charset=utf-8"
                    ];
                    // Variables para el email
                    $emailEncode = urlencode($email);
                    $tokenEncode = urlencode($token);
                    // Texto del email
                    $textoEmail = "
                    Hola!\n 
                    Gracias gracias por actualizar su contraseña.\n
                    Para activar entra en el siguiente enlace:\n
                    http://localhost:3000/DWS/bbdd_rol_usuario/acceso/verificar-cuenta.php?email=$emailEncode&token=$tokenEncode
                        ";
                    // Envio del email
                    mail($email, 'Activa tu cuenta', $textoEmail, $headers);
                    /* Redirección a login.php con GET para informar del envío del email */
                    header('Location: ../acceso/identificarse.php?registrado=1');
                    session_destroy();
                    exit();
                } else {
                    echo "Ha ocurrido un error";
                    header('refresh: 2, url: ../login');
                    session_destroy();
                    exit();
                }
                
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
   
                
    <?php //endif; ?>

    <?php if($_SESSION['email']): ?>
        <?php if (count($erroresPass)>0): ?>
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
        <form method="post" action="">
            <p>
                <label>
                    Contraseña
                    
                    <input type="password" name="password1">
                </label>
            </p>
            <p>
                <label>
                    Confirme contraseña
                    
                    <input type="password" name="password">
                </label>
            </p>
        
            <p>
                <!-- Botón submit -->
                <input type="submit" value="Actualizar contraseña">
            </p>
        </form>
    <?php else: ?>
        <!-- Mostramos errores por HTML -->
        <?php if (isset($errores)): ?>
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
            <form action="" method="post">
                <p>
                    <!-- Campo de Email -->
                    <label>
                        Correo electrónico
                        <input type="text" name="email">
                    </label>
                </p>
                <input type="submit" value="Enviar">
            </form>
    <?php endif; ?>
</body>
</html>