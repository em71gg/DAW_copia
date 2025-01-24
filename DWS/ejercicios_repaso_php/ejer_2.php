<!--
Se desea encontrar el valor máximo y mínimo de una serie de números desordenados.  
// serie de números definida 
$series = array(76, 7348, 56, 2.6, 189, 67.59, 17594, 2648, 1929.79, 54, 329, 820, -1.10, -1.101);
-->
<?php
    $series = array(76, 7348, 56, 2.6, 189, 67.59, 17594, 2648, 1929.79, 54, 329, 820, -1.10, -1.101);
/**
 * La función toma como argumento un array de números y devulve
 * un string donde se informa del máximo y el mínimo de la serie
 * @param {array}
 * @return {string}
 */
function returnMaxMin(array $array): string {
    //fijo los valores de partida de max y min
    $max = $array[0];
    $min = $array[0];
    /*itero por los valores del aray y si alguno de ellos es mayor 
    o menor que max y min sustituye su valor*/
    foreach($array as $value){
        if ($value > $max){
            $max = $value;
        }
        elseif($value< $min){
            $min = $value;
        }
    }
    return "El máximo de la series = $max.</br>El mínimo de la serie es = $min.";
}

echo returnMaxMin($series);
?>