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
    <title>Listado de departamentos</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Listado de departamentos usando fetch (PDO::FETCH_BOUND)</h1>

    <?php
        // Realiza la conexion a la base de datos a través de una función 
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        // Realiza la consulta a ejecutar en la base de datos en una variable
        $consulta = "SELECT d.id, d.nombre, presupuesto, s.nombre 
            FROM departamentos d 
            INNER JOIN sedes s ON s.id = d.sede_id";
        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
        $resultado = resultadoConsulta($conexion, $consulta);
        
        $resultado->bindColumn(1, $id);
        $resultado->bindColumn(2, $nombre);
        $resultado->bindColumn(3, $presupuesto);
        $resultado->bindColumn(4, $nombreSede);
    ?>
        <table border="1" cellpadding="10">
            <thead>
                <th>Departamento</th>
                <th>Presupuesto</th>
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
                   <!-- Muestra los datos -->
    <?php         
            while ($registro = $resultado->fetch(PDO::FETCH_BOUND)) {
                echo "<tr><td>".$nombre."</td><td>".$presupuesto."</td><td>".$nombreSede."</td>";
                if($privado){
                    echo "<td><a href='modificar.php?idDepartamento=" . $id . "' class='estilo_enlace'>&#9998</a>"; 
                    echo "<a href='borrar.php?idDepartamento=". $id . "' class='confirmacion_borrar'>&#128465</a></td>";
                }    
                echo "</tr>" . PHP_EOL;
            }
    ?>    
            </tbody>
        </table>
        <div class="contenedor">
            <div class="enlaces">
                <a href="../index.php">Volver a página de listados</a>&nbsp;
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