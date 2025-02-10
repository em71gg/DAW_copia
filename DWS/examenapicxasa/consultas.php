<?php

//Consulta con array
$ofertasOfertante = $conexion->prepare("SELECT 
usuario_id,
if(count(id)>0,
JSON_ARRAYAGG(
    JSON_OBJECT(
            'nombre', nombre,
            'descripcion', descripcion,
            'Fecha', fecha_actividad
        )
),
'[]') as oferta


FROM ofertas where usuario_id=?");
$ofertasOfertante->bindParam(1, $id_usuario);
$ofertasOfertante->execute();
$datosOfertante = $ofertasOfertante->fetch(PDO::FETCH_ASSOC);
$datosOfertante['oferta'] = json_decode($datosOfertante['oferta'], true);//paso el string delarra a array

salidaDatos(
    json_encode($datosOfertante),
    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
);


//Consulta con array o json object
if (isset($_GET['id'])) {
    //Mostrar un mensaje
    $select = " SELECT
            
            e.id AS Id,
            e.nombre AS Nombre,
            e.edad_minima AS `Edad mínima`,
            d.nombre AS `Nombre del deporte`,
            d.numero_jugadores AS `Número de jugadores del deporte`,
            IF(
                COUNT(a.id) > 0, 
                JSON_ARRAYAGG(
                /*Si quiero salida array*/
                    /*JSON_ARRAY(
                        a.nombre,
                        a.apellidos,
                        a.edad
                    )*/
                    /*si quiero salida json object*/
                    JSON_OBJECT(
                        'nombre', a.nombre,
                        'apellidos', a.apellidos,
                        'edad', a.edad
                    )
                ),
                '[]'
            ) AS Alumnos
        FROM 
            equipos e
        LEFT JOIN 
            deportes d ON e.deporte_id = d.id
        LEFT JOIN 
            equipos_alumnos ea ON e.id = ea.equipo_id
        LEFT JOIN 
            alumnos a ON ea.alumno_id = a.id
        WHERE 
            e.id = :id
        GROUP BY 
            e.id
            ";


    $consulta = $conexion->prepare($select);
    $consulta->bindParam(':id', $_GET['id']);
    $consulta->execute();
    if ($consulta->rowCount() > 0) {
        $datosConsulta = $consulta->fetch(PDO::FETCH_ASSOC);
        $datosConsulta['Alumnos'] = json_decode($datosConsulta['Alumnos'], true);
        salidaDatos(
            json_encode($datosConsulta),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    } else {
        salidaDatos('', array('HTTP/1.1 404 Not Found'));
    }

    //consulta con county coalesce
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
    //ejemplo insert
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


    //ejemplo update
    $ofertaId = $datos['id'];

    $campos = getParams($datos);
    $update = "UPDATE ofertas SET $campos, visada =0 WHERE id=:id";
    $consulta = $conexion->prepare($update);
    bindAllParams($consulta, $datos);
    $consulta->execute();

    if ($consulta->rowCount() == 0) {
        salidaDatos(
            'No se realizaron los cambios.',
            array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request')
        );
        exit;
    }
    salidaDatos('Petición de actualizacion realizada con éxito, en 24 horas será activada.', array('HTTP/1.1 200 OK'));
}
