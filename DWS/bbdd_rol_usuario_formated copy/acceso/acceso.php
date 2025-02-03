<?php
require_once("../utiles/variables.php");
require_once("../utiles/funciones.php");
$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = obtenerValorCampo('email');
    $contrasenaFormulario = isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : null;

    if (empty($email) || empty($contrasenaFormulario)) {
        $errores['errorVacios'] = "El email y la contraseña son obligatorios.";
        header("refresh:3;url=../login.php");
    } else {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        try {
            $consulta = $conexion->prepare("SELECT password, rol_id FROM usuarios WHERE email = ?");
            $consulta->bindParam(1, $email);
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                $row = $consulta->fetch();
                $contrasena = $row['password'];
                $rol = $row['rol_id'];
            } else {
                $errores['errorIncorrectos'] = "El email o la contraseña no son correctos.";
                header("refresh:3;url=../login.php");
            }
        } catch (PDOException $e) {
            $errores['errorBBDD'] = "Error en la base de datos: " . $e->getMessage();
            header("refresh:3;url=../login.php");
        } finally {
            desconectarPDO($consulta, $conexion);
        }

        if (!isset($errores['error']) && !empty($contrasena) && password_verify($contrasenaFormulario, $contrasena)) {
            session_name("sesion-privada");
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['rol_id'] = $rol;
            header('Location: index.php');
            exit();
        } else {
            $errores['errorValidacion'] = "El email o la contraseña no son correctos.";
            header("refresh:3;url=../login.php");
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
