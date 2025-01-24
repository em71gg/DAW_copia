<?php
    require_once("../utiles/funciones.php");
    require_once("../utiles/variables.php");
    
    //Se ntenta recuperar la sesión privada.
    session_name('sesion-privada');
    session_start();
    $privado = false;
    //Si existe la variable de sesion 'email' -> el flag se pone a true.
    if (isset($_SESSION['email'])) $privado = true;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de sedes</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Listado de sedes usando fetch (PDO::FETCH_ASSOC)</h1>

    <?php
        // Realiza la conexion a la base de datos a través de una función 
        $conexion = conectarPDO($host, $user, $password, $bbdd);

        $consulta = "SELECT id, nombre, direccion FROM sedes";

        // Realiza la consulta a ejecutar en la base de datos en una variable
        $query = $conexion -> query($consulta);

        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
        $rdo = $query -> fetchAll(PDO::FETCH_ASSOC);
    
    ?>

    <table border="1" cellpadding="10">
        <thead>
            <th>Nombre</th>
            <th>Dirección</th>
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
             <?php
                foreach($rdo as $row) {
                    echo"<tr>
                        <td>" . $row['nombre'] . "</td>
                        <td>" . $row['direccion'] . "</td>";
                    if ($privado) {
                        echo "<td><a href='modificar.php?idSede=" . $row["id"] . "' class='estilo_enlace'>&#9998</a>";
                        echo "<a href='borrar.php?idSede=". $row["id"] . "' class='confirmacion_borrar'>&#128465</a></td>";
                    }
                    echo "</tr>" . PHP_EOL;
                }
             ?>

        </tbody>
    </table>
    <div class="contenedor">
        <div class="enlaces">
            <a href="../index.php">Volver a página de listados</a>
        </div>
    </div>

    <?php

        // Libera el resultado y cierra la conexión
        desconectarPDO($consulta, $conexion);
    
    ?>
    <script type="text/javascript">    
        var elementos = document.getElementsByClassName("confirmacion_borrar");
        var confirmFunc = function (e) {
            if (!confirm('Está seguro de que desea borrar este regitro?')) e.preventDefault();
        };
        for (var i = 0, l = elementos.length; i < l; i++) {
            elementos[i].addEventListener('click', confirmFunc, false);
        }
    </script> 
</body>
</html>