<?php
session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["email"])) header("Location: ../login.php");
$rol = $_SESSION['rol_id'];
echo $rol;

require_once('../utiles/variables.php');
require_once('../utiles/funciones.php');
$errores = [];
$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : "";
//$rol = isset($_REQUEST['rol']) ? $_REQUEST['rol'] : "";
$passwordForm = isset($_REQUEST["password"]) ? $_REQUEST["password"] : "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //-----------------------------------------------------
    // Validaciones
    //-----------------------------------------------------
    // Email

    $rol = obtenerValorCampo('rol');
    echo $rol;
    if ($rol === "Seleccione un rol" || empty($rol)) {
        $errores[] = "Debe seleccionar un roll.";
    } else {
    }
    if (!validarRequerido($email)) {
        $errores[] = "Campo Email obligatorio.";
    }
    if (!validarEmail($email)) {
        $errores[] = "Campo Email no tiene un formato válido";
    }
    // Contraseña
    if (!validarRequerido($passwordForm)) {
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
    desconectarPDO($consulta, $conexion);
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
        $insert = "INSERT INTO usuarios (email, password, activo, token, rol_id, fecha) VALUES 
                            (:email, :password, :activo, :token, :rol_id, CURRENT_TIMESTAMP)";
        $consulta = $conexion->prepare($insert);
        // Ejecuta el nuevo registro en la base de datos
        $consulta->execute([
            "email" => $email,
            "password" => password_hash($passwordForm, PASSWORD_BCRYPT),
            "activo" => 0,
            "token" => $token,
            "rol_id" => $rol,
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
            Gracias por registrate en la mejor plataforma de internet, demuestras inteligencia.\n
            Para activar entra en el siguiente enlace:\n
           http://localhost:3000/DWS/bbdd_rol_usuario/acceso/verificar-cuenta.php?email=$emailEncode&token=$tokenEncode
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
            foreach ($errores as $error) {
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
                Correo electrónico
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
        <p>
            <label for="rol">Seleccione rol de usuario</label>
            <select name="rol" id="rol">
                <option>Seleccione un rol</option>
                <?php
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                $getOptions = ("SELECT id, nombre FROM roles");
                $rdo = resultadoConsulta($conexion, $getOptions);
                while ($registro = $rdo->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <option value="<?php echo $registro['id'] ?>"><?php echo $registro["nombre"]; ?></option>
                <?php
                endwhile;
                ?>

            </select>

        </p>
        <!-- Botón submit -->
        <input type="submit" value="Registrarse">
        </p>
    </form>
</body>

</html>