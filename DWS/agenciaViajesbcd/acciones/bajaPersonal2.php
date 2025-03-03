<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "id" i a session idEmpleado, en caso contrario vuelve a la página de login
if (!isset($_SESSION["id"])) header("Location: ../inicio.php");
//if(!isset($_SESSION['idEmpleado'])) header("location: ../inicio.php");
//if($_SESSION['idEmpleado'] == null) $errores['Error con el id de la oferta'];
$id = $_SESSION['id'];
$idEmpleado = null;
$errores = [];
$nombre = null;
$email = null;
$perfil = null;
$salida = null;


if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $idEmpleado =$_GET['id'];
    echo "<p>el identificador recogido en el idEmpleado desde el get es:  $idEmpleado</p>";
    try {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $getEmpleado = $conexion -> prepare('SELECT 
                                                g.id AS ID,
                                                g.nombre AS Nombre,
                                                g.email AS Email,
                                                p.perfil AS Perfil
                                                
                                            FROM gestores g
                                            LEFT JOIN perfiles p ON g.perfil_id = p.id
                                            WHERE g.id = ?');
        $getEmpleado -> bindParam(1, $idEmpleado);
        $getEmpleado -> execute();

        if($getEmpleado -> rowCount() > 0) {
            /*desconectarPDO($consulta, $conexion);
            header('location: ../areaPersonal/ofertante');
            exit();*/
            $registro = $getEmpleado -> fetch(PDO::FETCH_ASSOC);
            $nombre = $registro['Nombre'];
            $email = $registro['Email'];
            $perfil = $registro['Perfil'];
            
        }
    }catch (PDOException $e) {}finally {}
    

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $eliminarOferta = $conexion -> prepare ('DELETE
                                                    FROM gestores
                                                    WHERE id = ?
                                                ');
        $eliminarOferta -> bindParam(1, $_POST['eliminarId']);
        $eliminarOferta -> execute();

        if($eliminarOferta -> rowCount() >0) {
            $salida = "Baja registrada";
            header('Refresh: 3; url=../areaPersonal/editarAdmin.php');
        }

    } catch (PDOException $e) {} finally {}
    
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <script src='../scripts/despliegues.js'></script>
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
                        Confirme que desea registrar la siquiente Baja
                    </h1>
                    <?php
                     if($salida != null):
                    ?>
                        <p class="mt-2 text-sm font-bold text-blue-600 "><?php echo $salida ?></p>
                    <?php
                     endif;
                    ?>
                    
                </div>
                <div class="mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <input type="hidden" name="eliminarId" value="<?php echo $idEmpleado ?>">
                        <div class="grid gap-y-4">
                            <div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Identificador del empleado: </p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $idEmpleado?>.</p >
                            </div>
                            <div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Nombre del empleado: </p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $nombre?>.</p >
                            </div>
                            <div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Perfil del empleado</p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $perfil?>.</p >
                            </div><div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Email del empleado</p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $email?>.</p >
                            </div>
                            
                            <button
                                type="submit"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                                Eliminar
                            </button>


                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            ¿Quiere volver al cuadro de gestión?
                            <a
                                class="text-blue-600 decoration-2 hover:underline font-medium"
                                href="../areaPersonal/editarAdmin.php">
                                Volver
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html> 