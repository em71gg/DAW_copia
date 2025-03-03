<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');
 session_name('sesion-privada');
 session_start();
 if (!isset($_SESSION['id'])) header('location: ../inicio.php');
$flag = null;
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

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $conexion = conectarPDO($host, $user, $password, $bbdd);
  
    if(isset($_GET['accion'])){
        try{
            if($_GET['accion'] == 'borrar'){
                $eliminarSolicitud = $conexion -> prepare ("DELETE FROM solicitudes WHERE oferta_id = ? AND usuario_id = ?");
                $eliminarSolicitud ->bindParam(1, $_GET['ofertaId']);
                $eliminarSolicitud -> bindParam(2, $_GET['usuarioId']);
                $eliminarSolicitud -> execute();
    
                if($eliminarSolicitud -> rowCount() >0) echo "eliminado con éxito";
            }
    
            if($_GET['accion'] == 'insertar') {
                $insertarSolicitud = $conexion -> prepare("INSERT 
                                                    INTO solicitudes 
                                                    (oferta_id, usuario_id, fecha_solicitud, created_at, updated_at) 
                                                    VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP )
                                                    ");
                $insertarSolicitud -> bindParam(1, $_GET['ofertaId']);
                $insertarSolicitud -> bindParam(2, $_GET['usuarioId']);
                $insertarSolicitud -> execute();
                $mensajeId = (int)$conexion -> lastInsertId();
                if($mensajeId >0) {
                   
                    $salida = "Oferta registrada, será validada en breve";
                    echo $salida;
                }
            }
        }catch(PDOException $e){
            echo "Error al insertar el registro : " .$e -> getMessage();
        }finally{
            desconectarPDO($consulta, $conexion);
        }   
    }
}

$conexion = conectarPDO($host, $user, $password, $bbdd);
try{
    $consulta = $conexion->prepare('SELECT 
                                        o.id AS Identificador,
                                        o.usuario_id AS Usuario,    
                                        c.categoria AS Categoria,
                                        o.nombre AS Nombre,
                                        o.descripcion AS Descripcion,
                                        o.fecha_actividad AS Fecha, 
                                        o.aforo AS Aforo,
                                        o.visada AS visada,
                                        COUNT(s.oferta_id) AS Solicitudes,
                                        (o.aforo - COUNT(s.oferta_id)) As "Plazas Disponibles",
                                         GROUP_CONCAT(s.usuario_id) AS Usuarios
                                    FROM ofertas o 
                                    JOIN categorias c ON o.categoria_id=c.id
                                    LEFT JOIN solicitudes s ON o.id=s.oferta_id 
                                    WHERE o.visada=1 
                                        AND o.fecha_actividad > NOW() 
                                        
                                    GROUP BY o.id
                                    ORDER BY o.fecha_actividad DESC
                                    ');
//$consulta -> bindParam(1, $_SESSION['id']);
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
                        <div class='mallaDemandante'>
                            <div class='mallaBton'>
                                <button
                                    type="submit"
                                    class="logout-button"
                                    name="elegido"
                                    value="">
                                    Listar todos
                                </button>
                            </div>
                            <div class='mallaBton'>
                                <button
                                    type="submit"
                                    class="logout-button"
                                    name="elegido"
                                    value="noInscrito">
                                    Listar Sin inscribir
                                </button>
                            </div>
                            <div class="mallaBton">
                                <button
                                    type="submit"
                                    class="logout-button"
                                    name="elegido"
                                    value="inscrito">
                                    Listar propias
                                </button>
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
                        <?php
                            
                        ?>
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
                                    foreach ($rdo as $campo) { 
                                        $usuarios = !empty($campo['Usuarios']) ? explode(',', $campo['Usuarios']) : [];
                                        if ($flag == "inscrito") {
                                            if(!in_array($_SESSION['id'], $usuarios)) continue;
                                        }
                                        if ($flag == "noInscrito") {
                                            if(in_array($_SESSION['id'], $usuarios)) continue;
                                        }      
                                        echo "<tr>
                                        <td>" . $campo['Nombre'] . "</td>
                                        <td>" . $campo['Fecha'] . "</td>
                                        <td>" . $campo['Aforo'] . "</td>
                                        <td>" . $campo['Solicitudes'] . "</td>
                                        <td>" . $campo['Identificador'] . "</td>
                                        <td>" . $campo['Categoria'] . "</td>
                                        <td>" . $campo['Descripcion'] . "</td>";
                                        if($flag == "inscrito"  && in_array($_SESSION['id'], $usuarios)){
                                            echo "<td><a id='linkTabla' href='" . htmlspecialchars($_SERVER['PHP_SELF']) ."?accion=borrar&ofertaId="
                                            .$campo['Identificador']."&usuarioId=".$_SESSION['id']."'>Desincribirse</a></td>" ;
                                        }
                                        elseif($flag == "noInscrito" && !in_array($_SESSION['id'], $usuarios)){
                                            echo "<td><a id='linkTabla' href='" . htmlspecialchars($_SERVER['PHP_SELF']) ."?accion=insertar&ofertaId="
                                            .$campo['Identificador']."&usuarioId=".$_SESSION['id']."'>Incribirse</a></td>" ;
                                        }
                                        else{
                                            echo "<td>";
                                            if (!in_array($_SESSION['id'], $usuarios))
                                            echo
                                            "<a id='linkTabla' href='" . htmlspecialchars($_SERVER['PHP_SELF']) ."?accion=insertar&ofertaId="
                                            .$campo['Identificador']."&usuarioId=".$_SESSION['id']."'>Incribirse</a>";
                                            if (in_array($_SESSION['id'], $usuarios))
                                            echo
                                            "<a id='linkTabla' href='" . htmlspecialchars($_SERVER['PHP_SELF']) ."?accion=borrar&ofertaId="
                                            .$campo['Identificador']."&usuarioId=".$_SESSION['id']."'>Desincribirse</a>
                                            </td>" ;
                                        }
                                        echo "</tr>" . PHP_EOL;
                                    }                                
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

</html>