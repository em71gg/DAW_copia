<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $n = count($_REQUEST);
    $p =1;
    echo "Se han contestado $n de " . $_SESSION['preguntas'] . " preguntas con las siguientes respuestas.</br>";
    foreach($_REQUEST as $respuesta){
        echo "La respuesta a la pregunta $p es : " . $respuesta . "</br>";
        $p++;
    }
}else{header('location: encuesta2.php');
    }
?>