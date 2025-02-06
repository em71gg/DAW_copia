<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$consulta = $conexion->prepare("SELECT nombre, descripcion, fecha_actividad, aforo FROM ofertas WHERE visada=1");
$consulta->execute();

$rdo = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <link rel="stylesheet" href="./styles.css">
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
                    <form action="#" method="post">
                        <div class="logout-button-container">
                            <input type="text" class="logout-button" placeholder="Usuario" id="user">
                        </div>
                        <div class="logout-button-container">
                            <input type="password" class="logout-button" placeholder="Contraseña">
                        </div>
                    </form>

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

                    </table>
                </div>

            </div>

        </div>
    </main>
</body>

</html>