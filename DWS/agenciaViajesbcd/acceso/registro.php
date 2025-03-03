<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

//session_name("sesion-privada");
//session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
//if (!isset($_SESSION["id"])) header("Location: ../inicio.php");

$errores = [];
$nombre = null;
$perfil = null;
$email = null;
$fecha = null;
$contrasenna = null;
$salida = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = obtenerValorCampo('nombre');
    $perfil = obtenerValorCampo('perfil');
    $email = obtenerValorCampo('email');
    $contrasenna = obtenerValorCampo('contrasenna');

    if (empty($nombre)) $errores['faltaNombre'] = "Debe dar un nombre al empleado";
    if (strlen($nombre) > 30) $errores['nombreLargo'] = "El nombre no debe sobrepasar los 30 caracteres";
    if(empty($perfil)) $errores['faltaPerfil'] = "Debe seleccionar un perfil";
    if (empty($email)) $errores['faltaEmail'] = "Debe dar un email";
    if (!validarEmail($email)) $errores['emailIncorrecto'] = "Debe introducir un email válido";
    if (empty($contrasenna)) $errores['faltapassword'] = 'Debe indicar la contraseña';
    /* Verificar que no existe en la base de datos el mismo email */
    // Conecta con base de datos
    $conexion = conectarPDO($host, $user, $password, $bbdd);
    // Cuenta cuantos emails existen
    $select = "SELECT COUNT(*) as numero FROM usuarios WHERE email = :email";
    $consulta = $conexion->prepare($select);
    // Ejecuta la búsqueda
    $consulta->execute([
        "email" => $email
    ]);
    // Recoge los resultados
    $resultado = $consulta->fetch();
    desconectarPDO($consulta, $conexion);
    // Comprueba si existe
    if ($resultado["numero"] > 0) {
        $errores['emailRegistrado'] = "La dirección de email ya esta registrada.";
    }

    if (count($errores) == 0) {
        $passwordHashed = password_hash($contrasenna, PASSWORD_BCRYPT);
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        try{
           
            $conexion = conectarPDO($host, $user, $password, $bbdd);
            $consulta = $conexion -> prepare('INSERT 
                                                INTO usuarios 
                                                ( 
                                                perfil_id,
                                                nombre, 
                                                email,
                                                password, 
                                                token,
                                                activo,
                                                created_at,
                                                updated_at ) 
                                                VALUES
                                                ( ?, ?, ?, ?, ?,
                                                0,
                                                CURRENT_TIMESTAMP, 
                                                CURRENT_TIMESTAMP)');
            $consulta -> bindParam(1, $perfil);
            $consulta -> bindParam(2, $nombre);
            $consulta -> bindParam(3,$email);
            $consulta -> bindParam(4, $passwordHashed);
            $consulta -> bindParam(5, $token);

            $consulta -> execute();

            $mensajeId = (int)$conexion -> lastInsertId();
            if($mensajeId >0) {
                
                $salida = "Empleado registrado";
                header('Refresh: 3; url=../areaPersonal/editarAdmin.php');

                /* Envío De Email Con Token */
                // Cabecera
                $headers = [
                    "From" => "agencia@php.com",
                    "Content-type" => "text/plain; charset=utf-8"
                ];
                // Variables para el email
                $emailEncode = urlencode($email);
                $tokenEncode = urlencode($token);
                // Texto del email
                $textoEmail = "
                    Hola!\n 
                    Gracias por registrate en la mejor plataforma de internet, demuestras inteligencia.\n
                    Para activar entra en el siguiente enlace:\n
                http://localhost:3000/DWS/agenciaViajes/acceso/verificar-cuenta.php?email=$emailEncode&token=$tokenEncode
                        ";
                // Envio del email
                mail($email, 'Activa tu cuenta', $textoEmail, $headers);
                /* Redirección a login.php con GET para informar del envío del email */
                header('Location: identificarse.php?registrado=1');
                exit();
            }
        }
        catch(PDOException $e){
            echo "Error al insertar el registro : " .$e -> getMessage();
        }
        finally{
            desconectarPDO($consulta, $conexion);
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
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>
    <header>
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-500 dark:from-gray-500 dark:via-gray-600 dark:to-gray-500 py-8 md:py-16">
            <div class="flex flex-row justify-between items-center px-6">
                <p class="text-3xl font-bold text-white text-center flex-grow text-center">
                    Agencia de Viajes
                </p>
                <div class="flex gap-2">
                    <a href="../acceso/cerrarSesion.php">
                        <button
                            type="submit"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-white font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                            Cerrar Sesion
                        </button>
                    </a>

                </div>
            </div>
            
        </div>
    </header>

    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Cumplimente los campos del registro
                    </h1>
                    <?php
                     if($salida != null):
                    ?>
                        <p class="mt-2 text-sm font-bold text-blue-600 "><?php echo $salida ?></p>
                    <?php
                     endif;
                    ?>
                    
                </div>
                <div class="mt-5">
                    <form action="<?php echo (htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
                        <div class="grid gap-y-4">
                            <div>
                                <label
                                    for="nombre"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Nombre 
                                </label>
                                <div class="relative">
                                    <input
                                        type="nombre"
                                        id="nombre"
                                        name="nombre"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"

                                        aria-describedby="email-error"
                                        placeholder="<?php echo ($nombre != "" ? $nombre : "Nombre ") ?>" />
                                </div>
                            </div>
                            <div class="text-center">
                                <?php
                                if (!empty($errores['faltaNombre'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['faltaNombre'] ?></p>
                                <?php
                                endif;
                                ?>
                                <?php

                                if (!empty($errores['nombreLargo'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['nombreLargo'] ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Email 
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="email"
                                        name="email"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        aria-describedby="email-error"
                                        placeholder="<?php echo ($email != "" ? $email : "Email") ?>">
                                    </textarea>
                                </div>
                            </div>
                            <div class="text-center">
                                <?php
                                if (!empty($errores['faltaEmail'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['faltaEmail'] ?></p>
                                <?php
                                endif;
                                ?>
                                <?php

                                if (!empty($errores['emailIncorrecto'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['emailIncorrecto'] ?></p>
                                <?php
                                endif;
                                ?>
                                <?php
                                if (!empty($errores['emailRgistrado'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['emailRegistrado'] ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div>
                                <label
                                    for="perfil"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Perfil de usuario</label>
                                <div class="relative">
                                    <select
                                        id="perfil"
                                        name="perfil"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        aria-describedby="email-error"
                                        >
                                        <option value="">Seleccione una perfil</option>
                                        <?php
                                            $conexion = conectarPDO($host, $user,$password, $bbdd);

                                            $perfils = ('SELECT
                                                            id,
                                                            perfil
                                                            FROM perfiles');
                                            $getperfil= resultadoConsulta($conexion, $perfils);

                                            while ($row = $getperfil -> fetch(PDO::FETCH_ASSOC) ):
                                                if ( $row['id'] == 1 || $row['id'] == 2) continue;
                                        ?>

                                        <option value="<?php echo $row['id'];?>" <?php echo $row["id"] == $perfil ? "selected" : ""?>><?php echo $row['perfil'] ?></option>
                                        <?php
                                            endwhile;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <?php
                                if (!empty($errores['faltaPerfil'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['faltaPerfil'] ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                          
                         
                            <div>
                                <label
                                    for="contrasenna"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Contraseña 
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="contrasenna"
                                        name="contrasenna"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        placeholder="Contraseña"
                                        aria-describedby="email-error"
                                        />
                                </div>
                            </div>
                            <div class="text-center">
                                <?php
                                if (!empty($errores['faltapassword'])):
                                ?>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo $errores['faltapassword'] ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div class="text-center">

                            </div>
                            <button
                                type="submit"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                                Enviar
                            </button>

                            <button
                                type="reset"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                                Borrar
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Volver a inicio
                            <a
                                class="text-blue-600 decoration-2 hover:underline font-medium"
                                href="../inicio.php">
                                Volver
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>