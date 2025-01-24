<?php
    // Activa las sesiones
    session_name("sesion-privada");
    session_start();
    // Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
    if (!isset($_SESSION["email"])) header("Location: ../login.php");
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar una sede</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            
            <a href="../cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
    <h1>Modificar una sede</h1>
    <?php
    $errores = [];
    $emailUsuario = null;
    $validRoles = [1,2,3];

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['emailUsuario'])) {
        $emailUsuario = $_GET['emailUsuario'];

        // Validar si la sede existe
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $consulta->bindParam(1, $emailUsuario);
        $consulta->execute();

        if ($consulta->rowCount() === 0) {
            // Redirigir si no existe la sede
            header('location: listado.php');
            exit();
        } else {
            // Cargar datos existentes
            $registro = $consulta->fetch(PDO::FETCH_ASSOC);
            $email = $registro['email'];
            $rol_id = $registro['rol_id'];
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
        // Solo procesar POST si hay un id válido
        //$emailUsuario = $_POST['email'];
        $emailActualizado = obtenerValorCampo('email');
        $rolActualizado = obtenerValorCampo('rol');

        // Validar nombre
        if ($emailActualizado === "" || !validarEmail($emailActualizado)) {
            $errores['email'] = 'El el email debe ser válido.';
        } else {
            $email = $emailActualizado;
        }

        // Validar dirección
        if ($rolActualizado === "" || !validarEnteroPositivo($rolActualizado) || !in_array($rolActualizado, $validRoles)) {
            $errores['rol'] = 'Debe introducir un rol definido como 1, 2 o 3.';
        } else {
            $rol = $rolActualizado;
        }

        // Si no hay errores, actualizar la base de datos
        if (count($errores) === 0) {
            $conexion = conectarPDO($host, $user, $password, $bbdd);
            $consulta = $conexion->prepare("UPDATE usuarios SET email = ?, rol_id = ? WHERE email = ?");
            $consulta->bindParam(1, $email);
            $consulta->bindParam(2, $rol);
            $consulta->bindParam(3, $email);
            $consulta->execute();

            // Redirigir al listado tras actualizar
            header('Location: listado.php');
            exit();
        }
    } else {
        //echo $_GET['email'];
        // Si no es GET válido ni POST válido, redirigir
        header('location: listado.php');
        exit();
    }
    ?>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <input type="hidden" name="email" value="<?= htmlspecialchars($emailUsuario) ?>">
        <p>
            <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errores['email'])): ?>
                <p class="error"><?= $errores['email'] ?></p>
            <?php endif; ?>
        </p>
        <p>
            <input type="text" name="rol" placeholder="Rol Usuario" value="<?= htmlspecialchars($rol_id) ?>">
            <?php if (isset($errores['rol'])): ?>
                <p class="error"><?= $errores['rol'] ?></p>
            <?php endif; ?>
        </p>
        <p>
            <input type="submit" value="Guardar">
        </p>
    </form>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de usuarios</a>
        </div>
    </div>
</body>
</html>
