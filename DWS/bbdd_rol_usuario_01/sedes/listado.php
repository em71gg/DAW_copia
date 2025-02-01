<?php
    // Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
    $rol= $_SESSION['rol_id'];
    // Incluye ficheros de variables y funciones
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de sedes</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            
            <a href="../acceso/cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
        
    </header>
    <h1>Listado de sedes usando fetch (PDO::FETCH_ASSOC)</h1>

    <?php
            echo $rol;
            // Realiza la conexion a la base de datos a través de una función 
            $conexion = conectarPDO($host, $user, $password, $bbdd);
            
            // Realiza la consulta a ejecutar en la base de datos en una variable
            $query = $conexion->query('select id, nombre , direccion FROM sedes');//añado id a la consulta

            
            // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement

            $rdo = $query -> fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table border="1" cellpadding="10">
        <thead>
            
            <th>Nombre</th>
            <th>Dirección</th>
        <?php
            if($rol == 1):
        ?>
            <th>Acción</th>
        <?php
            endif;
        ?>
        </thead> 
        <tbody>
        <?php
                foreach($rdo as $row){
                    echo "<tr><td>". $row['nombre']. "</td><td>". $row['direccion'] . "</td>";
                    if($rol == 1) {
                        echo "<td><a href='modificar.php?idSede=$row[id]' class='estilo_enlace'>&#9998</a>
                        <a href='borrar.php?idSede=$row[id]' class='confirmacion_borrar'>&#128465</a></td>";
                    }
                    echo "</tr>".PHP_EOL;   
                }

        ?>

        </tbody>
    </table>
    <div class="contenedor">
        <div class="enlaces">
        <p><a href="../acceso/index.php">Volver a página de listados</a>
<?php
    if($rol == 1):
?>
             &nbsp;&nbsp;&nbsp;&nbsp;<a href="nuevo.php">Crear nueva sede</a></p><p>Este es el sitio correcto</p>
<?php
    endif;
?>
        </div>
    </div>

    <?php

        // Libera el resultado y cierra la conexión
        desconectarPDO($query, $conexion);
    
    ?>

    <script type="text/javascript">    
            var elementos = document.getElementsByClassName("confirmacion_borrar");
        var confirmFunc = function (e) {
            if (!confirm('Está seguro de que desea borrar este registro?')) e.preventDefault();
        };
        for (var i = 0, l = elementos.length; i < l; i++) {
            elementos[i].addEventListener('click', confirmFunc, false);
        }
</script>    
</body>
</html>