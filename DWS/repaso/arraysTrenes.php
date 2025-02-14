<?php
$puntuacion = [1,2,3,4,5,6];
$suma =0;
$dos=0;
for ($i = 1; $i <=100; $i ++){
    
    $tirada = array_rand($puntuacion);
    if($tirada == 2) $dos ++;
    $suma += $tirada;
    echo "Tirada nº $i, puntuación = $tirada, meida de las tiradas = "
    . number_format($suma / $i, 2) . ", número de veces que ha salido el dos = $dos.</br>".PHP_EOL;
}

?>