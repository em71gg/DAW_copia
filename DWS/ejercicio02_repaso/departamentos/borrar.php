<?php
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");

	//Si se ha seleccionado un registro para borrar
    if (count($_REQUEST) > 0)
    {

        if (isset($_GET["idDepartamento"]))
        {
            //Declarar la variable para la sedeque tomará el valor del $_GET, conectar a la BBDD, definir la consulta a ejecutar (DELETE), 
            //preparar la consulta (bindParam) y ejecutarla
			

			 //Si todo ha ido bien, mostrar mensaje
        	if () 
        	{
        	} 
			//Si no ha ido bien, mostrar mensaje 
            else 
            {
            }
            
            //En ambos casos, redireccionar al listado original tras 3 segundos.
	    } 
	    
	} 
	//Evitar que se pueda entrar directamente a la página .../borrar.php, redireccionando en tal caso a la página del listado
    else 
    {
    }
?>