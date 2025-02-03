<?php
session_name("sesion-privada");
session_start();
// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
if (!isset($_SESSION["email"])) header("Location: ../login.php");
require_once('../utiles/funciones.php');
require_once('../utiles/variables.php');

//-----------------------------------------------------
// Variables
//-----------------------------------------------------
$errores = [];
$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
$passwordForm = isset($_REQUEST["contrasena"]) ? $_REQUEST["contrasena"] : null;


// Comprobamos que nos llega los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //-----------------------------------------------------
  // COMPROBAR SI LA CUENTA ESTÁ ACTIVA
  //-----------------------------------------------------
  // Conecta con base de datos
  $conexion = conectarPDO($host, $user, $password, $bbdd);
  // Prepara SELECT para obtener la contraseña almacenada del usuario
  $select = "SELECT * FROM usuarios WHERE email = :email;";
  $consulta = $conexion->prepare($select);
  // Ejecuta consulta
  $consulta->execute([
    "email" => $email
  ]);
  // Guardo el resultado
  //echo "resultado consulta: " . $consulta -> rowCount() . "</br>";
  if ($consulta->rowCount() > 0) {
    $resultado = $consulta->fetch();
    //echo "resultado activo antes de hacer la conprobación del if: " . (int) $resultado["activo"];
    // Al hacer comprobación con el tipo convertimos el resultado a entero

    if ((int) $resultado["activo"] !== 1) {
      $errores[0] = "Tu cuenta aún no está activa. ¿Has comprobado tu bandeja de correo?";
    } else {

      //-----------------------------------------------------
      // COMPROBAR LA CONTRASEÑA
      //-----------------------------------------------------
      // Comprobamos si es válida
      if (password_verify($passwordForm, $resultado["password"])) {
        // Si son correctos, creamos la sesión
        session_name('sesion-privada');
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["rol_id"] = $resultado['rol_id'];
        // Redireccionamos a la página segura
        header("Location: index.php");
        exit();
      } else {
        $errores[1] = "El email o la contraseña es incorrecta.";
      }
    }
  } else {
    //Si el correo no está dado de alta 
    $errores[2] = "El email o la contraseña es incorrecta.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrada</title>
</head>

<body>
  <h1>Entrar</h1>
  <!-- Mostramos errores por HTML -->
  <?php if (count($errores) > 0): ?>
    <ul class="errores">
      <?php
      foreach ($errores as $error) {
        echo "<li>" . $error . "</li>";
      }
      ?>
    </ul>
  <?php endif; ?>
  <!-- Mensaje de aviso al registrarte -->
  <?php if (isset($_REQUEST["registrado"])): ?>
    <p>¡Gracias por registrarte! Revisa tu bandeja de correo para activar la cuenta.</p>
  <?php endif; ?>
  <!-- Mensaje de cuenta activa -->
  <?php if (isset($_REQUEST["activada"])): ?>
    <p>¡Cuenta activada! Inicie sesion.</p>
  <?php endif; ?>
  <!-- Formulario de identificación -->
  <form method="post">
    <p>
      <input type="text" name="email" placeholder="Email">
    </p>
    <p>
      <input type="password" name="contrasena" placeholder="Contraseña">
    </p>
    <p>
      <input type="submit" value="Entrar">
    </p>
  </form>
</body>

</html>