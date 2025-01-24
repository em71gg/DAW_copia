<!--
Mostrar la tabla de multiplicar del 2. Emplear el for,
luego el while y por ultimo el do/while.
-->

<?php
/*
<!--
Mostrar la tabla de multiplicar del 2. Emplear el for,
luego el while y por ultimo el do/while.
-->
*/
    $tabla =2;

    echo "La tabla del " . $tabla . " es:</br>".PHP_EOL;
    echo "bucle for:</br>".PHP_EOL;

    for($i =1; $i<11; $i++){
        echo $tabla ." x " . $i . " = " . ($tabla * $i) . "</br>".PHP_EOL;
    };

    echo "bucle while:</br>".PHP_EOL;
    $i =1;

    while($i<11){
        echo $tabla ." x " . $i . " = " . ($tabla * $i) . "</br>".PHP_EOL;
        $i++;
    };

    echo "bucle do-while:</br>".PHP_EOL;
    $i =1;
    do{
        echo $tabla ." x " . $i . " = " . ($tabla * $i) . "</br>".PHP_EOL;
        $i++;
    }while($i<11);
?>