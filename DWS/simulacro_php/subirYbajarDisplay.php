<?php
    session_start(); 
    if(!isset($_SESSION['resultado'])){
        $_SESSION['resultado'] = 0;
        //$resultado = $_SESSION['resultado'];
    }
?>

<!DOCTYPE html>

<html lang="es">
    <head>

    </head>
    <body>
        <form action="subirYbajarAction.php" method="post">
            <p>
                <input type="submit" name="decrease" value="-">
                <span><?php echo $_SESSION['resultado']?></span>
                <input type="submit" name="increase" value="+">
            </p>
            <input type="submit" name="reset" value="Poner a Cero">
        </form>
    </body>
</html>