<?php
    session_name('reset');
    session_start();

    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');

    isset($_SESSION['flag']) ? $flag = $_SESSION['flag'] : $flag = 0;//actualizo o creo el valor de flag para usarlo en la lógica
    isset($_SESSION['email']) ? $email = $_SESSION['email'] : $email = "";//actualizo o creo el valor de email
    isset($_SESSION['errores']) ? $_SESSION['errores'] = [] : null;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $_SESSION['pass1'] = obtenerValorCampo('pass1');
        $_SESSION['pass2'] = obtenerValorCampo('pass2');
        $pass1  = $_SESSION['pass1'];
        $pass2 = $_SESSION['pass2'];
       
        if (empty($pass1) || empty($pass2)) {//si las contraseñasno coinciden
            $_SERVER['errores']['faltaPass'] = "debe introducir y confirmar la contraseña.";
        }
        if($pass1 != $pass2){
            $_SERVER['errores']['passDiscrepan'] = "Las contraseñas deben coincidir.";
        }

        if (count($_SESSION['errores']) ==0){
            echo"<p>Ok las contraseñas</p>";
            echo "<p>Valor actual email: $email</p>";
            //creo un nuevo token
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $conexion = conectarPDO($host, $user, $password, $bbdd);
            $insertToken = $conexion -> prepare ('UPDATE usuarios SET token=?, fecha=CURRENT_TIMESTAMP WHERE email =?');
            $insertToken -> bindParam(1, $token);
            $insertToken -> bindParam(2, $email);
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
                http://localhost:3000/DWS/bbdd_rol_usuario/restablecer/confirmarPassword.php?email=$passEncode&token=$tokenEncode
                ";
                // Envio del email
                mail($email, 'Activa tu cuenta', $textoEmail, $headers);
                /* Redirección a login.php con GET para informar del envío del email */
                header('Location: identificarse.php?registrado=1');
                exit();
            }else{echo "<p>Se ha producido un error al actulzar el token</p>";}
        }
        else{}
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
    
            <?php
            echo "<h1>Valor flag = $flag</h1>";
            echo "<h1>Valor emial = $email</h1>";
            
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <input type="hidden" name="flag" value="1">
                <p>
                <label for="password1">Introduzca Contraseña: </label>
                <input type="password" name="pass1" id="password1">
                </p>   
                <p>
                <label for="password2">Condirme Contraseña: </label>
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
        
    
   
    <?php
    ?>
</body>

</html>

?>