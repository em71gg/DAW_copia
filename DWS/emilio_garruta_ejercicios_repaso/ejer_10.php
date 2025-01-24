<!--
Crea  un  array  multidimensional  llamado  $estudiantes  que  contenga  los  nombres  de  tres 
estudiantes y sus calificaciones en tres materias. 
Calcula el promedio de calificaciones de cada estudiante y muestra el resultado en el siguiente 
formato: Estudiante: Juan, Promedio: 7.5
-->

<?php
/*
<!--
Crea  un  array  multidimensional  llamado  $estudiantes  que  contenga  los  nombres  de  tres 
estudiantes y sus calificaciones en tres materias. 
Calcula el promedio de calificaciones de cada estudiante y muestra el resultado en el siguiente 
formato: Estudiante: Juan, Promedio: 7.5
-->
*/

require_once("utils.php");

$nombres = ['Pepe' , 'Juan', 'Manolo'];
$matematicas= [6,8,7];
$lengua = [9,5,8];
$historia =[10, 7.5, 5];


$len = minLentghArrays($nombres, $matematicas, $lengua, $historia); 

/*utilizo un for para cargar los arrays previos, que contienen los 
datos de las personas. Al ser datos de sólo tres personas no gano mucho, 
pero si fueran más, si que sería más eficiente.
*/


for($i = 0; $i < $len; $i++){
    $estudiantes[$i]['Nombre'] = $nombres[$i];
    $estudiantes[$i]['Matemáticas'] = $matematicas[$i];
    $estudiantes[$i]['Lengua'] = $lengua[$i];
    $estudiantes[$i]['Historia'] = $historia[$i];
}


foreach($estudiantes as $estudiante){//Itero por el array y accedo a los arrays con los datos de cada estudiante 
    $name = $estudiante['Nombre'];
    $sum = 0;//creo las variables sum y count que utilizare´para calcular el promedio
    $count = 1;
    foreach($estudiante as $nota){//itero dentro de cada array Estudiante
        if(is_numeric($nota)){//Si el valor es numérico, se trata de una nota y accedo al bloque 
                            //de la condición donde actulizo las variables sum y count
            $sum +=$nota;
            $count ++;
        }
    $average = number_format($sum/$count,1);//calculo la meida con el formato requerido
    }
    
    echo "Nombre: " .  $estudiante['Nombre'] . ", " . PHP_EOL;//las imprimo con los formatos pedidos
    echo "Promedio: "  . $average . "." . PHP_EOL;
    echo "</br>";//añado un br al final de la línea de cada persona
}

?>