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
    <script type="text/javascript">
        function ordenarListado(campo, orden)
        {
            location.href = "listado_ordenar.php?orden="+campo+"&sentido="+orden;
        }
    </script>
</head>
<body>
    <h1>Listado de departamentos usando fetch (PDO::FETCH_OBJ)</h1>
    <?php
        // Campos que permiten ordenación
        $camposOrdenacion = ["nombre", "apellidos", "email", "hijos", "salario", "nacionalidad", "departamento", "sede"];

        // Obtener campo de la ordenación
        if (isset($_GET["orden"])) 
        {
            $campoOrdenar = $_GET["orden"];
            if (!in_array($campoOrdenar,$camposOrdenacion)) 
            {
                $campoOrdenar = $camposOrdenacion[0];
            }
        } 
        else 
        {
            $campoOrdenar = $camposOrdenacion[0];
        }

        // Obtener sentido de la ordenación. ESTÁ INCOMPLETO. Lo tenéis que completar, siendo similar al "campo de la ordenación" anterior:
        $sentidosOrdenacion = ["ASC", "DESC"];
        // **COMPLETAR usando una variable $sentidoOrdenar** 
        if (isset($_GET["sentido"])) 
        {
            $sentidoOrdenar = $_GET["sentido"];
            if (!in_array($sentidoOrdenar,$sentidosOrdenacion)) 
            {
                $sentidoOrdenar = $sentidosOrdenacion[0];
            }
        } 
        else 
        {
            $sentidoOrdenar = $sentidosOrdenacion[0];
        }
        
        // Realiza la conexion a la base de datos a través de una función.
        $conexion = conectarPDO($host, $user, $password, $bbdd);        

        // Realiza la consulta a ejecutar en la base de datos utilizando las variables $campoOrdenar y $sentidoOrdenar.
        $consulta = "SELECT e.id id, e.nombre nombre, apellidos, email, hijos, salario, nacionalidad,  d.nombre departamento, s.nombre sede 
        FROM empleados e 
        INNER JOIN departamentos d ON 
        d.id = e.departamento_id
        INNER JOIN sedes s ON 
        s.id = d.sede_id
        INNER JOIN paises p ON
        p.id = e.pais_id
        ORDER BY ". $campoOrdenar. " " . $sentidoOrdenar ;

        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
        $resultado = resultadoConsulta($conexion, $consulta);
 
    ?>

        <table border="1" cellpadding="10">
            <thead>
                <th>Nombre <a href="javascript: void(0);" onclick="ordenarListado('nombre', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('nombre', 'DESC')">&#8595</a></th>
                <th>Apellidos <a href="javascript: void(0);" onclick="ordenarListado('apellidos', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('apellidos', 'DESC')">&#8595</a></th>
                <th>Correo electrónico <a href="javascript: void(0);" onclick="ordenarListado('email', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('email', 'DESC')">&#8595</a></th>
                <th>Nº hijos <a href="javascript: void(0);" onclick="ordenarListado('hijos', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('hijos', 'DESC')">&#8595</a></th>
                <th>Salario <a href="javascript: void(0);" onclick="ordenarListado('salario', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('salario', 'DESC')">&#8595</a></th>
                <th>Nacionalidad <a href="javascript: void(0);" onclick="ordenarListado('nacionalidad', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('nacionalidad', 'DESC')">&#8595</a></th>
                <th>Departamento <a href="javascript: void(0);" onclick="ordenarListado('departamento', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('departamento', 'DESC')">&#8595</a></th>
                <th>Sede <a href="javascript: void(0);" onclick="ordenarListado('sede', 'ASC')">&#8593</a> <a href="javascript: void(0);" onclick="ordenarListado('sede', 'DESC')">&#8595</a></th>
                <?php
                    if ($privado):
                ?>                    
                    <th>Acción</th>
                <?php
                    endif;
                ?>
            </thead>
            <tbody>
                <!-- Muestra los datos. Para ello tendrás que recorrer la matriz de los resultados -->
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