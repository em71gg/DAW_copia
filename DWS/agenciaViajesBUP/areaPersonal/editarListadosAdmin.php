<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["id"])) header("Location: ../inicio.php");
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $flag =  (isset($_GET['estado'])) ? $_GET['estado'] : null;
    $_SESSION['idOferta'] = (isset($_GET['idOferta'])) ? $_GET['idOferta'] : null;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa</title>
    <!--<link rel="stylesheet" type="text/css" href="../css/estilos.css">-->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

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
    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Las opciones de edición para esta oferta son:
                    </h1>
                    
                    <?php
                        if ($flag == 0):                    
                    ?>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Modificar la oferta
                            <a
                                class="text-blue-600 decoration-2 hover:underline font-medium"
                                href="../acciones/modificarOferta.php">
                                Modificar
                            </a>
                        </p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Eliminar la Oferta
                            <a
                                class="text-blue-600 decoration-2 hover:underline font-medium"
                                href="../acciones/eliminarOferta.php">
                                Eliminar
                            </a>
                        </p>
                    <?php
                        endif;
                    ?>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Repetir la oferta
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../acciones/repetirOferta.php">
                            Repetir
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        También puede crear una nueva oferta 
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../acciones/altaOferta.php">
                            Crear
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Volver a la página personal 
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../areaPersonal/administrador.php">
                            Volver 
                        </a>
                    </p>

                </div>
               
            </div>

        </div>
    </main>
</body>

</html>

</html>