<?php
$a = [];
$b = [];
$c = [];

$n = 10;

for ($i = 0 ; $i<10; $i++) {
    $rana = rand(0,1);
    $a[$i] = $rana;
    $ranb = rand(0,1);
    $b[$i] = $ranb;
    $ranc = rand(0,1);
    $c[$i] = $ranc;
}


$r = [];
$and = [];
$secq = [];

for ($i = 0; $i <$n; $i ++) {
    $r[$i] = ($a[$i] + $b[$i] + $c[$i]) >1 ? 1: 0;//bit mas comun
    $and[$i] = $b[$i] == 1 && $c[$i] == 1 ? 1 : 0;//andif
    if($i < $n-1) $secq[$i] = $c[$i] == $c[$i+1] ? 0 : 1;//cambio de bit
  
}
$arraysMas = [$a, $b, $c, $r];
$cabecerasMas = ["A" , 'B', 'C', 'R'];

echo "Rdo más común</br>";
for ($i = 0; $i <count($cabecerasMas) ; $i ++){
    echo $cabecerasMas[$i]. ": " . implode(", ", $arraysMas[$i]). "</br>";
    
}

echo "Operacion And</br>";
$arraysAnd = [$b, $c, $and];
$cabecerasAnd = ['B', 'C', 'R'];
for ($i = 0; $i <count($cabecerasAnd) ; $i ++){
    echo $cabecerasAnd[$i]. ": " .implode(", ", $arraysAnd[$i]) . "</br>";
    
}

echo "CAmbio de bit</br>";
$arraysCAm = [ $c, $secq];
$cabecerasCAm = ["C" ,'R'];
for ($i = 0; $i <count($cabecerasCAm) ; $i ++){
    echo $cabecerasCAm[$i]. ": " .implode(", ", $arraysCAm[$i]) . "</br>";
    
}


?>