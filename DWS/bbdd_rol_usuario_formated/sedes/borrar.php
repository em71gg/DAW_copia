<?php
    // Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");

    //Si se ha seleccionado un registro para borrar
    if (count($_REQUEST) > 0)
    {
        $idSede= null;
        if (isset($_GET["idSede"]))
        {
            //Declarar la variable para la sede que tomará el valor del $_GET, conectar a la BBDD, definir la consulta a ejecutar (DELETE), 
            $idSede = $_GET["idSede"];
            $exito= false;

            $conexion = conectarPDO($host, $user, $password, $bbdd);
            //preparar la consulta (bindParam) y ejecutarla
            $consulta= $conexion -> prepare ("DELETE FROM sedes WHERE id = ?");
            $consulta -> bindParam (1, $idSede);

           try{
            
            $consulta ->execute();
            $exito = true;
           }catch (PDOException $exception){
                //echo "<p>" . $exception -> getMessage() . "</p>";
                $exito = false;
           }
        
            //Si todo ha ido bien, mostrar mensaje
            if ($exito/*consulta -> rowCount() >0*/) 
            {               
                echo "Registro Borrado";
            } 
            //Si no ha ido bien, mostrar mensaje 
            else 
            {
                echo "No se puede Borrar ese registro";
                
            }
                
                //En ambos casos, redireccionar al listado original tras 3 segundos.
                header('refresh: 3; url=listado.php');
                exit();
        } 
        
    } 
    //Evitar que se pueda entrar directamente a la página .../borrar.php, redireccionando en tal caso a la página del listado
    else 
    {
        header('location: listado.php');
    }
?>