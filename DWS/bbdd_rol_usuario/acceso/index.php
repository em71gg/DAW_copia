<?php
    // Activa las sesiones
    session_name("sesion-privada");
    session_start();
    // Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
    if (!isset($_SESSION["email"])) header("Location: ../login.php");
    $rol= $_SESSION['rol_id'];
    echo $rol;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            
            <a href="cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
    <h1>Listados</h1>
    <div class="contenedor">
        <div class="enlaces">
            <!-- Poner enlace a listado de sedes -->
             <a href="../sedes/listado.php">Listado de sedes</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a listado de departamentos -->
            <a href="../departamentos/listado.php">Listado de departamento</a>
            
        </div>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleados -->
            <a href="../empleados/listado.php">Listado de empleados</a>
        </div>
<?php
    if ($rol == 1):
?>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleados -->
            <a href="../usuarios/listado.php">Listado de usuarios</a>
        </div>
<?php
    endif;
?>
    </div>

    <hr>
    
    
</body>
</html>

</html>