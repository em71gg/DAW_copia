<!DOCTYPE html>
<html lang="es">
    <head>
    </head>
    <body>
        <form action="datosPersonalesAction.php" method="post">
            <div>
                <label for="name">Escriba su nombre</label>
                <input type="text" name="nombre" placeholder = "nombre" id="name">
            </div>
            <div>
                <label for="apellido">Escriba sus apellidos</label>
                <input type="text" name="apellidos" placeholder = "apellidos" id="apellido">
            </div>
            <div>
                <label for="edad">Escriba su edad</label>
                <input type="number" name="edad" step="1" id="edad">
            </div>
            <div>
                <label for="peso">Escriba su nombre</label>
                <input type="number" name="peso" step="0.1" id="name">
            </div>
            <input type="submit" value="Enviar">
            <input type="reset" value="Borrar">
        </form>
    </body>
</html>