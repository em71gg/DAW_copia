<?php
    // Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
    // Incluye ficheros de variables y funciones
    require_once("../utiles/funciones.php");
    require_once("../utiles/variables.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de empleados</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript">
        function ordenarListado(campo, orden)
        {
            location.href = "listado_ordenar.php?orden="+campo+"&sentido="+orden;//está mandando el get
        }
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            
            <a href="../cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
    <h1>Listado de departamentos usando fetch (PDO::FETCH_OBJ)</h1>
    <?php
        // Campos que permiten ordenación
        $camposOrdenacion = ["nombre", "apellidos", "email", "hijos", "salario", "nacionalidad", "departamento", "sede"];//nuestras columnas se deben llamar igual

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

        // Obtener sentido de la ordenación. ESTÁ INCOMPLETO. 
        //Lo tenéis que completar, siendo similar 
        //al "campo de la ordenación" anterior:
        $sentidosOrdenacion = ["ASC", "DESC"];
       if (isset($_GET['sentido'])){
            $sentidoOrden = $_GET['sentido'];
            if(!in_array($sentidoOrden, $sentidosOrdenacion)){
                $sentidoOrden = $sentidosOrdenacion[0];
            }
        }else{
            $sentidoOrden = $sentidosOrdenacion[0];
        }
        // **COMPLETAR usando una variable $sentidoOrdenar** 

        
        // Realiza la conexion a la base de datos a través de una función.
       
        $conexion=conectarPDO($host, $user, $password, $bbdd);
       

        // Realiza la consulta a ejecutar en la base de datos utilizando las variables $campoOrdenar y $sentidoOrdenar.
        $consulta =     ("SELECT 
                            e.nombre as nombre, 
                            e.apellidos,
                            e.email,
                            e.hijos, 
                            e.salario,
                            p.nacionalidad,
                            d.nombre as departamento,
                            s.nombre as sede
                        FROM 
                            empleados e, departamentos d, sedes s, paises p
                        WHERE 
                            e.departamento_id=d.id AND d.sede_id=s.id AND e.pais_id=p.id
                        ORDER BY
                            {$campoOrdenar} {$sentidoOrden}"
                                        );

        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
        $resultado = resultadoConsulta($conexion, $consulta);
        //echo $resultado -> rowCount();


 
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
            </thead>
            <tbody>

                <!-- Muestra los datos. Para ello tendrás que recorrer la matriz de los resultados -->
                <?php
                  while ($registro = $resultado -> fetch(PDO::FETCH_OBJ)){
                        echo "<tr>
                            <td>" .$registro -> nombre . "</td>
                            <td>". $registro -> apellidos. "</td>
                            <td>". $registro -> email. "</td>
                            <td>". $registro -> hijos. "</td>
                            <td>". $registro -> salario. "</td>
                            <td>". $registro -> nacionalidad. "</td>
                            <td>". $registro -> departamento. "</td>
                            <td>" . $registro -> sede. "</td>
                        </tr>".PHP_EOL;
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
        $resultado = null;
        $conexion = null;
    ?>
</body>
</html>