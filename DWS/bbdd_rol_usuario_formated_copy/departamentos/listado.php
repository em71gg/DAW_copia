<?php
    // Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
    $rol = $_SESSION['rol_id'];
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
    <title>Listado de departamentos</title>
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
    <h1>Listado de departamentos usando fetch (PDO::FETCH_BOUND)</h1>

    <?php
        // Realiza la conexion a la base de datos a través de una función 
        $conexion = conectarPDO($host,$user,$password,$bbdd);
        // Realiza la consulta a ejecutar en la base de datos en una variable
        $consulta = $conexion -> query("SELECT 
                                            
                                            d.nombre, 
                                            d.presupuesto, 
                                            s.nombre,
                                            d.id /*añado la id de departamentos a la consulta*/
                                        FROM 
                                            departamentos d 
                                        JOIN
                                            sedes s 
                                        ON d.sede_id=s.id"
                                        );
        
        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
       
                
    ?>
        <table border="1" cellpadding="10">
            <thead>
                <th>Departamento</th>
                <th>Presupuesto</th>
                <th>Sede</th>
            <?php
                if($rol == 1):
            ?>
                <th>Acción</th>
            <?php
                endif;
            ?>
            </thead>
            <tbody>
                <!-- Muestra los datos -->
            <?php
                
                 while ($registro = $consulta -> fetch(PDO::FETCH_BOTH)){

                    echo "<tr><td>$registro[0]</td><td>$registro[1]</td><td>$registro[3]</td>";
                    if($rol == 1) {
                    echo "<td><a href='modificar.php?idDepartamento={$registro[3]}' class='estilo_enlace'>&#9998</a>
                    <a href='borrar.php?idDepartamento={$registro[3]}' class='confirmacion_borrar'>&#128465</a></td>";
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
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="nuevo.php">Crear nuevo departamento</a>
                <?php
                    endif;
                ?>
                </p>
            </div>
        </div>

    
    <?php

        // Libera el resultado y cierra la conexión
        desconectarPDO($consulta, $conexion);
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

