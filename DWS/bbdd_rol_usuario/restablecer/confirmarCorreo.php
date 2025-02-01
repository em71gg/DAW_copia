<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

$errores = [];
$email = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = obtenerValorCampo('email');
    if (!empty($email)) {
        if (validarEmail($email)) { //si el email es válio se comprueba si está registrado

            $conexion = conectarPDO($host, $user, $password, $bbdd);
            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email=?");
            $consulta->bindParam(1, $email);
            $consulta->execute();
            desconectarPDO($consulta, $conexion);

            if ($consulta->rowCount() > 0) { //Si el email existe en la bbdd llevo  creo sesion y llevo a introducir contraseña
                echo "$email existe en la base de datos";
                echo "Valor email tras crear la sesion: $email";

                session_name('restart');//creo la sesion y la variable email.
                session_start();
                $_SESSION['email'] = $email;
                header('location:setPass.php');
                exit();
            } else { //Si no existe el correo en la bbdd llevo a login
                echo "<p>No existe como usuario</p>";
                header('refresh:3; url=../login.php');
            }
        } else { //Si el email no es válido se pasa el error
            $errores['errorEmail'] = "Debe introducir un email válido";
        }
    } else { //si el campo del correo viene vacío lo pido
        $errores['emailVacio'] = "Debe introducir un email";
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
        <label for="email">Introduce Email: </label>
        <input type="text" placeholder="<?php echo ($email != "" ? $email : "Email") ?>" name="email" id="email">
        <?php

        if (!empty($errores['emailVacio'])):
        ?>
            <p class="errores"><?php echo $errores['emailVacio'] ?></p>
        <?php
        endif;
        ?>
        <?php
        if (!empty($errores['errorEmail'])):
        ?>
            <p class="errores"><?php echo $errores['errorEmail'] ?></p>
        <?php
        endif;
        ?>
        <p><input type="submit" value="enviar"></p>
    </form>

</body>

</html>