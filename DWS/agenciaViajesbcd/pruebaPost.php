<?php
require_once('./utiles/funciones.php');
require_once('./utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);



if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents('php://input'), true);
    if(!isset($datos['usuario_id']) || !isset($datos['categoria_id']) || 
    !isset($datos['nombre']) || !isset($datos['descripcion']) || !isset($datos['fecha_actividad'])){
        salidaDatos('Debe completar todos los datos de la petición. Los datos obligatorios son:' .PHP_EOL.
                        'usuario_id.' . PHP_EOL .
                        'categoria_id.' . PHP_EOL .
                        'nombre.' . PHP_EOL .
                        'descripcion.' . PHP_EOL .
                        'fecha_actividad con formato YYYY-MM-DD HH:MM:SS' ,
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
    salidaDatos("ok parametros, 'HTTP/1.1 200 OK'");

}



//En caso de que ninguna de las opciones anteriores se haya ejecutado
salidaDatos('Error', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
?>