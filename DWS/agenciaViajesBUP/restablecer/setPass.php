<?php
session_name('reset');
session_start();
if (!isset($_SESSION['email'])) {
    header('refresh:3; url=../login.php'); // Si llego sin la variable de sesión, vuelvo a login
    exit();
}

require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

// Depuración: Mostrar el email almacenado en la sesión
echo "Email de la sesión: {$_SESSION['email']}<br>";

$errores = [];
$tipoUsuario = null; // Para determinar si el email pertenece a 'usuarios' o 'gestores'

try {
    // Determinar si el email está en 'usuarios' o 'gestores'
    $conexion = conectarPDO($host, $user, $password, $bbdd);
    if (!$conexion) {
        $errores['conexion'] = "Error al conectar a la base de datos.";
    } else {
        // Primero buscamos en 'usuarios'
        $consultaUsuarios = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $consultaUsuarios->bindParam(1, $_SESSION['email']);
        $consultaUsuarios->execute();

        if ($consultaUsuarios->rowCount() > 0) {
            $tipoUsuario = 'usuarios';
        }else {
                $errores['noUsuario'] = "El email {$_SESSION['email']} no está registrado en ninguna tabla.";
            }
        
    }
} catch (PDOException $e) {
    $errores['sql'] = "Error en la consulta SQL: " . $e->getMessage();
} finally {
    if (isset($consultaUsuarios)) {
        $consultaUsuarios = null;
    }
    if (isset($consultaGestores)) {
        $consultaGestores = null;
    }
    if (isset($conexion)) {
        $conexion = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errores)) {
    $pass1 = obtenerValorCampo('pass1');
    $pass2 = obtenerValorCampo('pass2');
    if (empty($pass1) || empty($pass2)) {
        $errores['faltaPass'] = "Debe introducir y confirmar la contraseña.";
    } elseif ($pass1 != $pass2) {
        $errores['passDiscrepan'] = "Las contraseñas deben coincidir.";
    }

    if (count($errores) == 0) {
        $_SESSION['password'] = $pass2;
        // Creo un nuevo token
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $timeStamp = time();

        try {
          
            echo "Parámetros de conexión: Host=$host, User=$user, BBDD=$bbdd<br>";

            $conexion = conectarPDO($host, $user, $password, $bbdd);
            if (!$conexion) {
                $errores['conexion'] = "Error al conectar a la base de datos.";
            } else {
                // Actualizar la tabla correspondiente según $tipoUsuario
                $tabla = $tipoUsuario == 'usuarios' ? 'usuarios' : 'gestores';
                $insertToken = $conexion->prepare("UPDATE $tabla SET token=?, activo='0', updated_at=CURRENT_TIMESTAMP WHERE email =?");
                if (!$insertToken) {
                    $errores['prepare'] = "Error al preparar la consulta.";
                } else {
                    $insertToken->bindParam(1, $token);
                    $insertToken->bindParam(2, $_SESSION['email']);
                    $success = $insertToken->execute();

                    
                    if (!$success) {
                        $errores['execute'] = "Error al ejecutar la consulta.";
                    } else {
                        
                        $rowCount = $insertToken->rowCount();
                        echo "Filas afectadas: $rowCount<br>";

                        if ($rowCount > 0) {
                            // Se actualizó el token con éxito, enviamos correo
                            $headers = [
                                "From" => "dwes@php.com",
                                "Content-type" => "text/plain; charset=utf-8"
                            ];
                            $tokenEncode = urlencode($token);
                            $timeEncode = urlencode($timeStamp);
                            $textoEmail = "
                                Hola!\n 
                                Active su nueva contraseña pulsando en el siguiente enlace:\n
                                http://localhost:3000/DWS/agenciaViajes/restablecer/linkConfirmacion.php?token=$tokenEncode&time=$timeEncode&registrado=1 \n
                                El enlace tiene una duración de 5 minutos.
                            ";
                            mail($_SESSION['email'], 'Activa tu cuenta', $textoEmail, $headers);

                            // Depuración: Verificar si el archivo existe antes de redirigir
                            $redirectFile = 'linkConfirmacion.php';
                            if (file_exists($redirectFile)) {
                                header('Location: ' . $redirectFile);
                                exit();
                            } else {
                                $errores['redirect'] = "El archivo $redirectFile no se encuentra.";
                            }
                        } else {
                            $errores['update'] = "No se pudo actualizar el usuario con el email: {$_SESSION['email']}";
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            $errores['sql'] = "Error en la consulta SQL: " . $e->getMessage();
        } finally {
            if (isset($insertToken)) {
                $insertToken = null;
            }
            if (isset($conexion)) {
                $conexion = null;
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
    <title>Entrada</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Introduzca su nueva contraseña
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Recuerda su contraseña?
                        <a class="text-blue-600 decoration-2 hover:underline font-medium" href="../acceso/login.php">
                            Ir a login
                        </a>
                    </p>
                </div>
                <div class="mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <input type="hidden" name="flag" value="1">
                        <div class="grid gap-y-4">
                            <div>
                                <label for="password1" class="block text-sm font-bold ml-1 mb-2 dark:text-white">Introduzca contraseña</label>
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
                                <label for="password2" class="block text-sm font-bold ml-1 mb-2 dark:text-white">Confirme contraseña</label>
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
                                <?php if (!empty($errores)): ?>
                                    <?php foreach ($errores as $error): ?>
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
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