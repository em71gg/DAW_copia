<?php
session_name('reset');
session_start();
if (!isset($_SESSION['email'])) header('refresh:3, url=../login.php'); //si llego sin la variable de sesion vuelvo a login
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');
echo $_SESSION['email'];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    $pass1 = obtenerValorCampo('pass1');
    $pass2 = obtenerValorCampo('pass2');
    if (empty($pass1) || empty($pass2)) { //si las contraseñasno coinciden
        $errores['faltaPass'] = "Debe introducir y confirmar la contraseña.";
    } elseif ($pass1 != $pass2) { //si todo ok con las contraseñas envío correo con link que al confirmase hará el update en la base de datos
        $errores['passDiscrepan'] = "Las contraseñas deben coincidir.";
    }
    if (count($errores) == 0) {
        $_SESSION['password'] = $pass2;
        //echo"<p>Ok las contraseñas</p>";
        //echo "<p>Valor actual email: $email</p>";
        //creo un nuevo token
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $timeStamp = time(); // Hora actual en segundos
        //$timeToken = "$token|$timeStamp";

        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $insertToken = $conexion->prepare('UPDATE usuarios SET token=?, activo="0" WHERE email =?');
        $insertToken->bindParam(1, $token);
        $insertToken->bindParam(2, $_SESSION['email']);
        $insertToken->execute();
        

        if ($insertToken->rowCount() > 0) { //se actualizó el token con éxito enviamos correo
            /* Envío De Email Con Token */
            // Cabecera
            $headers = [
                "From" => "dwes@php.com",
                "Content-type" => "text/plain; charset=utf-8"
            ];
            // Variables para el email
            //$passEncode = urlencode($pass2);
            $tokenEncode = urlencode($token);
            $timeEncode = urlEncode($timeStamp);
            // Texto del email
            $textoEmail = "
                    Hola!\n 
                    Active su nueva contraseña pulsando en el siguiente enlace:\n
                    http://localhost:3000/DWS/agenciaViajes/restablecer/linkConfirmacion.php?token=$tokenEncode&time=$timeEncode&registrado=1 \n
                    El enlace tiene una duración de 5 minutos.
                    ";
            // Envio del email
            mail($_SESSION['email'], 'Activa tu cuenta', $textoEmail, $headers);
            /* Redirección a login.php con GET para informar del envío del email */
            header('Location: linkconfirmacion.php');
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
                        Introduzca su nueva contraseña
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
                        <input type="hidden" name="flag" value="1">
                        <div class="grid gap-y-4">
                            <div>
                                <label
                                    for="password1"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Introduzca contraseña</label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password1"
                                        name="pass1"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        aria-describedby="email-error"
                                         />
                                </div>
                            </div>
                            <div>
                                <label
                                    for="password2"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Confirme contraseña</label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password2"
                                        name="pass2"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        aria-describedby="email-error"
                                        />
                                </div>
                            </div>
                            <div class="text-center">
                                <?php if (!empty($errores['faltaPass'])): ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['faltaPass']; ?></p>
                                <?php endif; ?>

                                <?php if (!empty($errores['passDiscrepan'])): ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['passDiscrepan']; ?></p>
                                <?php endif; ?>
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
    </main>
</body>

</html>