<?php
?>

<!DOCTYPE html>
<html lang="es">
    <head></head>
    <body>
        <h1>TABLA CUADRADA CON CASILLAS DE VERIFICACIÓN</h1>
        <h1>(FORMULARIO)</h1>
        <p>Escriba un número (0< número <= 20) y dibujará una tabla cuadrada de ese taqmaño con casillas de verificacion en cada celda</p>
        <form action="casillasVerificacion2.php" method="post">
            <p>
                <label for='tamaño'>Tamaño de la tabla</label>
                <input type='number' name='rango' step='1' placeholder='0'>
            </p>
            <p>
                <input type='submit' value='Mostrar'>
                <input type='reset' value='Borrar'>
            </p>
        </form>
    </body>
</html>