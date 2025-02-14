<?php

require_once('./utiles/funciones.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nombre = obtenerValorCampo('nombre');
    $apellidos = obtenerValorCampo('apellidos');
    $edad = obtenerValorCampo('edad');
    $email = obtenerValorCampo('email');
    $peso = obtenerValorCampo('peso');
    $errores =[];

    $nombreMin = 4;//longitud mínioma del nombre
    $nombreMax =120;
    if(!empty($nombre)){
        //se hacen validaciones
        if(!validarLongitudCadena($nombre, $nombreMin, $nombreMax)) {
            $errores[]= "Su monbre debe tener al menos 4 caracteres";
        }
    }
    else{
        $errores[] = "Debe introducir el nombre";
    }

    if(!empty($apellidos)){
        //validaciones
    }else{
        $errores[] = "Debe introducir apellidos";
    }
    if(!empty($email)){
        //validaciones
        if(!validarEmail($email)){
            $errores[]= "debeintroducir un email válido.";
        }
    }else{
        $errores[]= "debeintroducir su email";
    }
    if(!isset($_POST['sexo'])){
        $errores[]= "debe indicar su sexo";
    }
    if(!empty($edad)){
        //validaciones
        if(!validarEnteroPositivo($edad)){
            $errores[] = "Su edad debe ser un número entero";
        } 
    }else{
        $errores[] = "Debe introducir su edad";
    }
    if(!isset($_POST['intereses'])){
        $errores[]="debe indicar algun interes";
    }
    if(!isset($_POST['estado'])){
        $errores[] = "Debe indicar su estado";
    }

    if(count($errores)==0){

        echo "Su nombre es $nombre.</br>".PHP_EOL;
        echo "Su apellido es $apellidos.</br>".PHP_EOL;
        echo "Su sexo es " . $_POST['sexo'].".</br>".PHP_EOL;
        echo "Su peso es $peso.</br>" .PHP_EOL;
        echo "Su edad es $edad.</br>".PHP_EOL;
        echo "Su estado es " . $_POST['estado']. ".</br>".PHP_EOL;
        echo "Sus intereses son " . implode(", ", $_POST['intereses']) . ".";


    }else{
        echo implode("</br>", $errores);
    }

}else{
    header('location: formulario1.php');
}
?>