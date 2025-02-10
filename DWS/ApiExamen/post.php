<?php

require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

$conexion = conectarPDO($host, $user, $password, $bbdd);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Transformamos el JSON de entrada de datos a un array asociativo
    $datos = json_decode(file_get_contents('php://input'), true);



    $mensajesError = []; //creamos contenedor de errores
    //Nos aseguramos que las variables que usaremos no queden undefined
    if (
        !isset($datos['usuario_id']) || !isset($datos['categoria_id']) ||
        !isset($datos['nombre']) || !isset($datos['descripcion']) ||
        !isset($datos['fecha_actividad']) || !isset($datos['aforo'])
    ) { //Si hay alguna undefined se crea este mensaje
        salidaDatos('Debe completar todos los parámetros de la petición JSON.' . PHP_EOL .
            'Los datos obligatorios son:' . PHP_EOL .
            'usuario_id.' . PHP_EOL .
            'categoria_id.' . PHP_EOL .
            'nombre.' . PHP_EOL .
            'descripcion.' . PHP_EOL .
            'fecha_actividad con formato YYYY-MM-DD HH:MM:SS' . PHP_EOL .
            'aforo.', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        exit;
    }

    //comprobar que el id pertenece a un ofertante
    $checkId = $conexion->prepare("SELECT o.id
                                    FROM ofertas o
                                    JOIN usuarios u ON o.usuario_id = u.id
                                    WHERE o.usuario_id = ? AND u.perfil_id = 3");
    $checkId->bindParam(1, $datos['usuario_id']);
    $checkId->execute();

    if ($checkId->rowCount() == 0) {
        $mensajesError[] = 'Identificador de ofertante inadecuado.';
    }

    //comprobar que el id pertenece a alguna categoría.

    $checkCategoria = $conexion->prepare("SELECT COUNT(*) FROM categorias WHERE id = ?");
    $checkCategoria->bindParam(1, $datos['categoria_id']);
    $checkCategoria->execute();
    $rdo = $checkCategoria->fetchColumn();
    $checkCategoria = null;

    //Cargo la lista de catgegorias para usarlas en el error
    $listaCategorias = $conexion->prepare("SELECT id, categoria FROM categorias");
    $listaCategorias->execute();


    while ($listado = $listaCategorias->fetch(PDO::FETCH_ASSOC)) {
        $registrosListado[] = $listado['id'] . " : " . $listado['categoria'] . PHP_EOL;
    }
    $listaCategorias = null;
    if ($rdo == 0) {
        $mensajesError[] =
            'Identificador de categoria inadecuado.' . PHP_EOL .
            'Las categorias adecuadas son:' . PHP_EOL
            . implode(PHP_EOL, $registrosListado) . '';
    }
    //nombre entre 5 y 256 caracteres.
    if (strlen(trim($datos['nombre'])) < 5 || strlen(trim($datos['nombre'])) > 256) {
        $mensajesError[] = 'La longitud del nombre de la actividad debe estar entre 5 y 256 caracteres.';
    }
    //nombre no exista
    $checkNombre = $conexion->prepare("SELECT nombre FROM ofertas WHERE nombre LIKE ?");
    $checkNombre->bindParam(1, $datos['nombre']);
    $checkNombre->execute();

    if ($checkNombre->rowCount() > 0) {
        $mensajesError[] = 'Ya existe una actividad con el nombre ' . $datos['nombre'] . '.';
    }
    //descripcion entre 20 y 256 caracteres.
    if (strlen(trim($datos['descripcion'])) < 20 || strlen(trim($datos['nombre'])) > 256) {
        $mensajesError[] = 'La longitud del nombre de la actividad debe estar entre 20 y 256 caracteres.';
    }
    //Alta de actividad al menos con una semana de antelación
    $antelación = (strtotime($datos['fecha_actividad']) - time()) / (60 * 60 * 24); //convierte el str a timestamp resta la 
    //fecha actual y divide para pasar de segundos a días
    if ($antelación < 7) {
        $mensajesError[] = 'La antelación para el alta de una actividad debe ser de al menos 7 días.';
    }

    if (count($mensajesError) == 0) {
        $alta = $conexion->prepare("INSERT 
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
        $alta->execute();
        $idAlta = $conexion->lastInsertId();
        $alta = null;
        if ($idAlta) {

            mail('gesto@emailcom', 'Nueva alta', $textoEmail, $headers);

            salidaDatos("Alta realizada con éxito en 24 horas será revisada y activada, id del alta = "
                . $idAlta . ".", array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
    } else {
        echo var_dump($mensajesError);
        $mensajeJson = implode(PHP_EOL, $mensajesError);
        salidaDatos(json_encode($mensajeJson), array('HTTP/1.1 400 Bad Request'));
    }
    //ejemplo Delete
    $id = $datos['id'];
    $delete = "DELETE FROM ofertas where id=:id";
    $consulta = $conexion->prepare($delete);
    $consulta->bindParam(':id', $id);
    $consulta->execute();
    if ($consulta->rowCount() > 0) {
        salidaDatos('Oferta borrada con éxito', array('HTTP/1.1 200 OK'));
    } else {
        salidaDatos('', array('HTTP/1.1 404 Not Found'));
        exit;
    }
}
salidaDatos('Error', array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
