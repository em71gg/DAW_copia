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
    /**
     * minLen calcula la longitud del array más corto de una serie de arrays
     * pasados como arbumentos
     * @param ...1arg serie de arrays a calcular su longitud
     * @return {int} longitud del array más corto 
     */
    function minLentghArrays(...$argv):int {
        $countList = [];//array para almacenar las longitudes de los distintos arrays
        foreach($argv as $array){//Itero por la matriz argv
            $countList[]=count($array);//calculo la longitud de cada array de argv y añado el resultado a countList
        }
        return min($countList);//calcula y devuleve el minimo de countList
    }

    
      
?>