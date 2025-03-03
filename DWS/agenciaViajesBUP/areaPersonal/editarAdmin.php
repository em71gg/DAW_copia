<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["id"])) header("Location: ../inicio.php");
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $flag = (isset($_GET['estado'])) ? $_GET['estado'] : null;
}

$conexion = conectarPDO($host, $user, $password, $bbdd);

$consulta = $conexion -> prepare('SELECT 
                                        g.id AS ID,
                                        g.nombre AS Nombre,
                                        g.email AS Email,
                                        p.perfil AS Perfil,
                                        g.created_at AS Creado,
                                        g.updated_at AS Actualizado
                                    FROM gestores g
                                    LEFT JOIN perfiles p ON g.perfil_id = p.id');
$consulta -> execute();

$rdo = $consulta -> fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Estilo para transformar la tabla en un diseño apilado en pantallas pequeñas */
        @media (max-width: 640px) {
            #customers thead {
                display: none; /* Oculta los encabezados */
            }
            #customers tbody tr {
                display: block; /* Cada fila se muestra como un bloque */
                margin-bottom: 1rem;
                border-bottom: 2px solid #e5e7eb;
            }
            #customers tbody td {
                display: block; /* Cada celda se muestra como un bloque */
                text-align: left;
                padding: 0.5rem;
                position: relative;
                padding-left: 8rem; /* Espacio para la etiqueta */
            }
            #customers tbody td:before {
                content: attr(data-label); /* Usa el atributo data-label como etiqueta */
                position: absolute;
                left: 0.5rem;
                width: 7rem;
                font-weight: bold;
                color: #4a5568;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-500 dark:from-gray-500 dark:via-gray-600 dark:to-gray-500 py-8 md:py-16">
            <div class="flex flex-row justify-between items-center px-6">
                <p class="text-3xl font-bold text-white text-center flex-grow text-center">
                    Agencia de Viajes
                </p>
                <div class="flex gap-2">
                    <a href="../acceso/cerrarSesion.php">
                        <button
                            type="submit"
                            class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-white font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                            Cerrar Sesion
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main id="content" role="main" class="w-full p-6">
        <div class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Tablero de gestion de personal.
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Registrar un alta de personal
                        <a class="text-blue-600 decoration-2 hover:underline font-medium" href="../acciones/altaPersonal.php">
                            Registrar 
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Registrar un cambio de función
                        <a class="text-blue-600 decoration-2 hover:underline font-medium" href="../acciones/altaPersonal.php">
                            Registrar 
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Registrar una baja de personal
                        <a class="text-blue-600 decoration-2 hover:underline font-medium" href="../acciones/bajaPersonal.php">
                            Registrar
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Registro de empleados
                    </h1>
                    <p>&nbsp</p>
                    <div id="div-table" class="overflow-x-auto">
                        <table id="customers" class="w-full border-collapse rounded-lg font-roboto">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-500 text-white">
                                    <th class="py-2 px-2 text-left rounded-tl-lg sm:py-3 sm:px-4 text-xs sm:text-sm">ID</th>
                                    <th class="py-2 px-2 text-left sm:py-3 sm:px-4 text-xs sm:text-sm">Perfil_id</th>
                                    <th class="py-2 px-2 text-left sm:py-3 sm:px-4 text-xs sm:text-sm">Nombre</th>
                                    <th class="py-2 px-2 text-left sm:py-3 sm:px-4 text-xs sm:text-sm">Email</th>
                                    <th class="py-2 px-2 text-left sm:py-3 sm:px-4 text-xs sm:text-sm">Creado</th>
                                    <th class="py-2 px-2 text-left sm:py-3 sm:px-4 text-xs sm:text-sm">Actualizado</th>
                                    <th class="py-2 px-2 text-left rounded-tr-lg sm:py-3 sm:px-4 text-xs sm:text-sm">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($rdo)) {
                                    echo "<tr><td colspan='7' class='p-2 text-center text-gray-600 italic sm:p-4 text-xs sm:text-sm'>No hay empleados registrados</td></tr>";
                                } else {
                                    $rowIndex = 0;
                                    foreach ($rdo as $campo) {
                                        $rowIndex++;
                                        $rowClass = $rowIndex % 2 == 0 ? 'bg-blue-50' : 'bg-white';
                                        echo "<tr class='$rowClass hover:bg-gray-200'>
                                            <td class='p-1 sm:p-2 text-gray-800 whitespace-normal break-words text-xs sm:text-sm' data-label='ID'>" . $campo['ID'] . "</td>
                                            <td class='p-1 sm:p-2 text-gray-800 whitespace-normal break-words text-xs sm:text-sm' data-label='Perfil_id'>" . $campo['Perfil'] . "</td>
                                            <td class='p-1 sm:p-2 text-gray-800 whitespace-normal break-words text-xs sm:text-sm' data-label='Nombre'>" . $campo['Nombre'] . "</td>
                                            <td class='p-1 sm:p-2 text-gray-800 whitespace-normal break-words text-xs sm:text-sm' data-label='Email'>" . $campo['Email'] . "</td>
                                            <td class='p-1 sm:p-2 text-gray-800 whitespace-normal break-words text-xs sm:text-sm' data-label='Creado'>" . $campo['Creado'] . "</td>
                                            <td class='p-1 sm:p-2 text-gray-800 whitespace-normal break-words text-xs sm:text-sm' data-label='Actualizado'>" . $campo['Actualizado'] . "</td>
                                            <td class='p-1 sm:p-2 text-gray-800 text-xs sm:text-sm' data-label='Acciones'>
                                                <a href='../acciones/bajaPersonal2.php?id=" . $campo['ID'] . "' class='text-blue-600 font-bold hover:underline'>Baja</a>
                                            </td>
                                        </tr>" . PHP_EOL;
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-500">
                                    <td colspan="7" class="p-2 rounded-bl-lg rounded-br-lg sm:p-4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>