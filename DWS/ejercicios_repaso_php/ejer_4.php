<?php

    /**
     * calularBisiesto calcula si un año es bisiesto o no
     * @param {int} año a calcular
     * @return {bool} true si es biisesto false si no 
     */
    function calcularBisiesto(int $year): bool{
        //Introduzco las condciones pedidas en el enunciado
        if($year % 4 === 0 && $year % 100 !== 0 ||
         $year % 400 === 0 && $year % 100 !== 0){
            return true; //Si las cumple es bisiesto
        }
        else{
            return false; //si no no lo es
        }
    };

    //conjunto de años para comprobar la función
    $years =[2020, 2021, 2022, 2023, 2024]; 
    
    foreach($years as $year){
        echo $year;
        if (calcularBisiesto($year)){ //llamada a la función
            print"<p>El año $year es bisiesto</p>".PHP_EOL;
        }
        else{
            print"<p>El año $year no es bisiesto</p>".PHP_EOL;
        }
    }
    
?>