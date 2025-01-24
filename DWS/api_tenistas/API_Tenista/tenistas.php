<?php
 /**
 * Ejercicio - API 
 *
 * @author Escriba aquí su nombre
 */

  require_once("../utiles/config.php");
  require_once("../utiles/funciones.php");
  $datos = "";

  // ESCRIBA AQUI EL CÓDIGO PHP NECESARIO
  
  /*
    Datos del tenista y una nueva clave con los titulos que tiene, que es una estructura en la que aparecen los nombres de los títulos agrupados por años
  */

  // ESCRIBA AQUI EL CÓDIGO PHP NECESARIO

  /*
    Borrar un tenista
  */

  // ESCRIBA AQUI EL CÓDIGO PHP NECESARIO

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header ($headerJSON);
header ($codigosHTTP["400"]);
echo  $datos;
?>