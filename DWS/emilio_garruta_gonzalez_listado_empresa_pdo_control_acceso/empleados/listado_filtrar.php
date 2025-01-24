<?php
  // Activa las sesiones
	session_name("sesion-privada");
	session_start();
	// Comprueba si existe la sesión "email", en caso contrario vuelve a la página de login
	if (!isset($_SESSION["email"])) header("Location: ../login.php");
    // Incluye ficheros de variables y funciones
    require_once("../utiles/variables.php");
    require_once("../utiles/funciones.php");

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de empleados</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <header>
        <div class="header-container">
            
            <a href="../cerrar-sesion.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </header>
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
      //$condiciones = "";
      $consultaSinRegistros = false; //variable necesaria para definir si lanzaré un mensaje 
                                    //por consulta vacía luego si exisate consulta y no arroja
                                    //resultados se cambia a true
      $condiciones = [];
        
      if (isset($_POST['texto']) || isset($_POST['salarioMinimo']) 
      || isset($_POST['salarioMaximo'])  || isset($_POST['hijos'])){//si alguna de las variables de $_post existe
                                                                    // entro en el bloque de proceso, si no le 
                                                                    //indico que debe introducir algún valor de filtrado
        
        if(isset($_POST['texto'])){//Si se ha enviado el campo de texto

          $texto=obtenerValorCampo('texto');//uso obtenerCampo para quedarme con el texto filtrado
          if($texto !== ""){//Si el texto no está en blanco. Añado a la matriz condiciones su contenido
            $condiciones[] = "(e.nombre LIKE '%" .$texto. "%' OR e.apellidos LIKE '%" .$texto. "%' OR e.email LIKE '%" .$texto. "%')";
          }
        }

        if(isset($_POST['salarioMinimo'])){

          $salarioMin = obtenerValorCampo('salarioMinimo');
          if($salarioMin !== ""){
            $condiciones[] =  "e.salario > " . $salarioMin ."";
          }
        }

        if(isset($_POST['salarioMaximo'])){
          $salarioMax = obtenerValorCampo('salarioMaximo');
          if($salarioMax !== ""){
            $condiciones [] = "e.salario < " . $salarioMax. "";
          }
        }

        if(isset($_POST['hijos'])){
          $hijos =obtenerValorCampo('hijos');
          if($hijos !==""){
            $condiciones []= "e.hijos = ".  $hijos . "";
          }
        }
        if(count($condiciones) >0){
          $stringWhere = implode(" AND ", $condiciones);

          echo "<p>El string del Where para la consulta es: " . $stringWhere . "</br>".PHP_EOL;

          $conexion= conectarPDO($host, $user, $password, $bbdd);

          $consulta = ("SELECT 
                      e.nombre as nombre, 
                      e.apellidos,
                      e.email,
                      e.hijos, 
                      e.salario,
                      p.nacionalidad,
                      d.nombre as departamento,
                      s.nombre as sede
                    FROM
                      empleados e 
                      INNER JOIN departamentos d ON d.id=e.departamento_id
                      INNER JOIN sedes s ON s.id=d.sede_id
                      INNER JOIN paises p ON p.id=e.pais_id
                    
                    WHERE
                      {$stringWhere}
                    ");

          $resultado = resultadoConsulta($conexion, $consulta);
          
          if($resultado -> rowCount() === 0){//Si no hay resultados lo reflejo en la variable
            $consultaSinRegistros = true; 
          }
        }else{
          echo "<p>Debe introducir algún valor de filtrado en el formulario</p>".PHP_EOL;
        }
        
      }
       // echo "<p>Laconsulta queda" .$consulta;
      
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

          <?php

            if(isset($resultado) && $resultado !== null /*&& $resultado -> rowCount() >0*/){
              while ($registro = $resultado -> fetch(PDO::FETCH_ASSOC)){
                echo "<tr>
                    <td>" . $registro['nombre'] . "</td>
                    <td>" . $registro['apellidos']. "</td>
                    <td>" . $registro['email'] . "</td>
                    <td>" . $registro ['hijos'] . "</td>
                    <td>" . $registro ['salario'] . "</td>
                    <td>" . $registro['nacionalidad'] . "</td>
                    <td>" . $registro ['departamento'] . "</td>
                    <td>" . $registro ['sede'] . "</td>
                </tr>".PHP_EOL;
             }

            }if($consultaSinRegistros){//Si la variable está fijada a true imprimo el mensaje
              echo"<p>No se encuentran resultados con esos criterios de búsqueda.</p>".PHP_EOL;
            }
              
          ?>
            
              
          </tbody>
        </table>
      
      <div class="contenedor">
          <div class="enlaces">
              <a href="../index.php">Volver a página de listados</a>
          </div>
      </div>

    <?php

      $resultado= null;  // Libera el resultado y cierra la conexión
      $conexion =null;
    ?>
</body>
</html>
