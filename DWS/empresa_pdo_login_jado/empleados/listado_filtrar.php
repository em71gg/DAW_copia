<?php
    // Incluye ficheros de variables y funciones
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");
    // Intentamos recuperar la sesión
    session_name("sesion-privada");
    session_start();
    $privado=False;
    // Comprueba si existe la sesión "email"
    if (isset($_SESSION["email"])) $privado=True;
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
        if (isset ($_REQUEST["texto"]) || isset ($_REQUEST["salarioMinimo"]) || isset($_REQUEST["salarioMaximo"]) || isset ($_REQUEST["hijos"])){
          // Realiza la conexion a la base de datos a través de una función 
          $conexion = conectarPDO($host, $user, $password, $bbdd);        
          // Obtenemos los valores del formulario de filtrado. Necesitarás una varialble por cada uno: texto, sueldo min, sueldo max e hijos.
          // Texto
          $texto=obtenerValorCampo("texto");
          //echo "<p>El texto introducido: " . $texto;
          if ($texto!="") $condiciones[] = "(e.nombre LIKE '%".$texto."%' OR apellidos LIKE '%".$texto."%' OR email LIKE '%".$texto."%')";
          // Salario mínimo
          $salarioMinimo=obtenerValorCampo("salarioMinimo");
          //echo "<p>El salarioMinimo introducido: " . $salarioMinimo;
          if ($salarioMinimo!="") $condiciones[] = "salario > ". $salarioMinimo;
          // Salario máximo
          $salarioMaximo=obtenerValorCampo("salarioMaximo");
          //echo "<p>El salarioMaximo introducido: " . $salarioMaximo;
          if ($salarioMaximo!="") $condiciones[] = "salario < ". $salarioMaximo;
          // Hijos
          $hijos=obtenerValorCampo("hijos");
          //echo "<p>El número de hijos: " . $hijos;
          if ($hijos!="") $condiciones[] = "hijos = ". $hijos;               
          /* Crea las condiciones de filtrado. 
          Para ello deberías considerar crear una variable string que se construya como unión de las distintas condiciones.
          También una variable tipo array donde se vayan metiendo las distintas condiciones: la de texto, la de sueldos y la de hijos.
          Entre ambas, se debería construir la sentencia SQL para hacer el filtrado "WHERE..."*/
          $scondicion = "";
          if (count($condiciones)==1){
            $scondicion = $condiciones[0]; 
          }
          else {
            for ($i=0; $i<=count($condiciones) - 2 ; $i++){
              $scondicion .= $condiciones[$i] . " AND ";
            }
            $scondicion .= end($condiciones);
          } 
          
         //echo "<p>Condición completa: </p>" . $scondicion;

         // Realiza la consulta (SELECT) a ejecutar en la base de datos en una variable
          $consulta = "SELECT e.id id, e.nombre nombre, apellidos, email, hijos, salario, nacionalidad,  d.nombre departamento, s.nombre sede 
          FROM empleados e 
          INNER JOIN departamentos d ON 
          d.id = e.departamento_id
          INNER JOIN sedes s ON 
          s.id = d.sede_id
          INNER JOIN paises p ON
          p.id = e.pais_id
          WHERE " ;  
          $consulta .= $scondicion;
          echo "<p>La consulta queda: <br>" . $consulta . "</p>" . PHP_EOL;
        
          // Obten el resultado de ejecutar la consulta para poder recorrerlo. El resultado es de tipo PDOStatement
          $resultado = resultadoConsulta($conexion, $consulta);
          // Muestra los criterios de búsqueda. Hay que tener en cuenta si el filtrado tiene algún resultado o no hay registros con el criterio de búsqueda, ya que si no hay resultados, se debería avisar. 
          if ($resultado->rowCount()==0) echo "<p>La consulta no ha devuelto ningún resultado.</p>" . PHP_EOL;
      

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
              <?php
                if ($privado):
              ?>                    
              <th>Acción</th>
              <?php
                endif;
              ?>
          </thead>
          <tbody>

              <!-- Muestra los datos. Recorre la matriz para ir pintando los campos.-->
    <?php         
            while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
                echo "<tr><td>".$registro->nombre."</td><td>".$registro->apellidos."</td><td>"
                .$registro->email."</td><td>".$registro->hijos."</td><td>".$registro->salario."</td><td>"
                .$registro->nacionalidad."</td><td>".$registro->departamento."</td><td>".$registro->sede."</td>";
                if($privado){
                  echo "<td><a href='modificar.php?idEmpleado=" . $registro->id . "' class='estilo_enlace'>&#9998</a>"; 
                  echo "<a href='borrar.php?idEmpleado=". $registro->id . "' class='confirmacion_borrar'>&#128465</a></td>";
                }
                echo "</tr>" . PHP_EOL;
            }
    ?>              
          </tbody>
        </table>
      
      <div class="contenedor">
          <div class="enlaces">
              <a href="../index.php">Volver a página de listados</a>
              <?php
                if ($privado){
                    echo '<a href="nuevo.php">Añadir</a>&nbsp;';
                    echo '<a href="../acceso/cerrar-sesion.php">Salir</a>';
                }
            ?>
          </div>
      </div>

    <?php
        // Libera el resultado y cierra la conexión
        $resultado = null;
        $conexion = null;
    }   
    ?>
</body>
</html>
