<!--
Escribe una función para comparar dos fechas y 
ver cuál es más reciente.
-->

<?php

function compararFechas($fecha1, $fecha2){

    $objFecha1 = new DateTime($fecha1);//uso el objeto datetime de php
    $objFecha2 = new DateTime($fecha2);

    if($objFecha1 > $objFecha2){
        echo "$fecha1 es mayor";
    }elseif($objFecha1 = $objFecha2){
        echo "Las fechas son iguales";
    }else{
        echo"$fecha2 es mayor";
    }

}

echo compararFechas("12-01-2024", "21-02-2012");
?>