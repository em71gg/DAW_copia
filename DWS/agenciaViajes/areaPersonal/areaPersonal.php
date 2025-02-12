<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

$consulta = $conexion->prepare("SELECT 
                                    o.nombre,
                                    o.descripcion
                                    o.fecha_actividad,
                                    o.aforo - COALESCE(COUNT(s.oferta_id), 0) AS plazas_disponibles
                                FROM ofertas o
                                LEFT JOIN solicitudes s ON o.id = s.oferta_id/*lef join para que me rescate también las ofertas que no tengan coincidencia en solicitud, osea las que tienen todas las plazas libres porque no ha habido solicitudes todavía*/
                                GROUP BY o.nombre
                                HAVING plazas_disponibles > 0
                                WHERE o.visada =1");


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
                    <form action="./areaPersonal/areaPersonal.php" method="post">
                        
                        <div class="logout-button-container">
                            <input type="submit" class="logout-button" placeholder="Cerrar Sesion">
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