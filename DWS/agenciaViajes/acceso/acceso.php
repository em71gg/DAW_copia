<?php

/**
 * En esta página se recogen los datos enviados por post desde "./login.php"
 * se sanitizan con la fución obtenerValorcampo() 
 * 
 */
require_once("../utiles/variables.php");
require_once("../utiles/funciones.php");

$errores = [];
$perfilId = null;
$usuarioId = null;
$consultaUsuarios = null; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = obtenerValorCampo('email');
    $contrasenaFormulario = obtenerValorCampo('contrasena');

    if (empty($email) || empty($contrasenaFormulario)) {
        //Si no se han recibido correctamente los campos se lanza un error y se devuelve a ./login.php
        $errores['errorVacios'] = "El email y la contraseña son obligatorios.";
        header("refresh:3;url=./login.php");

    } else {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        /*Si se han recibido los campos email y contrasena se procede en primer lugar a comprobar que el email está rgistrado 
        En la base de datos*/

        try {//En primer lugar se consulta la tabla de usuarios ya que se esperan más entradas de ellos
            $consultaUsuarios = $conexion->prepare("SELECT password, id, perfil_id, email FROM usuarios WHERE email = ?");
            $consultaUsuarios->bindParam(1, $email);
            $consultaUsuarios->execute();

            if ($consultaUsuarios->rowCount() > 0) {//Si la consulta devuelve un resultado se recogen las variables necesarias
                $row = $consultaUsuarios->fetch();
                $contrasena = $row['password'];
                $perfilId = $row['perfil_id'];
                $usuarioId = $row['id'];
                $email = $row['email'];
            }
            else{//Si no se encuentra el email en la tabla usuarios se consulta la tabla gestores
                $consultaGestores = $conexion -> prepare ("SELECT password, id, perfil_id, email FROM gestores WHERE email = ?");
                $consultaGestores -> bindParam(1, $email);
                $consultaGestores -> execute();
                if ($consultaGestores -> rowCount() >0){//Si la consulta devileve un resultado se recogen las variables necesarias
                    $row = $consultaGestores -> fetch();
                    $contrasena = $row['password'];
                    $perfilId = $row['perfil_id'];
                    $usuarioId = $row['id'];
                    $email = $row['email'];
                }
            }           
            if($usuarioId == null || $perfilId == null) {//Se comprueba que una de las dos consultas obtuvieron resultados, 
                                                        //si no es así se lanza el error y se redirige a "login.php"
                $errores['errorIncorrectos'] = "El email o la contraseña no son correctos.";
                header("refresh:3;url=./login.php");
            }
        } catch (PDOException $e) {//Si esixten errores en la aqcción del try se recogen para debug
            $errores['errorBBDD'] = "Error en la base de datos: " . $e->getMessage();
            header("refresh:3;url=./login.php");

        } finally {//finalmente se desconecta  
            desconectarPDO($consultausuarios, $conexion);
            desconectarPDO($consultaGestores, $conexion);    
        }

        //Si no hay errores generados durante el proceso , y hay un match entre contraseña y email se crea la sesion,
        // las variables de sesion y se dirige a la página "../areaPersonal/areaPersonal.php"
        if (!isset($errores['error']) && !empty($contrasena) && password_verify($contrasenaFormulario, $contrasena)) { 
            session_name("sesion-privada");
            session_start();
            $_SESSION['id'] = $usuarioId;
            $_SESSION['perfil_id'] = $perfilId;
            $_SESSION['email'] = $email;
            header('Location: ../areaPersonal/areaPersonal.php');
    
        } else {
            $errores['errorIncorrectos'] = "El email o la contraseña no son correctos.";
           // $errores['errorValidacion'] = "El email o la contraseña no son correctos Validacion."; 
           //No me interesa dar un error diferente si no hay un match con la contraseña
            header("refresh:3;url=./login.php");
    
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body>
    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <?php if (!empty($errores['errorVacios'])): ?>
                        <p class="mt-2 text-sm font-bold text-red-600 dark:text-red-400"><?php echo $errores['errorVacios']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($errores['errorIncorrectos'])): ?>
                        <p class="mt-2 text-sm font-bold text-red-600 dark:text-red-400"><?php echo $errores['errorIncorrectos']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($errores['errorBBDD'])): ?>
                        <p class="mt-2 text-sm font-bold text-red-600 dark:text-red-400"><?php echo $errores['errorBBDD']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($errores['errorValidacion'])): ?>
                        <p class="mt-2 text-sm font-bold text-red-600 dark:text-red-400"><?php echo $errores['errorValidacion']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
