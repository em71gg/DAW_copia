<?php

    // Definición de variables
	$host = "192.168.1.135";//casa
    //$host ="172.26.104.213";//instituto
	$user = "pepito01";
	$password = "pepito01";
	$bbdd = "tenistasdb";


    $database = [
        "host" => "192.168.1.135",//casa
        //"host" => "172.26.104.213",//instituto
        "username" => "pepito01",
        "password" => "pepito01",
        "db" => "tenistasdb"
    ];

    $headerJSON = 'Content-Type: application/json';
    $codigosHTTP = [ 
        "200" => "HTTP/1.1 200 OK",
        "201" => "HTTP/1.1 201 Created",
        "202" => "HTTP/1.1 202 Accepted",
        "400" => "HTTP/1.1 400 Bad Request",
        "404" => "HTTP/1.1 404 Not Found",
        "500" => "HTTP/1.1 500 Internal Server Error"
    ];

?>
