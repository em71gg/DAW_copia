<!--Dado un url, se desea extraer los datos del protocolo, el nombre de dominio, la ruta de acceso 
u otro componente significativo. 
-->

<?php
    $url = "https://www.php.net/manual/es/function.strlen.php";

    echo "La longitud del url es de: " . strlen($url) . " caracteres." . "</br>".PHP_EOL;

    /*$splitted = str_split($url);
    $paso1 =  array_slice($splitted, 0, 4);
    echo implode($paso1). "</br>";
    echo print_r($splitted);
*/
    $splittedUrl = str_split($url);
    //echo "str_split: " . $splittedUrl;

    for($i = 0; $i < count($splittedUrl); $i++){
        if($splittedUrl[$i] =="/"){
            $tramo= array_slice($splittedUrl, 0, $i);
            //echo " slice $i: " . print_r($tramo);
            $cadena = trim(implode($tramo), "/:");
            echo "Loop $i: " .$cadena . "</br>".PHP_EOL;
            echo implode($splittedUrl). "</br>".PHP_EOL;;
            $splittedUrl = array_slice($splittedUrl, $i);
            //echo implode($splittedUrl). "</br>".PHP_EOL;
        }
    }
?>

