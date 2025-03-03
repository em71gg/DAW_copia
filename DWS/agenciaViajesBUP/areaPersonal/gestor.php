<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');
 session_name('sesion-privada');
 session_start();
 if (!isset($_SESSION['id'])) header('location: ../inicio.php');

 if (!isset($_SESSION['id']) || !isset($_SESSION['perfil_id'])) {
    session_unset();
    session_destroy();
    //echo "no hay variables de sesion";
    header('location: ../inicio.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conexion = conectarPDO($host, $user, $password, $bbdd);
    $id = isset($_GET['ofertaID']) ? $_GET['ofertaID'] : null;
    $visada= 1;
    try {
        $visar = $conexion ->prepare ('UPDATE 
                                            ofertas
                                        SET
                                            visada= ?
                                        WHERE id = ?                                    
                                    ');
        $visar -> bindParam(1, $visada);
        $visar -> bindParam(2, $id);
        $visar -> execute();

        if($visar -> rowCount()>0) {
            $salida = "Oferta Validada";
            echo "Enviar mensaje a cliente";
        }

    }catch (PDOException $e) {
        echo "error: " .$e -> getMessage();
    } finally{
        desconectarPDO($visar, $conexion);
    }
}

$conexion = conectarPDO($host, $user, $password, $bbdd);
try{
    $consulta = $conexion->prepare('SELECT 
    o.id AS ID,
    o.nombre AS Nombre,
    c.categoria AS Categoria,
    o.descripcion AS Descripcion,
    o.fecha_actividad AS Fecha,
    o.aforo AS Aforo,
    u.nombre AS Usuario,
    o.created_at AS Creada,
    o.updated_at AS Actualizada
    FROM ofertas o
    LEFT JOIN usuarios u ON o.usuario_id = u.id
    LEFT JOIN categorias c ON o.categoria_id = c.id
        
    WHERE o.visada=0 
    ORDER BY fecha_actividad ASC
');

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
                    <h1 class="title">Listado de actividades pendientes de visar</h1>
                </div>
                <div id="div-table">
                    <table id="customers">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Descripción de la actividad</th>
                                <th>Fecha</th>
                                <th>Aforo</th>
                                <th>Usuario</th>
                                <th>Creada</th>
                                <th>Actualizada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if(empty($rdo)){
                                echo "<tr><td colspan='7' id='noActivo'> No hay actividades activas</td></tr>";
                            }
                            else{
                               
                                    foreach ($rdo as $campo) {
                                       
                                        echo "<tr>
                                        <td>" . $campo['ID'] . "</td>
                                        <td>" . $campo['Nombre'] . "</td>
                                        <td>" . $campo['Categoria'] . "</td>
                                        <td>" . $campo['Descripcion'] . "</td>
                                        <td>" . $campo['Fecha'] . "</td>
                                        <td>" . $campo['Aforo'] . "</td>
                                        <td>" . $campo['Usuario'] . "</td>
                                        <td>" . $campo['Creada'] . "</td>
                                        <td>" . $campo['Actualizada'] . "</td>
                                        <td> <a id='linkTabla' href='" . htmlspecialchars($_SERVER['PHP_SELF']) ."?accion=visar&ofertaID={$campo['ID']}'>Visar</td>
                                    </tr>" . PHP_EOL;
                                    }
                                //}
                                
                            }
                            
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10"></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>

        </div>
    </main>
</body>

</html>