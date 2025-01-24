<!--
Imprime la hora y la fecha actual.
-->
<?php
print"<p>Estamos a " . date("d-m-Y") . " y son las " . 
    date("H:i:s") . ".</p>";

setlocale(LC_TIME, "es_ES.UTF-8");

// deprecated print"<p>Estamos a " . strftime("%d del ") 

?>