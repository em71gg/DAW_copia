<!--
Confeccionar un  formulario que solicite la carga  del nombre de una persona y que permita 
seleccionar  una  serie  de  deportes  que  practica  (futbol,  basket,  tennis,  voley).  Mostrar  en  la 
pagina que procesa el formulario la cantidad de deportes que practica
-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Deportes</title>
</head>
<body>
  

    <h1>Listado de deportes</h1>
    <form action="procesar_12.php" method="POST">
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required></br>
        <h2>Seleccione los deportes que practica:</h2>
        <label>
            <input type="checkbox" name="deportes[]" value="Futbol"> Futbol
        </label><br>
        <label>
            <input type="checkbox" name="deportes[]" value="Basket" > Basket
        </label><br>
        <label>
            <input type="checkbox" name="deportes[]" value="Tenis"> Tenis
        </label><br>
        <label>
            <input type="checkbox" name="deportes[]" value="Voley"> Voley
        </label><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>