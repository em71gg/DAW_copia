<?php
$n = rand(5,15);
for ($i =0; $i < $n; $i++){
    $bolas []= rand(1,10); 
}

function mostrarBolas ($array) {
    return implode(' ' , array_map(fn($num) => "&#" . (10101 +$num). ";", $array));
}

//Mostr<r grupo inicial
$bolas_sin_repetir = array_unique($bolas);
echo "GRupo inicial: " .mostrarBolas($bolas) . "</br>";
echo "Bolas sin repetir: " . mostrarBolas($bolas_sin_repetir);
?>