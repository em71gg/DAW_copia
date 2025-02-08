<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);


if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    if (isset($_GET['id'])) {//consulta para obtener una oferta filtrada por id
        
        $consulta = $conexion->prepare("SELECT * FROM ofertas where id=:id");
        $consulta->bindParam(':id', $_GET['id']);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
            salidaDatos(
                json_encode($consulta->fetch(PDO::FETCH_ASSOC)),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            salidaDatos('Error', array('HTTP/1.1 404 Not Found'));
        }
    } 
    elseif (isset($_GET['categoria'])) { //consulta para obtener un listado de ofertas por nombre de la categoría.
        $consulta = $conexion->prepare("SELECT 
                                                o.nombre, o.descripcion, o.fecha_actividad
                                            FROM ofertas o
                                            JOIN categorias c ON o.categoria_id = c.id
                                            WHERE c.categoria = ?");
        $consulta->bindParam(1, $_GET['categoria']);
        $consulta->execute();
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    } 
    elseif(isset($_GET['disponible'])){//consulta para obtener las ofertas con plazas disponibles

        $consulta = $conexion->prepare("SELECT 
                                            o.nombre,
                                            o.fecha_actividad,
                                            o.aforo - COALESCE(COUNT(s.oferta_id), 0) AS plazas_disponibles
                                        FROM ofertas o
                                        LEFT JOIN solicitudes s ON o.id = s.oferta_id/*lef join para que me rescate también las ofertas que no tengan coincidencia en solicitud, osea las que tienen todas las plazas libres porque no ha habido solicitudes todavía*/
                                        GROUP BY o.nombre
                                        HAVING plazas_disponibles > 0");
        
        $consulta->execute();
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }
    elseif(isset($_GET['usuario_id'])){//consulta para rescatar las ofertas por ofertante
        $id_usuario = $_GET['usuario_id'];
        //comprobar que el id pertenece a un ofertante
        $checkId = $conexion -> prepare("SELECT o.id
                                        FROM ofertas o
                                        JOIN usuarios u ON o.usuario_id = u.id
                                        WHERE o.usuario_id = ? AND u.perfil_id = 3");
        $checkId -> bindParam(1, $id_usuario);
        $checkId -> execute();

        if($checkId->rowCount() == 0) {
            salidaDatos('Identificador de usuario inadecuado.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            exit;
        }
        $ofertasOfertante = $conexion -> prepare ("SELECT * FROM ofertas where usuario_id=?");
        $ofertasOfertante -> bindParam(1, $id_usuario);
        $ofertasOfertante -> execute();
        while ($registro = $ofertasOfertante->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

        }
    else {//Consulta para mostrar todas las ofertas

        $consulta = $conexion->prepare("SELECT * FROM ofertas");
        $consulta->execute();
        $registros = [];
        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $registros[] = $registro;
        }
        salidaDatos(
            json_encode($registros),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents('php://input'), true);
    if(!isset($datos['usuario_id']) || !isset($datos['categoria_id']) || 
    !isset($datos['nombre']) || !isset($datos['descripcion']) || 
    !isset($datos['fecha_actividad']) || !isset($datos['aforo'])){
        salidaDatos('Debe completar todos los parámetros de la petición JSON.' . PHP_EOL . 
                        'Los datos obligatorios son:' .PHP_EOL.
                        'usuario_id.' . PHP_EOL .
                        'categoria_id.' . PHP_EOL .
                        'nombre.' . PHP_EOL .
                        'descripcion.' . PHP_EOL .
                        'fecha_actividad con formato YYYY-MM-DD HH:MM:SS'. PHP_EOL .
                        'aforo.',
                        array('HTTP/1.1 400 Bad Request'));
        exit;
    }

    //comprobar que el id pertenece a un ofertante
    $checkId = $conexion -> prepare("SELECT o.id
                                    FROM ofertas o
                                    JOIN usuarios u ON o.usuario_id = u.id
                                    WHERE o.usuario_id = ? AND u.perfil_id = 3");
    $checkId -> bindParam(1, $datos['usuario_id']);
    $checkId -> execute();

    if ($checkId -> rowCount() == 0) {
        salidaDatos('Identificador de ofertante inadecuado.',
                        array('HTTP/1.1 400 Bad Request'));
        exit;
    }

    //comprobar que el id pertenece a alguna categoría.

    $checkCategoria = $conexion -> prepare ("SELECT COUNT(*) FROM categorias WHERE id = ?");
    $checkCategoria -> bindParam(1, $datos['categoria_id']);
    $checkCategoria -> execute();
    $rdo = $checkCategoria -> fetchColumn();
    $checkCategoria = null;
    //$listaCategorias = $conexion -> prepare("SELECT * FROM categorias");
    $listaCategorias = $conexion -> prepare ("SELECT id, categoria FROM categorias");
    $listaCategorias ->execute();
    

    while ($listado = $listaCategorias -> fetch(PDO::FETCH_ASSOC)){
        $registrosListado[] = $listado['id'] . " : " . $listado['categoria'].PHP_EOL;
    }
    $listaCategorias = null;
    if( $rdo == 0) {
        salidaDatos('Identificador de categoria inadecuado.' .PHP_EOL .
                       'Las categorias adecuadas son:' .PHP_EOL
                            .implode(PHP_EOL, $registrosListado) .'',
                        array('HTTP/1.1 400 Bad Request'));
        exit;
    }

    //nombre entre 5 y 256 caracteres.
    if (strlen(trim($datos['nombre'])) <5 || strlen(trim($datos['nombre'])) >256){
        salidaDatos('La ongitud del nombre de la actividad debe estar entre 5 y 256 caracteres.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }

    //nombre no exista
    $checkNombre = $conexion -> prepare ("SELECT nombre FROM ofertas WHERE nombre LIKE ?");
    $checkNombre -> bindParam(1, $datos['nombre']);
    $checkNombre -> execute();

    if($checkNombre -> rowCount() >0) {
        salidaDatos('Ya existe una actividad con el nombre ' . $datos['nombre'] . '.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }
    //descripcion entre 20 y 256 caracteres.
    if (strlen(trim($datos['descripcion'])) <20 || strlen(trim($datos['nombre'])) >256){
        salidaDatos('La longitud del nombre de la actividad debe estar entre 20 y 256 caracteres.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }
    //Alta de actividad al menos con una semana de antelación
    $antelación = (strtotime($datos['fecha_actividad']) - time()) / ( 60 * 60 * 24); //convierte el str a timestamp resta la 
                                                                                    //fecha actual y divide para pasar de segundos a días
    if ($antelación < 7) {
        salidaDatos('La antelación para el alta de una actividad debe ser de al menos 7 días.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    } 
    
    $alta = $conexion -> prepare ("INSERT 
                                    INTO ofertas 
                                    (usuario_id, 
                                    categoria_id,
                                    nombre, 
                                    descripcion,
                                    fecha_actividad, 
                                    aforo, 
                                    visada,
                                    created_at,
                                    updated_at ) 
                                    VALUES
                                    (:usuario_id, 
                                    :categoria_id, :nombre, 
                                    :descripcion,
                                    :fecha_actividad, 
                                    :aforo,
                                    0,
                                    CURRENT_TIMESTAMP, 
                                    CURRENT_TIMESTAMP)");
    bindAllParams($alta, $datos);
    $alta -> execute();
    $idAlta = $conexion -> lastInsertId();
    $alta= null;
    if($idAlta) {
        //enviar correo para activar el alta

        /* Envío De Email Con id de la actividad */
            // Cabecera
            $headers = [
                "From" => "dwes@php.com",
                "Content-type" => "text/plain; charset=utf-8"
            ];
            // Variables para el email
            //$passEncode = urlencode($pass2);
            $idAltaEncode = urlencode($idAlta);
            
            // Texto del email
            $textoEmail = "
                    Hola!\n 
                    Se ha recibido un nuevo alta que debe activar, revise y active puede usar este link o hacerlo manualmente:\n
                    http://localhost:3000/DWS/bbdd_rol_usuario_formated/restablecer/linkConfirmación.php?id=$idAltaEncode&activada=1 \n
                    ";
            // Envio del email
            mail('gesto@emailcom', 'Nueva alta', $textoEmail, $headers);
            
        salidaDatos("Alta realizada con éxito en 24 horas será revisada y activada, id del alta = " 
        . $idAlta . ". , 'HTTP/1.1 200 OK'");
    }
    //salidaDatos("ok parametros, 'HTTP/1.1 200 OK'");

}
if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
}
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('Error', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>