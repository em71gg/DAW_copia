<?php
    session_name('reset');
    session_start();

    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');
    $errores = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(!isset($_SESSION['email'])) header('refresh:3, url=../login.php');//si llego sin la variable de sesion vuelvo a login
      
            $pass1 = obtenerValorCampo('pass1');
            $pass2 = obtenerValorCampo('pass2');
            if (empty($pass1) || empty($pass2)) {//si las contraseñasno coinciden
                $errores['faltaPass'] = "debe introducir y confirmar la contraseña.";
            }
            if($pass1 != $pass2){//si todo ok con las contraseñas envío correo con link que al confirmase hará el update en la base de datos
                $errores['passDiscrepan'] = "Las contraseñas deben coincidir.";
            }
            if(count($errores) == 0){
                $_SESSION['pass'] = $pass2;
                echo"<p>Ok las contraseñas</p>";
                echo "<p>Valor actual email: $email</p>";
                //creo un nuevo token
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                $insertToken = $conexion -> prepare ('UPDATE usuarios SET token=?, activo="0", fecha=CURRENT_TIMESTAMP WHERE email =?');
                $insertToken -> bindParam(1, $token);
                $insertToken -> bindParam(2, $_SESSION['email']);
                $insertToken -> execute();
                desconectarPDO($insertToken, $conexion);
                
                if ($insertToken -> rowCount()>0) {//se actualizó el token con éxito enviamos correo
                    /* Envío De Email Con Token */
                    // Cabecera
                    $headers = [
                        "From" => "dwes@php.com",
                        "Content-type" => "text/plain; charset=utf-8"
                    ];
                    // Variables para el email
                    $passEncode = urlencode($pass2);
                    $tokenEncode = urlencode($token);
                    // Texto del email
                    $textoEmail = "
                    Hola!\n 
                    Active su nueva contraseña pulsando en el siguiente enlace:\n
                    http://localhost:3000/DWS/bbdd_rol_usuario/restablecer/linkConfirmación.php?password=$passEncode&token=$tokenEncode&registrado=1
                    ";
                    // Envio del email
                    mail($_SESSION['email'], 'Activa tu cuenta', $textoEmail, $headers);
                    /* Redirección a login.php con GET para informar del envío del email */
                    header('Location: linkconfirmación.php');
                    exit();

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
    <title>Entrada</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
    


            <form action="#" method="post">
                <input type="hidden" name="flag" value="1">
                <p>
                <label for="password1">Introduzca Contraseña: </label>
                <input type="password" name="pass1" id="password1">
                </p>   
                <p>
                <label for="password2">Confirme Contraseña: </label>
                <input type="password"  name="pass2" id="password2">
                </p>    
                <?php if (!empty($errores['faltaPass'])): ?>
                <p class="errores"><?php echo $errores['faltaPass']; ?></p>
                <?php endif; ?>
                
                <?php if (!empty($errores['passDiscrepan'])): ?>
                    <p class="errores"><?php echo $errores['passDiscrepan']; ?></p>
                <?php endif; ?>
                    <p><input type="submit" value="enviar"></p>
                </form>

</body>

</html>