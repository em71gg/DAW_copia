<?php

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>

<body>
    <main>
        <div>
            <h1>TAbla cuadrada con casillas de verificacion</h1>
            <p>Escriba un numero (mayor que cero y menor o igual a 20) y dibujaré una tabla cuadrada con ese tamaño de casilla de verificacion en cada celda</p>


        </div>
        <div>
            <form action="ej4b.php" method="post">
                <p>tamaño de la tabla:
                    <input type="number" name="tabla" step=1>
                </p>
                <p><input type="submit" name="mostrar" value="Mostrar">
                    <input type="reset" name="borrar" value="borrar">
                </p>
            </form>
        </div>
        <div></div>

    </main>

</body>

</html>