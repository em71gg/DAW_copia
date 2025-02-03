<?php
// Activa las sesiones
session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["email"])) header("Location: ../login.php");
$rol = $_SESSION['rol_id'];
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
                    Aplicación Empresa
                </p>
                <div class="flex gap-2">
                    <a href="./cerrar-sesion.php">
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
                        Listados
                    </h1>
                    <!-- Poner enlace a listado de sedes -->
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Quiere ver el listado de sedes?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../sedes/listado.php">
                            Entrar
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Quiere ver el listado de departamentos?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../departamentos/listado.php">
                            Entrar
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Quiere ver el listado de empleados?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../empleados/listado.php">
                            Entrar
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Quiere filtrar empleados?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../empleados/listado_filtrar.php">
                            Entrar
                        </a>
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Quiere listar los empleados con ordenación?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../empleados/listado_filtrar.php">
                            Entrar
                        </a>
                    </p>
                    <?php
                    if ($rol == 1):
                    ?>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Quiere ver el listado de usuarios?
                        <a
                            class="text-blue-600 decoration-2 hover:underline font-medium"
                            href="../usuarios/listado.php">
                            Ir a usuarios
                        </a>
                    </p>
                    <?php
                    endif;
                    ?>

                </div>
               
            </div>

        </div>
    </main>
</body>

</html>

</html>