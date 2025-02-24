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

                session_name('reset'); //creo la sesion y la variable email.
                session_start();
                $_SESSION['email'] = $email;
                header('location:setPass.php');
                exit();
            } else { //Si no existe el correo en la bbdd llevo a login
                $errores['noUsuario'] = "No existe como usuario";
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
    <!--<link rel="stylesheet" href="../css/estilos.css">-->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>
    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        ¿Olvidó su contraseña?
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Recuerda su contraseña?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../acceso/login.php">
                            Ir a login
                        </a>
                    </p>

                </div>
                <div class="mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <div class="grid gap-y-4">
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Dirección email</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="email"
                                        name="email"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        aria-describedby="email-error"
                                        placeholder="<?php echo ($email != "" ? $email : "Email") ?>" />
                                </div>

                            </div>
                            <div class="text-center">
                                <?php
                                if (!empty($errores['noUsuario'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['noUsuario'] ?></p>
                                <?php
                                endif;
                                ?>
                                <?php

                                if (!empty($errores['emailVacio'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['emailVacio'] ?></p>
                                <?php
                                endif;
                                ?>
                                <?php
                                if (!empty($errores['errorEmail'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['errorEmail'] ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                            <button
                                type="submit"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                                Reset password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-left">
                    <ul class="list-disc">
                        <li>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Se confirma el correo, si está registrado se ingresa la nueva contraseña.</p>
                        </li>
                       
                        <li>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Se valida la contraseña, se genera un token y se inactiva al usuario.</p>
                        </li>
                        <li>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">El usuario recibe un correo con un link de confirmación.</p>
                        </li>
                        <li>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Tendrá 5 minutos para usarlo.</p>
                        </li>
                        <li>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Al pulsar el link de onfirmación cambia la contraseña, se activa el usuario y se borra el token </p>
                        </li>
                    </ul>

                </div>

            </div>

        </div>
    </main>


</body>

</html>