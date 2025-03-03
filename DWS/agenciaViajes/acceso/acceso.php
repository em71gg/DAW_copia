<?php
require_once("../utiles/variables.php");
require_once("../utiles/funciones.php");
$errores = [];
$perfilId = null;
$usuarioId = null;
//$consultaGestores = null; Est la voy a usar seguro
$consultaUsuarios = null; // lainicializo a null por si no se usa

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = obtenerValorCampo('email');
    $contrasenaFormulario = isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : null;

    if (empty($email) || empty($contrasenaFormulario)) {
        $errores['errorVacios'] = "El email y la contraseña son obligatorios.";
        header("refresh:3;url=./login.php");
        exit();
    } else {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        try {
            $consultaUsuarios = $conexion->prepare("SELECT password, id, perfil_id, email FROM usuarios WHERE email = ?");
            $consultaUsuarios->bindParam(1, $email);
            $consultaUsuarios->execute();

            if ($consultaUsuarios->rowCount() > 0) {
                $row = $consultaUsuarios->fetch();
                $contrasena = $row['password'];
                $perfilId = $row['perfil_id'];
                $usuarioId = $row['id'];
                $email = $row['email'];
            }
            else{

                $consultaGestores = $conexion -> prepare ("SELECT password, id, perfil_id, email FROM gestores WHERE email = ?");
                $consultaGestores -> bindParam(1, $email);
                $consultaGestores -> execute();

                if ($consultaGestores -> rowCount() >0){
                    $row = $consultaGestores -> fetch();
                    $contrasena = $row['password'];
                    $perfilId = $row['perfil_id'];
                    $usuarioId = $row['id'];
                    $email = $row['email'];
                }
            }           
            if($usuarioId == null || $perfilId == null) {
                $errores['errorIncorrectos'] = "El email o la contraseña no son correctos.";
                header("refresh:3;url=./login.php");
                exit();
            }
        } catch (PDOException $e) {
            $errores['errorBBDD'] = "Error en la base de datos: " . $e->getMessage();
            header("refresh:3;url=./login.php");
        } finally {
            desconectarPDO($consultausuarios, $conexion);
            desconectarPDO($consultaGestores, $conexion);
            
        }

        if (!isset($errores['error']) && !empty($contrasena) && password_verify($contrasenaFormulario, $contrasena)) {
            session_name("sesion-privada");
            session_start();
            $_SESSION['id'] = $usuarioId;
            $_SESSION['perfil_id'] = $perfilId;
            $_SESSION['email'] = $email;
            header('Location: ../areaPersonal/areaPersonal.php');
            exit();
        } else {
            $errores['errorValidacion'] = "El email o la contraseña no son correctos.";
            header("refresh:3;url=./login.php");
            exit();
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
