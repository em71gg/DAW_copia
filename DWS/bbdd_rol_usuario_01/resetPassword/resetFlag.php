<?php

    session_name('reset');
    session_start();
    include_once('../utiles/funciones.php');
    include_once('../utiles/variables.php');

    $flag = false;
    $erroresMail = [];
    $erroresPass = [];
    $exito = false;
    $flag = isset($_SESSION['flag']) ? $_SESSION['flag'] : false;
    $validatedEmail = isset($_SESSION['validatedEmail']) ? $_SESSION['validatedEmail'] : null;

    if ($flag == true) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $password1 = obtenerValorCampo('password1');
            $password = obtenerValorCampo('password');
            $email = obtenerValorCampo('validatedEmail');

            //echo $email;
            if(!empty($password) && !empty($password1) && $password == $password1){
                $conexion = conectar("$host, $user, $password, $bbdd");
                try {
                    $token = bin2hex(openssl_random_pseudo_bytes(16));
                    $consulta = $conexion -> prepare ("UPDATE
                                                        usuarios
                                                        SET password = ?, token = ?, activo = ?
                                                        WHERE email = ?"
                                                        );
                    $consulta -> bindParam(1, $password);
                    $consulta -> bindParam(2, $token);
                    $consulta -> bindParam(3, 0);
                    $consulta -> bindParam(4, $email);

                    $consulta -> execute();

                    if ($consulta -> rowCount() > 0) {
                        $exito = 1;
                        header('refresh: 3, url=../login.php');
                    }
                    else {
                        $exito = 2;
                        header('refresh: 3, url=../login.php');
                    }
                }
                catch (PDOException $exc) {
                    echo "<p>" . $exc -> getMessage() . "</p>";
                } 
                finally {}
            }
            else{
                if(empty($password) || empty($password1)){
                    $erroresPass[] = "Debe escribir y confirmar su contraseña.";
                }
                elseif($password !== $password1){
                    $erroresPass[] = "La contraseña de confirmación es diferente, deben ser iguales.";
                }
            }
        }
        else{
            header('location: ../login.php');
        }
        
    }
    else{
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $emailToValidate = obtenerValorCampo('email');

            if(!empty($emailToValidate)){
                if(!validarEmail($emailToValidate)){
                    $erroresMail[] = "Debe introducir un correo electrónico con formato válido.";
                }else{
                    
                    try{
                        $conexion =  conectarPDO($host, $user, $password, $bbdd);
                        $consulta = $conexion -> prepare ( "SELECT * 
                                                            FROM usuarios
                                                            WHERE email = ?"
                                                        );
                        $consulta -> bindParam(1, $emailToValidate);
                        $consulta -> execute();

                        if ($consulta -> rowCount() == 0){ //si el mail no está en nuestra base de datos se 
                                                        //envía al login y se le da un mensaje falso de envío
                                                        //de mail de confirmación
                            desconectarPDO($consulta, $conexion);
                            echo "Se le ha enviado un correo de conrimación revise su correo.";
                            header('refresh: 3, url: ../login.php');
                        }
                        else{//Si tenemos el correo en la base de datos se crea la variable 
                            //$validatedEmail que se enviará oculta, ponemos el flag a true
                            //y se le abre la posibilidad de actualizar la contraseña
                            $validatedEmail = $emailToValidate;
                            $flag = true;
                        }

                    }catch(PDOException $exc){
                        echo "<p>" . $exc -> getMessage() . "</p>";
                     }finally{
                        desconectarPDO($consulta, $conexion);
                     }
                    
                }
            }else{
                $erroresMail[] = "Debe introducir su email.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Actualizar contraseña</h1>                
    <?php //endif; ?>

    <?php if ($flag == true): /*paso2*/?>
        <?php if (count($erroresPass) >0): ?>
            <ul class="errores">
                <?php 
                    foreach ($erroresPass as $error) 
                    {
                        echo '<li>' . $error . '</li>';
                    } 
                ?> 
            </ul>
        <?php endif; ?>
            <!-- Campo de Contraseña -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="validatedEmail" value="<?php echo htmlspecialchars($validatedEmail) ?>">
            <p>
                <label>
                    Contraseña
                    <input type="password" name="password1">
                </label>
            </p>
            <p>
                <label>
                    Confirme contraseña
                    
                    <input type="password" name="password">
                </label>
            </p>
        
            <p>
                <!-- Botón submit -->
                <input type="submit" value="Actualizar contraseña">
            </p>
        </form>
    <?php elseif ($flag == false): /*paso1*/?>
        <!-- Mostramos errores por HTML -->
        <?php if (count($erroresMail) > 0): ?>
            <ul class="errores">
                <?php 
                    foreach ($erroresMail as $error) 
                    {
                        echo '<li>' . $error . '</li>';
                    } 
                ?> 
            </ul>
        <?php endif; ?>
            <!-- Formulario -->
             <p>Introduzca y envíe su correo</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <p>
                    <!-- Campo de Email -->
                    <label>
                        Correo electrónico
                        <input type="text" name="email">
                    </label>
                </p>
                <input type="submit" value="Enviar">
            </form>
    <?php elseif ($exito === 1): ?>
            <h1>Contraseña actualizada</h1>
    <?php elseif ($exito == 2): ?>
        <h1>No se ha podido actualizar la contraseña</h1>
    <?php endif; ?>
</body>
</html>