<?php
    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');

    
    
    //$mysql="mysql:host=$host;dbname=$bbdd;charset=utf8";
    $conexion = conectarPDO($host, $user, $password, $bbdd);
    // set the PDO error mode to exception
    //$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //-----------------------------------------------------
    // Variables
    //-----------------------------------------------------
    $errores = [];
    $email = obtenerValorCampo('email');
    $passwordForm = obtenerValorCampo('password');

    // Comprobamos si nos llega los datos por POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
    //-----------------------------------------------------
    // Validaciones
    //-----------------------------------------------------
    // Email
    if (empty($email)) {
    $errores[] = "Campo Email obligatorio.";
    }
    if (!validarEmail($email)) {
    $errores[] = "Campo Email no tiene un formato válido";
    }
    // Contraseña
    if (empty($passwordForm)) {
    $errores[] = "Campo Contraseña obligatorio.";
    }
    /* Verificar que no existe en la base de datos el mismo email */
    // Conecta con base de datos
    $conexion = conectarPDO($host, $user, $password, $bbdd);
    // Cuenta cuantos emails existen
    $select = "SELECT COUNT(*) as numero FROM usuarios WHERE email = :email";
    $consulta = $conexion->prepare($select);
    // Ejecuta la búsqueda
    $consulta->execute([
        "email" => $email
        ]);
    // Recoge los resultados
    $resultado = $consulta->fetch();
    $consulta = null;
    // Comprueba si existe
    if ($resultado["numero"] > 0) {
    $errores[] = "La dirección de email ya esta registrada.";
    }
    //-----------------------------------------------------
    // Crear cuenta
    //-----------------------------------------------------
    if (count($errores) === 0) {
    /* Registro En La Base De Datos */
    // Prepara INSERT
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $insert = "INSERT INTO usuarios (email, password, activo, token) VALUES
    (:email, :password, :activo, :token)";
    $consulta = $conexion->prepare($insert);
    // Ejecuta el nuevo registro en la base de datos
    $consulta->execute([
    "email" => $email,
    "password" => password_hash($passwordForm, PASSWORD_BCRYPT),
    "activo" => 0,
    "token" => $token
    ]);
    $consulta = null;
    /* Envío De Email Con Token */
    // Cabecera
    $headers = [
    "From" => "dwes@php.com",
    "Content-type" => "text/plain; charset=utf-8"
    ];
    // Variables para el email
    $emailEncode = urlencode($email);
    $tokenEncode = urlencode($token);
    // Texto del email
    $textoEmail = "
    Hola!\n
    Gracias por registrate en la mejor plataforma de internet, demuestras inteligencia.\
    n
    Para activar entra en el siguiente enlace:\n
    http://localhost:3000/ejercicio02_repaso/registro/verificar-cuenta.php?email=$emailEncode&token=$tokenEncode
    ";
    // Envio del email
    mail($email, 'Activa tu cuenta', $textoEmail, $headers);
    /* Redirección a login.php con GET para informar del envío del email */
    header('Location: identificarse.php?registrado=1');
    exit();
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
        <h1>Registro</h1>
    <!-- Mostramos errores por HTML -->
    <?php if (isset($errores)): ?>
        <ul class="errores">
    <?php
        foreach ($errores as $error)
        {
        echo '<li>' . $error . '</li>';
        }
    ?>
        </ul>
    <?php endif; ?>
    <!-- Formulario -->
         <form action="" method="post">
            <p>
            <!-- Campo de Email -->
            <label>
            Correo electrónio
            <input type="text" name="email">
            </label>
            </p>
            <p>
            <!-- Campo de Contraseña -->
            <label>
            Contraseña
            <input type="password" name="password">
            </label>
            </p>
            <p>
            <!-- Botón submit -->
            <input type="submit" value="Registrarse">
            </p>
        </form>
    </body>
</html>
        