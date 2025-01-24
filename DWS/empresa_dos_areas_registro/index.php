<?php
    // Activa las sesiones
    session_name("sesion-privada");
    session_start();
    // Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
    //if (!isset($_SESSION["email"])) header("Location: login.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
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
             <a href="sedes/listado.php">Listado de sedes</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a listado de departamentos -->
            <a href="departamentos/listado.php">Listado de departamento</a>
            
        </div>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleados -->
            <a href="empleados/listado.php">Listado de empleados</a>
        </div>
    </div>
    <hr>
    <!-- Ampliación -->
    <div class="contenedor">
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleado con ordenación -->
            <a href="empleados/listado_ordenar.php">Listado de empleados con ordenación</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleado con filtros (número de hijos y salario) -->
            <a href="empleados/listado_filtrar.php">Listado de empleados con filtros por números de hijos y salario</a>
        </div>
    </div>
    <h1>Creación de tablas</h1>
    <div class="contenedor">
        <div class="enlaces">
            <!-- Poner enlace a listado de sedes -->
             <a href="sedes/nuevo.php">Alta de nuevas sedes</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a listado de departamentos -->
            <a href="departamentos/nuevo.php">Alta de nuevos departamento</a>
            
        </div>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleados -->
            <a href="empleados/nuevo.php">Alta de nuevos empleados</a>
        </div>
    </div>
    <hr>
    <div>
        <h1>Entrar</h1>     
        <!-- Formulario de identificación -->
        <form action="./acceso/acceso.php" method="post">
            <p>
                <input type="text" name="email" placeholder="Email"> 
            </p> 
            <p>
                <input type="password" name="contrasena" placeholder="Contraseña"> 
            </p>
            <p>
                <input type="submit" value="Entrar"> 
            </p>
        </form>

        <p>email: pperezdi@email.com</p>
        <p>contraseña: 123</p>
    </div>
</body>
</html>

</html>