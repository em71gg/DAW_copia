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
    <!-- Formulario de identificación -->
    <form action="./acceso/acceso.php" method="post">
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

    <p><a href="./resetPassword/resetPassword.php">Contraseña olvidada</a></p>
    <p>email: admin@email.com, rol: 1 adminstrador</p>
    <p>email: gestor@email.com, rol: 2 gestor</p>
    <p>email: consulta@email.com, rol: 3 consultor</p>
    
    <p>contraseña: 12345</p>

</body>
</html>