<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$consulta = $conexion->prepare("SELECT nombre, descripcion, fecha_actividad, aforo FROM ofertas WHERE visada=1 ORDER BY fecha_actividad DESC");
$consulta->execute();

$rdo = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!--
Pubnto de entrada a la aplicacion, muestra las actividades validadas y no cumplidas en fecha. 
Ofrece la posibiloidad de login y registro.
El login es el punto de entrada a la parte privada para clientes demandantes, clientes ofertantes y empleados.
    El login se gestiona desde el archivo "./acceso/login.php"

El registro es para ser usado por clientes ofertantes/demandantes, los tabajadores son dados de alta por elperfil administrador
que es creado al iniciar la aplicación.
    El registro se gestiona desde el archivo "./acceso/registro.php"
-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <link rel="stylesheet" href="./styles/styles.css">
</head>
<body>
    <header>
        <div class="header-bg">
            <div class="header-container">
                <div></div>
                <div>
                    <p class="header-title">Agencia de viajes</p>
                </div>
                <div id="options-header">
                    <div class="logout-button-container">
                        <a class="fcc-btn" href="./acceso/login.php">Login</a>  
                    </div>
                    <div class="logout-button-container">
                        <a class="fcc-btn" href="./acceso/registro.php">Registrarse</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main id="content" role="main">
        <div class="table-container">
            <div class=info-table>
                <div class="text-center">
                    <h1 class="title">Listado de actividades</h1>
                </div>
                <div id="div-table">
                    <table id="customers">
                        <thead>
                            <tr>
                                <th>Nombre de la Actividad</th>
                                <th>Descripción de la actividad</th>
                                <th>Fecha de la actividad</th>
                                <th>Aforo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($rdo as $campo) {
                                    echo "<tr>
                                    <td>" . $campo['nombre'] . "</td>
                                    <td>" . $campo['descripcion'] . "</td>
                                    <td>" . $campo['fecha_actividad'] . "</td>
                                    <td>" . $campo['aforo'] . "</td>
                                </tr>" . PHP_EOL;
                                }
                            ?>
                        </tbody>
                         <tfoot>
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>