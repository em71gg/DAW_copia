<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
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

    <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Inicie Sesion
                    </h1>
                </div>
                <div class="mt-5">
                    <form action="./acceso/acceso.php" method="post" method="post">
                        <div class="grid gap-y-4">
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Dirección email</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="email"
                                        name="email"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"

                                        aria-describedby="email-error"
                                        placeholder="Email" />
                                </div>

                            </div>
                            <div>
                                <label
                                    for="password"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Contraseña</label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="contrasena"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"

                                        aria-describedby="email-error"
                                        placeholder="Contraseña" />
                                </div>

                            </div>
                            <div class="text-center">

                            </div>
                            <button
                                type="submit"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                                Entrar
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            ¿Recuerda su contraseña?
                            <a
                                class="text-blue-600 decoration-2 hover:underline font-medium"
                                href="./restablecer/confirmarCorreo.php">
                                Restablecer contraseña
                            </a>
                        </p>

                    </div>
                </div>
            </div>

        </div>

        <div
            class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">email: admin@email.com, rol: (1) administrador.</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">email: gestor@email.com, rol: (2) gestor.</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">email: consulta@email.com, rol: (3) consultor.</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">contraseña: 12345.</p>
                </div>

            </div>

        </div>
    </main>



</body>

</html>