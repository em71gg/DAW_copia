<?php
    // Incluye ficheros de variables y funciones
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
    // Intentamos recuperar la sesión
    session_name("sesion-privada");
    session_start();
    $privado=False;
    // Comprueba si existe la sesión "email"
    if (isset($_SESSION["email"])) $privado=True;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de empleados</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    
</head>
<body>
    <!--<h1>Listado de departamentos usando fetch (PDO::FETCH_ASSOC)</h1>-->
    <h1>Listado de empleados usando fetch (PDO::FETCH_OBJ)</h1>
    <?php
        // Realiza la conexion a la base de datos a través de una función 
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        // Realiza la consulta a ejecutar en la base de datos en una variable
        $consulta = "SELECT e.id id, e.nombre nombre, apellidos, email, hijos, salario, nacionalidad, 
         d.nombre departamento, s.nombre sede 
        FROM empleados e 
        INNER JOIN departamentos d ON 
        d.id = e.departamento_id
        INNER JOIN sedes s ON 
        s.id = d.sede_id
        INNER JOIN paises p ON
        p.id = e.pais_id";

        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El 
        $resultado = resultadoConsulta($conexion, $consulta);
    ?>
        
    <table border="1" cellpadding="10" style="margin-bottom: 10px;">
        <thead>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo electrónico</th>
            <th>Nº hijos</th>
            <th>Salario</th>
            <th>Nacionalidad</th>
            <th>Departamento</th>
            <th>Sede</th>
            <?php
                if ($privado):
            ?>                    
            <th>Acción</th>
            <?php
                endif;
            ?>
        </thead>
        <tbody>
            <!-- Mostrar todos los datos de los empleados -->
    <?php         
            while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
                echo "<tr><td>".$registro->nombre."</td><td>".$registro->apellidos."</td><td>"
                .$registro->email."</td><td>".$registro->hijos."</td><td>".$registro->salario."</td><td>"
                .$registro->nacionalidad."</td><td>".$registro->departamento."</td><td>".$registro->sede."</td>";
                if($privado){
                    echo "<td><a href='modificar.php?idEmpleado=" . $registro->id . "' class='estilo_enlace'>&#9998</a>"; 
                    echo "<a href='borrar.php?idEmpleado=". $registro->id . "' class='confirmacion_borrar'>&#128465</a></td>";
                }
                echo "</tr>" . PHP_EOL;
            }
    ?>            
        </tbody>
    </table>
        
    <div class="contenedor">
        <div class="enlaces">
            <a href="../index.php">Volver a página de listados</a>
            <?php
                if ($privado){
                    echo '<a href="nuevo.php">Añadir</a>&nbsp;';
                    echo '<a href="../acceso/cerrar-sesion.php">Salir</a>';
                }
            ?>
        </div>
    </div>
    <?php
        // Libera el resultado y cierra la conexión
        $resultado = null;
        $conexion = null;    
    ?>
</body>
</html>