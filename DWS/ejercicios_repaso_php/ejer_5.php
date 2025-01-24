<!--
Escribe una función que indique los días que tiene un mes
-->
<?php

    require_once("utils.php");

    function calcularDiasMes(string $mes, int $year):string {
        $mesNo31 =['febrero' , 'abril', 'junio', 'septiembre' , 'novienbre'];
        
        $mesFiltrado = strtolower($mes);
        if(!in_array($mesFiltrado, $mesNo31)){
            return ucfirst($mesFiltrado) . " tiene 31 días";
        }
        else{
            if($mesFiltrado !== 'febrero'){
                return ucfirst($mesFiltrado) . " tiene 30 días";
            }
            else{
                if(calcularBisiesto($year)){
                    return ucfirst($mesFiltrado) . " tiene 29 días";  
                }
                else{
                    return ucfirst($mesFiltrado) . " tiene 29 días";
                }
            }
        }
    }

   
    echo calcularDiasMes("febrero", 2024);

   
?>