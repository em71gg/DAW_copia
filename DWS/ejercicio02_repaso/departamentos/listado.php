<?php

    // Incluye ficheros de variables y funciones
    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');

    session_name('sesion-privada');
    session_start();
    $privado = false;
    if(isset($_SESSION['email'])) $privado =true;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de departamentos</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Listado de departamentos usando fetch (PDO::FETCH_BOUND)</h1>

    <?php
        // Realiza la conexion a la base de datos a través de una función
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $consulta = $conexion ->query("SELECT 
                                        d.nombre, 
                                        d.presupuesto, 
                                        s.nombre,
                                        d,id
                                    FROM departamentos d
                                    JOIN sedes s
                                    ON d.sede_id =s.id"
                                    );
        
        // Realiza la consulta a ejecutar en la base de datos en una variable
        
        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
        

    ?>
        <table border="1" cellpadding="10">
            <thead>
                <th>Departamento</th>
                <th>Presupuesto</th>
                <th>Sede</th>
    <?php
        if($privado):
    ?>
                <th>Acción</th>
    <?php
        endif;
    ?>
            </thead>
            <tbody>
                
                <!-- Muestra los datos -->
                
            </tbody>
        </table>
        <div class="contenedor">
            <div class="enlaces">
                <a href="../index.php">Volver a página de listados</a>
            </div>
        </div>

    
    <?php
        $consulta -> bindColumn(1, $nombre);
        $consulta -> bindColumn(2, $presupuesto);
        // Libera el resultado y cierra la conexión
        while ($row = $consulta -> fetch(PDO::FETCH_BOUND)) {
            echo "<tr>
                <td></td>
                <td></td>
                <td></td>
                "
        }
    
    ?>
</body>
</html>