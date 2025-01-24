<!--
Crea  un  array  multidimensional  llamado  $personas  con  información  de  tres  personas,  cada 
una con nombre, edad, y ciudad. 
Muestra los datos de cada persona en el siguiente formato: Nombre: Juan, Edad: 25, Ciudad: 
Madrid
-->
<?php
/*
<!--
Crea  un  array  multidimensional  llamado  $personas  con  información  de  tres  personas,  cada 
una con nombre, edad, y ciudad. 
Muestra los datos de cada persona en el siguiente formato: Nombre: Juan, Edad: 25, Ciudad: 
Madrid
-->
*/
require_once("utils.php");

$nombres = ['Pepe' , 'Juan', 'Manolo'];
$ciudades = ['Sevilla', 'Madrid', 'Jaen'];
$edades = [24, 42, 21];


$len = minLentghArrays($nombres, $ciudades, $edades); 

/*utilizo un for para cargar los arrays previos, que contienen los 
datos de las personas. Al ser datos de sólo tres personas no gano mucho, 
pero si fueran más, si que sería más eficiente.
*/

for($i = 0; $i < $len; $i++){
    $personas[$i]['Nombre'] = $nombres[$i];
    $personas[$i]['Edad'] = $edades[$i];
    $personas[$i]['Ciudad'] = $ciudades[$i];
}


foreach($personas as $persona){//Itero porel array y accedo a los valores a través de las claves
          
        echo "Nombre: " .  $persona['Nombre'] . ", " . PHP_EOL;//las imprimo con los formatos pedidos
        echo "Edad: "  . $persona['Edad'] . ", " . PHP_EOL;
        echo "Ciudad: "  . $persona['Ciudad'] . "." . PHP_EOL;
        echo "</br>";//añado un br al final de la línea de cada persona
}

?>
