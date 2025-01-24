<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
    <h1>Listados</h1>
    <div class="contenedor">
        <div class="enlaces">
            <!-- Poner enlace a listado de sedes -->
            <a href="./sedes/listado.php">Listado de sedes</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a listado de departamentos -->
            <a href="./departamentos/listado.php">Listado de departamento</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleados -->
            <a href="./empleados/listado.php">Listado de empleados</a>
        </div>
    </div>
    <hr>
    <!-- Ampliación -->
    <div class="contenedor">
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleado con ordenación -->
            <a href="./empleados/listado_ordenar.php">Listado de empleados con ordenación</a>
        </div>
        <div class="enlaces">
            <!-- Poner enlace a Listado de empleado con filtros (número de hijos y salario) -->
            <a href="./empleados/listado_filtrar.php"> de empleados con filtros por números de hijos y salario</a>
        </div>
    </div>
    <hr>
    <?php
        session_name('sesion-privada');//este campo debe estar sobre el de session_start.
        session_start();
         if(!isset($_SESSION['email'])):   
    ?>
    <h2>Acceso al área privada</h2>
    <form action="acceso/acceso.php" method="post">
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
   
    <?php
        endif;
    ?>
</body>
</html>

</html>