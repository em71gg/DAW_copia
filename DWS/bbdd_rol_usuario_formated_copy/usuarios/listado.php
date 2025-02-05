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
    <title>Listado de sedes</title>
    <!-- <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">-->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>
    
    <header>
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-500 dark:from-gray-500 dark:via-gray-600 dark:to-gray-500 py-8 md:py-16">
            <div class="flex flex-row justify-between items-center px-6">
                <p class="text-3xl font-bold text-white text-center flex-grow text-center">
                    Aplicación Empresa
                </p>

            </div>
        </div>
    </header>
    <!--<header>
        <div class="header-container">
            
            <a href="../acceso/cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
        
    </header>-->
    
    <h1>Listado de usuarios</h1>

    <?php
    // Realiza la conexion a la base de datos a través de una función 
    $conexion = conectarPDO($host, $user, $password, $bbdd);

    // Realiza la consulta a ejecutar en la base de datos en una variable
    $query = $conexion->query('SELECT 
                                            email, 
                                            nombre,
                                            CASE
                                                WHEN activo = 1 THEN "Activo"
                                                WHEN activo = 0 THEN "Inactivo"
                                            END AS estado, 
                                            fecha
                                        FROM usuarios
                                        JOIN roles
                                        WHERE usuarios.rol_id = roles.id'); //añado id a la consulta


    // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement

    $rdo = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div
        class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
        <table class="w-full text-left table-auto min-w-max">
            <thead>
                <tr>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p
                            class="block text-sm font-normal leading-none text-slate-500">Email
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p
                            class="block text-sm font-normal leading-none text-slate-500">Rol
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p
                            class="block text-sm font-normal leading-none text-slate-500">Estado
                        </p>
                    </th>
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p
                            class="block text-sm font-normal leading-none text-slate-500">Fecha
                        </p>
                    </th>

                    <!--<?php
                        if ($rol == 1):
                        ?>-->
                    <th class="p-4 border-b border-slate-300 bg-slate-50">
                        <p
                            class="block text-sm font-normal leading-none text-slate-500">Acción
                        </p>
                    </th>
                <?php
                        endif;
                ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rdo as $row) {
                    echo "<tr vclass='hover:bg-slate-50'><td class='p-4 border-b border-slate-200'> <p class='block text-sm text-slate-800'>"
                        . $row['email']
                        . "</p></td><td class='p-4 border-b border-slate-200'><p class='block text-sm text-slate-800'>" . $row['nombre'] . "</p></td><td class='p-4 border-b border-slate-200'>"
                        . $row['estado'] . "</td><td class='p-4 border-b border-slate-200'><p class='block text-sm text-slate-800'>" . $row['fecha'];

                    echo "</p><td class='p-4 border-b border-slate-200'><a href='modificar.php?emailUsuario=$row[email]' class='estilo_enlace'>&#9998</a>
                        <a href='borrar.php?emailUsuario=$row[email]' class='confirmacion_borrar'>&#128465</a></td>";
                }



                ?>

            </tbody>
        </table>
    </div>


    <div class="contenedor">
        <div class="enlaces">
            <p><a href="../acceso/index.php">Volver a página de listados</a>&nbsp;&nbsp;<a href="../acceso/registro.php">Registrar nuevo usuario</a>

        </div>
    </div>
    </main>
    <?php

    // Libera el resultado y cierra la conexión
    desconectarPDO($query, $conexion);

    ?>

    <script type="text/javascript">
        var elementos = document.getElementsByClassName("confirmacion_borrar");
        var confirmFunc = function(e) {
            if (!confirm('Está seguro de que desea borrar este registro?')) e.preventDefault();
        };
        for (var i = 0, l = elementos.length; i < l; i++) {
            elementos[i].addEventListener('click', confirmFunc, false);
        }
    </script>
</body>

</html>