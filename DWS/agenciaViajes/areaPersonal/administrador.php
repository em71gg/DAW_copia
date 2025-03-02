<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');
 session_name('sesion-privada');
 session_start();
 if (!isset($_SESSION['id'])) header('location: ../inicio.php');
$flag =null;
 if (!isset($_SESSION['id']) || !isset($_SESSION['perfil_id'])) {
    session_unset();
    session_destroy();
    //echo "no hay variables de sesion";
    header('location: ../inicio.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flag = $_POST['elegido'];
}

$conexion = conectarPDO($host, $user, $password, $bbdd);
try{
    $consulta = $conexion->prepare("SELECT 
    o.id AS Identificador,    
    c.categoria AS Categoria,
    o.nombre AS Nombre,
    o.descripcion AS Descripcion,
    o.fecha_actividad AS Fecha, 
    o.aforo AS Aforo,
    o.visada AS visada,
    COUNT(s.oferta_id) AS Solicitudes
    FROM ofertas o 
    JOIN categorias c ON o.categoria_id=c.id
    LEFT JOIN solicitudes s ON o.id=s.oferta_id 
    WHERE /*o.visada=1 
        AND o.fecha_actividad< NOW() 
        AND*/ o.usuario_id= ?
    GROUP BY o.id
    ORDER BY o.fecha_actividad DESC
");
$consulta -> bindParam(1, $_SESSION['id']);
$consulta->execute();

$rdo = $consulta->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {
    echo "ERROR: " . $e -> getMessage();
}
finally {
    desconectarPDO($consulta, $conexion);
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="../scripts/despliegues.js"></script>
</head>

<body>
    <header>
        <div class="header-bg">
            <div class="header-container">
                <div>
                    <?php
                    echo"<p>La variable \$_SESSION['id'] es = ". $_SESSION['id'] . "</p>";
                    echo"<p>La variable \$_SESSION['perfil_id'] es = ". $_SESSION['perfil_id'] . "</p>";
                    ?>
                </div>
                <div>
                    <p class="header-title">Agencia de viajes</p>
                </div>
                <div id="options-header">
                    
                        <div class="logout-button-container">
                            <a class="fcc-btn" href="../acceso/cerrarSesion.php">Cerrar sesión</a>  
                        </div>
                       
                </div>
            </div>
        </div>
    </header>

    <main id="content" role="main">
        <div class="table-container">
            <div class="info-table">
                <div class="text-center">
                    <h1 class="title">Elige opciones</h1>
                </div>
                <div class='buttons'>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                        <div class='malla'>
                            <div class='mallaBton'>
                                <button
                                    type="submit"
                                    class="logout-button"
                                    name="elegido"
                                    value="null">
                                    Listar todos
                                </button>
                            </div>
                            <div class='mallaBton'>
                                <button
                                    type="submit"
                                    class="logout-button"
                                    name="elegido"
                                    value="inactivos">
                                    Listar inactivos
                                </button>
                            </div>
                            <div class='mallaBton'>
                                <button
                                    type="submit"
                                    class="logout-button"
                                    name="elegido"
                                    value="activos">
                                    Listar activos
                                </button>
                            </div> 
                            <div class="mallaBton">
                                <div class="logout-button">
                                    <a id="linkSin"  href="../acciones/altaOferta.php">Nueva Actividad</a>  
                                </div>
                            </div>
                        </div>
                            
                    </form>
                </div>

            </div>
        </div>
        
        <div class="table-container">
            <div class="info-table">
                <div class="text-center">
                    <h1 class="title">Listado de actividades activas</h1>
                </div>
                <div id="div-table">
                    <table id="customers">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Aforo</th>
                                <th>Solicitudes</th>
                                <th>Identificador</th>
                                <th>Categoria</th>
                                <th>Descripción de la actividad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if(empty($rdo)){
                                echo "<tr><td colspan='7' id='noActivo'> No hay actividades activas</td></tr>";
                            }
                            else{
                               // if ($flag == "todos" || $flag == null){
                                    foreach ($rdo as $campo) {
                                        if ($flag == "activos"){
                                            if ($campo['visada'] == 0 || strtotime($campo['Fecha']) < time()) continue;
                                        }
                                        if ($flag == "inactivos") {
                                            if($campo['visada'] == 1) continue;
                                        }
                                        echo "<tr>
                                        <td>" . $campo['Nombre'] . "</td>
                                        <td>" . $campo['Fecha'] . "</td>
                                        <td>" . $campo['Aforo'] . "</td>
                                        <td>" . $campo['Solicitudes'] . "</td>
                                        <td>" . $campo['Identificador'] . "</td>
                                        <td>" . $campo['Categoria'] . "</td>
                                        <td>" . $campo['Descripcion'] . "</td>
                                        <td> <a id='linkTabla' href='editarOfertante.php?estado={$campo['visada']}&idOferta={$campo['Identificador']}'>Editar</td>
                                    </tr>" . PHP_EOL;
                                    }
                                //}
                                
                            }
                            
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8"></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>

        </div>
    </main>
</body>

</html><p>Privado</p>