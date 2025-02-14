<?php
    $tiradas = [1 ,2,3,4,5,6];
    $continuar = true;
    $contador = 0;
    do{
        $rdo = $tiradas[array_rand($tiradas)]; //array_rand devulev un índice aleatorio del array
        $contador ++;
        echo "ha salido un $rdo en el lanzamiento</br>";
        if($rdo ==6){
            echo "Ha salido un 6 jugada terminada. Se han necesitado $contador lanzamientos.";
            $continuar = false;
        }
        
    }while($continuar)
?>