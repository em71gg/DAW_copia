<?php 

//archivo que sabeos que funciona porque conserva la estructura original entregada por el profesor
// Incluye ficheros de variables y funciones
require_once("./utiles/variables.php");
require_once("./utiles/funciones.php");

// Comprobamos que nos llega los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $contrasena ="";
    $email= obtenerValorCampo('email');//email recibido del formulario.
    
    $conexion = conectarPDO($host,$user, $password, $bbdd);
    // Comprobamos que el email extraido del formulario se corresponde con el de un cliente.
    //try{    }catch(PDOException){}finally{}
    $consulta = $conexion -> prepare("SELECT contrasena FROM usuarios WHERE email = ?");
    $consulta -> bindParam(1, $email);

    $consulta -> execute();

    if($consulta-> rowCount()>0){// Si existe ese mail del cliente en la base de datos, tomamos el valor de su hash almacenado.
        $row = $consulta -> fetch();
        $contrasena = $row['contrasena'];
        desconectarPDO($consulta, $conexion);
    }
    // Cargamos la contraseña del formulario para posteriormente validarla con la almacenada en la BBDD.
    $contrasenaFormulario = isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : null;
    if (password_verify($contrasenaFormulario, $contrasena))// Usando password_verify se comrpueba si hay coincidencia entre la clave del formulario y el hash de la base de datos.
     {
        // Si son correctos, creamos la sesión
        session_name("sesion-privada");
        session_start();
        $_SESSION['email'] = $_REQUEST['email'];
        // Redireccionamos a la página privada
        echo "Vamos a privado";
        header('Location: privado.php');
        exit();
        }
        else {
        // Si no son correctos, informamos al usuario
        print'<p style="color: red">El email o la contraseña es incorrecta.</p>';
        header("refresh:3;url=login.php");
        exit();
        }
}else{
    header('location: login.php');
}
?>