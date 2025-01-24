<?php

use function PHPSTORM_META\type;

$arrayLetters = ["a" => 'cara'];

for ($i = "b" ; $i <"z"; $i ++){
    $arrayLetters[] = $i;
};

echo "<pre>" . print_r($arrayLetters) . "</pre>";

$arrayB = ["item" => 'cajon'];

$arrayC = $arrayLetters + $arrayB;

echo "<pre>" . print_r($arrayC) . "</pre>";

$_PHP_SELF;

$numero = 122.34567;

$numeroR = round($numero, 2);
$numero_r =  round($numero, -1);

echo "numero redondeado normal: $numeroR </br>";
echo "numero redondeado -normal: $numero_r </br>";

echo " valor de getrandmax(): " . getrandmax() . "</br>";
echo " valor de mt_getrandmax(): " . mt_getrandmax() . "</br>";
echo "valor aleatorio:" . rand() . "</br>";
$difLettersC = array_diff($arrayC, $arrayLetters);
echo gettype($difLettersC) . "</br>";
echo "diferencia entre arrayLeters y arrayC: " . print_r($difLettersC, true) . "</br>";
echo "diferencia entre arrayLeters y arrayC con var_dump : <pre>" . var_dump($difLettersC) . "</pre></br>";
?>