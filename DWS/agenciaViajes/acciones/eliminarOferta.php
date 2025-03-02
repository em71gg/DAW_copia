<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "id" i a session idOferta, en caso contrario vuelve a la página de login
if (!isset($_SESSION["id"])) header("Location: ../inicio.php");
if(!isset($_SESSION['idOferta'])) header("location: ../inicio.php");
if($_SESSION['idOferta'] == null) $errores['Error con el id de la oferta'];
$id = $_SESSION['id'];
$idOferta = $_SESSION['idOferta'];
$errores = [];
$nombre = null;
$categoria = null;
$descripcion = null;
$fecha = null;
$fechaFormateada = null;
$aforo = null;
$salida = null;


if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    try {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $getOferta = $conexion -> prepare('SELECT
                                            o.nombre AS Nombre,
                                            o.descripcion AS Descripcion,
                                            o.fecha_actividad AS Fecha,
                                            o.aforo AS Aforo,
                                            c.id As Categoria
                                            FROM ofertas o
                                            JOIN categorias c ON o.categoria_id=c.id
                                            WHERE o.id = ?');
        $getOferta -> bindParam(1, $_SESSION['idOferta']);
        $getOferta -> execute();

        if($getOferta -> rowCount() > 0) {
            /*desconectarPDO($consulta, $conexion);
            header('location: ../areaPersonal/ofertante');
            exit();*/
            $registro = $getOferta -> fetch(PDO::FETCH_ASSOC);
            $nombre = $registro['Nombre'];
            $categoria = $registro['Categoria'];
            $descripcion = $registro['Descripcion'];
            $fecha = $registro['Fecha'];
            
            $aforo = $registro['Aforo'];
            if ($fecha) {
                $fechaFormateada = (new DateTime($fecha))->format('Y-m-d\TH:i');
            }else{
                $fechaFormateada = '';
            }
        }
    }catch (PDOException $e) {}finally {}
    

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conexion = conectarPDO($host, $user, $password, $bbdd);
        $eliminarOferta = $conexion -> prepare ('DELETE
                                                    FROM ofertas
                                                    WHERE id = ?
                                                ');
        $eliminarOferta -> bindParam(1, $idOferta);
        $eliminarOferta -> execute();

        if($eliminarOferta -> rowCount() >0) {
            $salida = "Oferta eliminada";
            header('Refresh: 3; url=../areaPersonal/ofertante.php');
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
                        Confirme que desea eliminar la oferta siquiente
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
                        <input type="hidden" name="eliminarId" value="<?php echo $idOferta ?>">
                        <div class="grid gap-y-4">
                            <div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Identificador de la actividad: </p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $idOferta?>.</p >
                            </div>
                            <div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Nombre de la actividad: </p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $nombre?>.</p >
                            </div>
                            <div>
                                <p class="block text-sm font-bold ml-1 mb-2 dark:text-white">Fecha de la actividad</p>
                                <p class="block text-sm ml-1 mb-2 dark:text-white"><?php echo $fecha?>.</p >
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
                            ¿Quiere volver a la página personal?
                            <a
                                class="text-blue-600 decoration-2 hover:underline font-medium"
                                href="../areaPersonal/ofertante.php">
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