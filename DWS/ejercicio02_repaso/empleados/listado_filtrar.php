<?php

    // Incluye ficheros de variables y funciones

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de empleados</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
</head>
<body>
    <h1>Listado de empleados (filtrar por salario y/o número de hijos)</h1>
    <div style="margin-bottom: 1em">
      <fieldset style="width:50%">
        <legend>Filtrado</legend>
        <form name="filtrar" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <p><label for="texto">Texto <input type="text" name="texto"></label>
          </p>
          <p><label for="salarioMinimo">Salario mínimo <input type="number" step="0.01" name="salarioMinimo" min="0"></label>
          <label for="salarioMaximo">Salario Máximo <input type="number" step="0.01" name="salarioMaximo" min="0"></label>
          </p>
          <p>Hijos: <select name="hijos">
            <option value="">Seleccione el número de hijos</option>
            <?php
              for ($i=0; $i<=10; $i++):
            ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php
              endfor;
            ?>
          </select>
          </p>
          <input type="submit" value="Filtrar">
        </form>
      </fieldset>
    </div>
      <?php
        
        // Realiza la conexion a la base de datos a través de una función 
        
        // Obtenemos los valores del formulario de filtrado. Necesitarás una varialble cor cada uno: texto, sueldo min, sueldo max e hijos.
        
        /* Crea las condiciones de filtrado. 
          Para ello deberías considerar crear una variable string que se construya como unión de las distintas condiciones.
          También una variable tipo array donde se vayan metiendo las distintas condiciones: la de texto, la de sueldos y la de hijos.
          Entre ambas, se debería construir la sentencia SQL para hacer el filtrado "WHERE..."*/
        
        // Realiza la consulta (SELECT) a ejecutar en la base de datos en una variable
        
        // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement

        // Muestra los criterios de búsqueda. Hay que tener en cuenta si el filtrado tiene algún resultado o no hay registros con el criterio de búsqueda, ya que si no hay resultados, se debería avisar. 
      
      ?> 
      
        <table border="1" cellpadding="10">
          <thead>
              <th>Nombre</th>
              <th>Apellidos</th>
              <th>Correo electrónico</th>
              <th>Nº hijos</th>
              <th>Salario</th>
              <th>Nacionalidad</th>
              <th>Departamento</th>
              <th>Sede</th>
          </thead>
          <tbody>

              <!-- Muestra los datos. Recorre la matriz para ir pintando los campos.-->
              
          </tbody>
        </table>
      
      <div class="contenedor">
          <div class="enlaces">
              <a href="../index.php">Volver a página de listados</a>
          </div>
      </div>

    <?php

        // Libera el resultado y cierra la conexión
    
    ?>
</body>
</html>
