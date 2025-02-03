<?php


?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <link rel="stylesheet" href="./styles.css">
</head>

<body>
    <header>
        <div class="header-bg">
            <div class="header-container">
                <p class="header-title">Aplicación Empresa</p>
                <div id="options-header">
                    <form action="#" method="post">
                        <div class="logout-button-container">

                            <input type="text" class="logout-button" placeholder="Usuario">



                        </div>
                        <div class="logout-button-container">
                            
                        <input type="password" class="logout-button" placeholder="Contraseña">
                                    
                                </button>
                            </a>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </header>

    <main id="content" role="main">
        <div class="form-container">
            <div class="form-card">
                <div class="text-center">
                    <h1 class="title">¿Olvidó su contraseña?</h1>
                    <p class="text-sm">
                        ¿Recuerda su contraseña?
                        <a class="link" href="../login.php">Ir a login</a>
                    </p>
                </div>
                <div class="form-section">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <div class="form-group">
                            <label for="email" class="label">Dirección email</label>
                            <input type="text" id="email" name="email" class="input" placeholder="<?php echo ($email != "" ? $email : "Email") ?>" />
                        </div>
                        <div class="text-center">
                            <?php if (!empty($errores['noUsuario'])): ?>
                                <p class="error"><?php echo $errores['noUsuario'] ?></p>
                            <?php endif; ?>
                            <?php if (!empty($errores['emailVacio'])): ?>
                                <p class="error"><?php echo $errores['emailVacio'] ?></p>
                            <?php endif; ?>
                            <?php if (!empty($errores['errorEmail'])): ?>
                                <p class="error"><?php echo $errores['errorEmail'] ?></p>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="info-container">
            <div class="info-card">
                <div class="text-left">
                    <ul class="info-list">
                        <li>
                            <p class="info-text">Se confirma el correo, si está registrado se ingresa la nueva contraseña.</p>
                        </li>
                        <li>
                            <p class="info-text">Se valida la contraseña, se genera un token y se inactiva al usuario.</p>
                        </li>
                        <li>
                            <p class="info-text">El usuario recibe un correo con un link de confirmación.</p>
                        </li>
                        <li>
                            <p class="info-text">Tendrá 5 minutos para usarlo.</p>
                        </li>
                        <li>
                            <p class="info-text">Al pulsar el link de confirmación cambia la contraseña, se activa el usuario y se borra el token.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>

</html>