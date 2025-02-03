<?php
session_name('reset');
session_start();

require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

if (!isset($_SESSION['email'])) header('location: ../login.php');
if (!isset($_SESSION['password'])) header('location: ../login.php');
$flag = null;
$errores = [];
if (count($_REQUEST) > 0) {

    if ($_GET['registrado'] == 1) { //insertar en bbdd la contraseña, activo =1

        if (isset($_GET['token']) && isset($_GET['time'])) {
            $timeMail = $_GET['time'];
            $ahora = time();
            $diferencia = $ahora - $timeMail;

            if ($diferencia > 300) { //si pasan 5 minutos no dejo que se restablezca
                $flag = 2;
                session_destroy();
                header('refresh:3, url=../login.php');
            } else {
                //$flag = 1;

                $passToCrypt = password_hash($_SESSION['password'], PASSWORD_BCRYPT);
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                $consulta = $conexion->prepare("UPDATE usuarios SET password=?, activo='1', token='' WHERE email=? AND token=?");
                $consulta->bindParam(1, $passToCrypt);
                $consulta->bindParam(2, $_SESSION['email']);
                $consulta->bindParam(3, $_GET['token']);
                $consulta->execute();

                if ($consulta->rowCount() > 0) { //consulta con éxito.
                    $flag = 1;
                    session_destroy();
                    header('refresh:3, url=../login.php');
                }
            }
        } else { //error en el link
            $errores['errorLink'] = "Se ha producido un error en la activación.";
            session_destroy();
            header('refresh:3, url=../login.php');
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
   <!-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script>-->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <?php
                    if ($flag == 1):
                    ?>
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-white">Actualización de contraseña realizada con éxito</h2>
                        <h3 class="block text-2xl font-bold text-gray-800 dark:text-white">Será redirigido a la pantalla de login</h3>

                        <?php
                        if (!empty($errores['errorLink'])):
                        ?>
                            <h2 class="block text-2xl font-bold text-gray-800 dark:text-white"><?php echo isset($errores['errorLink']) ? $errores['errorLink'] : "Error desconocido."; ?></h2>
                        <?php
                        endif;
                        ?>
                        <?php
                        //endif;
                        ?>
                    <?php
                    elseif ($flag == 2):
                    ?>
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-white">Enlace expirado. Solicite un nuevo restablecimiento de contraseña.</h2>
                    <?php
                    else:
                    ?>
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-white">Se le ha enviado un correo electrónico con un link de confirmación para activar su cuenta.</h2>
                    <?php
                    endif;
                    ?>
                </div>
            </div>

        </div>
    </main>

</body>

</html>