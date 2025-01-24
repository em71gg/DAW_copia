<!--
Crea un array multidimensional llamado $biblioteca que contenga información de tres libros, 
cada uno con titulo, autor, y año. 
Muestra los detalles de cada libro en el siguiente formato: Título: 1984, Autor: George Orwell, 
Año: 1949.
-->
<?php
require_once("utils.php");

$titulos = ['El Puente de Alcántara' , 'César', 'El Señor de Los Anillos'];
$autores = ['Frank Bauer', 'Adrian Goldsworthy', 'J.R.R. Tolkien'];
$publicados = [1991, 2006, 1954];


$len = minLentghArrays($titulos, $autores,$publicados); 

/*utilizo un for para cargar los arrays previos, que contienen los 
datos de las personas. Al ser datos de sólo tres personas no gano mucho, 
pero si fueran más, si que sería más eficiente.
*/

for($i = 0; $i < $len; $i++){
    $biblioteca[$i]['Título'] = $titulos[$i];
    $biblioteca[$i]['Autor'] = $autores[$i];
    $biblioteca[$i]['publicado'] = $publicados[$i];
    
}


foreach($biblioteca as $libro){//Itero porel array y accedo a los valores a través de las claves
          
        echo "Título: " .  $libro['Título'] . ", " . PHP_EOL;//las imprimo con los formatos pedidos
        echo "Autor: "  . $libro['Autor'] . ", " . PHP_EOL;
        echo "Año: "  . $libro['publicado'] . "." . PHP_EOL;
        echo "</br>";//añado un br al final de la línea de cada persona
}

?>
