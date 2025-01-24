<?php
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
    <title>Listado de piezas</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<?php

    $conexion = conectarPDO($host, $user, $password, $bbdd);
    try{
        $consulta =("SELECT
                    p.id as id, /*lo voy a necesitar para constuir el envío del get para borrar y modificar*/
                    p.nombre as nombre,
                    color,
                    precio,
                    c.nombre as categoria
                 FROM 
                    pieza p
                INNER JOIN 
                    categoria c ON p.id_categoria= c.id
                ");
        $resultado = resultadoConsulta($conexion, $consulta);
    }catch(PDOException $e){
        echo "<p>" . $e -> getMessage() . "</p>";
    }finally{
        desconectarPDO($consulta, $conexion);
    }
    
?>
<body>
    <h1>Listado de piezas</h1>
    <table border="1" cellpadding="10">
        <thead>
            <th>Nombre</th>
            <th>Color</th>
            <th>Precio</th>
            <th>Categoría</th>
            <th>Acción</th>
        </thead>
        <tbody>

            <!-- Muestra los datos -->
    <?php      
         while($registro = $resultado -> fetch(PDO::FETCH_ASSOC)){

            echo "<tr>
                    <td>" . $registro['nombre'] . "</td>
                    <td>" . $registro['color'] . "</td>
                    <td>" . $registro['precio'] . "</td>
                    <td>" . $registro['categoria'] . "</td>
                    <td><a href='modificar.php?idPieza=$registro[id]' class='estilo_enlace'>&#9998</a>
                    <a href='borrar.php?idPieza=$registro[id]' class='confirmacion_borrar'>&#128465</a></td>
                 </tr>".PHP_EOL;

        }
           
        //desconectarPDO($consulta, $conexion);
        
    ?>
        </tbody>
    </table>
    <div class="contenedor">
        <div class="enlaces">
            <a href="../index.html">Volver a página de listados</a>
            <a href="nuevo.php">Añadir</a>
        </div>
    </div>

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