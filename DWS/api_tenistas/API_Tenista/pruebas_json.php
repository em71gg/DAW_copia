<?php
$salidaArray ='{
    "Id": 1,
    "Nombre": "Las Jugones",
    "Edad mínima": 10,
    "Nombre del deporte": "Futbol",
    "Número de jugadores del deporte": 11,
    "Alumnos": [
        [
            "Mónica",
            "Fernández Paniagua",
            11
        ],
        [
            "Beatriz",
            "Fernández León",
            12
        ],
        [
            "Rosario",
            "Pérez Alonso",
            10
        ],
        [
            "Carmen",
            "García Moran",
            10
        ],
        [
            "Nuria",
            "Pastor Meneses",
            10
        ],
        [
            "Francisca",
            "López Barroso",
            10
        ],
        [
            "Estibaliz",
            "Mirón Márquez",
            12
        ],
        [
            "Ana María",
            "Tapia Valera",
            10
        ],
        [
            "Francisca",
            "Linares Martí",
            10
        ],
        [
            "Julia",
            "Jiménez Fernández",
            11
        ],
        [
            "Beatriz",
            "Cabrera García",
            11
        ]
    ]
}';

$salidaJson = '{
    "Id": 1,
    "Nombre": "Las Jugones",
    "Edad mínima": 10,
    "Nombre del deporte": "Futbol",
    "Número de jugadores del deporte": 11,
    "Alumnos": [
        {
            "nombre": "Mónica",
            "apellidos": "Fernández Paniagua",
            "edad": 11
        },
        {
            "nombre": "Beatriz",
            "apellidos": "Fernández León",
            "edad": 12
        },
        {
            "nombre": "Rosario",
            "apellidos": "Pérez Alonso",
            "edad": 10
        },
        {
            "nombre": "Carmen",
            "apellidos": "García Moran",
            "edad": 10
        },
        {
            "nombre": "Nuria",
            "apellidos": "Pastor Meneses",
            "edad": 10
        },
        {
            "nombre": "Francisca",
            "apellidos": "López Barroso",
            "edad": 10
        },
        {
            "nombre": "Estibaliz",
            "apellidos": "Mirón Márquez",
            "edad": 12
        },
        {
            "nombre": "Ana María",
            "apellidos": "Tapia Valera",
            "edad": 10
        },
        {
            "nombre": "Francisca",
            "apellidos": "Linares Martí",
            "edad": 10
        },
        {
            "nombre": "Julia",
            "apellidos": "Jiménez Fernández",
            "edad": 11
        },
        {
            "nombre": "Beatriz",
            "apellidos": "Cabrera García",
            "edad": 11
        }
    ]
}';

$salidaArrayPHP = json_decode($salidaArray);
$salidaJsonPHP = json_decode($salidaJson);
echo "<pre>";
echo print_r($salidaArrayPHP);
echo "</pre>";
echo "</br>";

echo "<pre>";
echo print_r($salidaJsonPHP);
echo "</pre>";
?>