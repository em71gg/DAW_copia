<?php 
// Incluye ficheros de variables y funciones
require_once("../utiles/variables.php");
require_once("../utiles/funciones.php");

// Comprobamos que nos llega los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// Variables del formulario
$emailFormulario = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
$contrasenaFormulario = isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : null;
//echo "<p>Email: " . $emailFormulario ."</p>". PHP_EOL;
//echo "<p>Password: " . $contrasenaFormulario ."</p>". PHP_EOL;

//Conectamos a la bbdd y hacemos un SELECT
$conexion = conectarPDO($host, $user, $password, $bbdd);
//Usamos un SELECT para traer los valores del usuario.
$select = "SELECT id, nombre, email, clave FROM empleados WHERE email = :email";
// preparamos la consulta (bindParam)
$consulta = $conexion->prepare($select);
$consulta->bindParam(':email', $emailFormulario); 
$consulta->execute();

// Comprobamos si los datos son correctos
if ($consulta->rowCount() > 0 )
	{
        $registro = $consulta->fetch();
        if(password_verify($contrasenaFormulario, $registro['clave'])){
            // Si son correctos, creamos la sesión
            session_name("sesion-privada");
            session_start();
            $_SESSION['email'] = $_REQUEST['email'];
            // Redireccionamos a la página privada
            //echo "Vamos a privado";
            //header('Location: privado.php');
            header('Location: ../index.php');
            exit();
        }
        else {
            // Si no son correctos, informamos al usuario
            print'<p style="color: red">El email o la contraseña es incorrecta.</p>';
            header("refresh:3;url=../index.php");
            exit();
        }
    }
else 
{
    // Si no son correctos, informamos al usuario
    print'<p style="color: red">El email o la contraseña es incorrecta.</p>';
    header("refresh:3;url=../index.php");
    exit();
    }
}

// Comprobamos si los datos son correctos
/*
if ($baseDeDatos['email'] == $emailFormulario && password_verify($contrasenaFormulario, $baseDeDatos['password'])) {
    // Si son correctos, creamos la sesión
    session_name("sesion-privada");
    session_start();
    $_SESSION['email'] = $_REQUEST['email'];
    // Redireccionamos a la página privada
    //echo "Vamos a privado";
    header('Location: privado.php');
    exit();
    }
    else {
    // Si no son correctos, informamos al usuario
    print'<p style="color: red">El email o la contraseña es incorrecta.</p>';
    header("refresh:3;url=login.php");
	exit();
    }
}
*/
?>