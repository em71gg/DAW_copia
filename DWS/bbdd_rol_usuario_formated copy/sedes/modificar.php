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
    $nombre = '';
    $direccion = '';
    $idSede = null;
    $LongitudMinNombre = 3;
    $longitudMaxNombre = 50;
    $longitudMinDireccion = 10;
    $longitudMaxDireccion = 255;

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idSede'])) {
        $idSede = $_GET['idSede'];

        // Validar si la sede existe
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $consulta = $conexion->prepare("SELECT * FROM sedes WHERE id = ?");
        $consulta->bindParam(1, $idSede);
        $consulta->execute();

        if ($consulta->rowCount() === 0) {
            // Redirigir si no existe la sede
            header('location: listado.php');
            exit();
        } else {
            // Cargar datos existentes
            $registro = $consulta->fetch(PDO::FETCH_ASSOC);
            $nombre = $registro['nombre'];
            $direccion = $registro['direccion'];
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        // Solo procesar POST si hay un id válido
        $idSede = $_POST['id'];
        $nombreActualizado = obtenerValorCampo('nombre');
        $direccionActualizada = obtenerValorCampo('direccion');

        // Validar nombre
        if ($nombreActualizado === "" || !validarLongitudCadena($nombreActualizado, $LongitudMinNombre, $longitudMaxNombre)) {
            $errores['nombre'] = 'El nombre debe tener entre 3 y 50 caracteres.';
        } else {
            $nombre = $nombreActualizado;
        }

        // Validar dirección
        if ($direccionActualizada === "" || !validarLongitudCadena($direccionActualizada, $longitudMinDireccion, $longitudMaxDireccion)) {
            $errores['direccion'] = 'La dirección debe tener entre 10 y 255 caracteres.';
        } else {
            $direccion = $direccionActualizada;
        }

        // Si no hay errores, actualizar la base de datos
        if (count($errores) === 0) {
            $conexion = conectarPDO($host, $user, $password, $bbdd);
            $consulta = $conexion->prepare("UPDATE sedes SET nombre = ?, direccion = ? WHERE id = ?");
            $consulta->bindParam(1, $nombre);
            $consulta->bindParam(2, $direccion);
            $consulta->bindParam(3, $idSede);
            $consulta->execute();

            // Redirigir al listado tras actualizar
            header('Location: listado.php');
            exit();
        }
    } else {
        // Si no es GET válido ni POST válido, redirigir
        header('location: listado.php');
        exit();
    }
    ?>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($idSede) ?>">
        <p>
            <input type="text" name="nombre" placeholder="Sede" value="<?= htmlspecialchars($nombre) ?>">
            <?php if (isset($errores['nombre'])): ?>
                <p class="error"><?= $errores['nombre'] ?></p>
            <?php endif; ?>
        </p>
        <p>
            <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($direccion) ?>">
            <?php if (isset($errores['direccion'])): ?>
                <p class="error"><?= $errores['direccion'] ?></p>
            <?php endif; ?>
        </p>
        <p>
            <input type="submit" value="Guardar">
        </p>
    </form>
    <div class="contenedor">
        <div class="enlaces">
            <a href="listado.php">Volver al listado de sedes</a>
        </div>
    </div>
</body>
</html>
