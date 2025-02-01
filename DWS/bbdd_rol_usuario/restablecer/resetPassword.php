<?php
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

//$flag = 0;
//$email = "";
$errores = [];
isset($_SESSION['flag']) ? $flag = $_SESSION['flag'] : $flag = 0;//actualizo o creo el valor de flag para usarlo en la lógica
isset($_SESSION['email']) ? $email = $_SESSION['email'] : $email = "";//actualizo o creo el valor de email

       
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /*if(isset($_POST['flag'])){//mantiene el valor de flag del submit
        $flag=$_POST['flag'];
    }
  
    if ($flag ==1) {
        $pass1 = obtenerValorCampo('pass1');
        $pass2 = obtenerValorCampo('pass2');
        if (empty($pass1) || empty($pass2)) {//si las contraseñasno coinciden
            $errores['faltaPass'] = "debe introducir y confirmar la contraseña.";
        }else{
            if($pass1 == $pass2){//si todo ok con las contraseñas envío correo con link que al confirmase hará el update en la base de datos
                echo "contraseñas ok";

            }else{
                $errores['passDiscrepan'] = "Las contraseñas deben coincidir.";
            }
        }*/
    //}
   // else{
        $email = obtenerValorCampo('email');
        if (!empty($email)) {
            if (validarEmail($email)) { //si el email es válio se comprueba si está registrado
    
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email=?");
                $consulta->bindParam(1, $email);
                $consulta->execute();
                desconectarPDO($consulta, $conexion);
    
                if ($consulta->rowCount() > 0) { //Si el email existe en la bbdd llevo  creo sesion y llevo a introducir contraseña
                    echo "$email existe en la base de datos";
                    session_name('reset');
                    session_start();
                    $flag = 1;
                    $_SESSION['flag'] = $flag;
                    $_SESSION['email'] = $email;
                    echo "Valor email tras crear la sesion: $email";
                } else { //Si no existe el correo en la bbdd llevo a login
                    echo "<p>No existe como usuario</p>";
                    header('refresh:3; url=../login.php');
                }
            } else { //Si el email no es válido se pasa el error
                $errores['errorEmail'] = "Debe introducir un email válido";
            }
        } else { //si el campo del correo viene vacío lo pido
            $errores['emailVacio'] = "Debe introducir un email";
        }
   // }        
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
    
        <?php
        if ($flag == 0):
        ?>
            <?php
            echo "<h1>Valor flag = $flag</h1>";
            echo "<h1>Valor emial = $email</h1>";
            
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <label for="email">Introduce Email: </label>
                <input type="text" placeholder="<?php echo ($email != "" ? $email : "Email") ?>" name="email" id="email">
                <?php
            
                if (!empty($errores['emailVacio'])):
                ?>
                    <p class="errores"><?php echo $errores['emailVacio'] ?></p>
                <?php
                        endif;
                ?>
                <?php
                    if (!empty($errores['errorEmail'])):
                ?>
                    <p class="errores"><?php echo $errores['errorEmail'] ?></p>
                <?php
                        endif;
                ?>
                <p><input type="submit" value="enviar"></p>
            </form>
            
        <?php
            endif;
        ?>
        <?php
        if ($flag ==1):
        ?>
        ?>
            <?php
            echo "<h1>Valor flag = $flag</h1>";
            echo "<h1>Valor emial = $email</h1>";
            
            ?>

            <form action="./confirmarPassword.php" method="post">
                <input type="hidden" name="flag" value="1">
                <p>
                <label for="password1">Introduzca Contraseña: </label>
                <input type="password" name="pass1" id="password1">
                </p>   
                <p>
                <label for="password2">Confirme Contraseña: </label>
                <input type="password"  name="pass2" id="password2">
                </p>    
                <?php if (!empty($errores['faltaPass'])): ?>
                <p class="errores"><?php echo $errores['faltaPass']; ?></p>
                <?php endif; ?>
                
                <?php if (!empty($errores['passDiscrepan'])): ?>
                    <p class="errores"><?php echo $errores['passDiscrepan']; ?></p>
                <?php endif; ?>
                    <p><input type="submit" value="enviar"></p>
                </form>
        <?php
        endif;
        ?>
    
   
    <?php
    ?>
</body>

</html>