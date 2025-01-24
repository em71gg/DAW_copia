<!--
Confeccionar  una  pagina  que  muestre  un  contrato,  disponer  puntos  suspensivos  donde  el 
operador  debe  ingresar  un  texto.  La  pagina  que  procesa  el  formulario  solo  debe  mostrar  el 
contrato con las modificaciones que hizo el operador. 
Ej. De un contrato puede ser: 
 
En la ciudad de [........], se acuerda entre la Empresa [........] representada por el Sr. [........] 
en su carácter de Apoderado, con domicilio en la calle [........] y el Sr. [........], futuro 
empleado con domicilio en [........], celebrar el presente contrato a Plazo Fijo, de acuerdo a 
la normativa vigente de los articulos 90, 92, 93, 94, 95 y concordantes de la Ley de Contrato 
de Trabajo No. 20744.
-->

<!-- No he encontrado la forma de poner puntos suspensivos-->

<?php


$ciudad = ""; //declaración variables que se incluyen en las lineas html
$empresa = "";
$representante = "";
$domicilio_empresa = "";
$empleado = "";
$domicilio_empleado = "";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contrato</title>
</head>
<body>
    <h2>Contrato de Trabajo</h2>
    <form action="procesar_13.php" method="POST">
        
        <p>
            En la ciudad de <input type="text" name="ciudad" value="<?= $ciudad ?>" required> 
            se acuerda entre la Empresa <input type="hidden" name="empresa" value="<?= $empresa ?>" required> 
            representada por el Sr. <input type="text" name="representante" value="<?= $representante ?>" required> 
            en su carácter de Apoderado, con domicilio en la calle <input type="text" name="domicilio_empresa" 
            value="<?= $domicilio_empresa ?>" required> 
            y el Sr. <input type="text" name="empleado" value="<?= $empleado ?>" required>, 
            futuro empleado con domicilio en <input type="text" name="domicilio_empleado" 
            value="<?= $domicilio_empleado ?>" required>, 
            celebrar el presente contrato a Plazo Fijo, de acuerdo a la normativa vigente de los 
            artículos 90, 92, 93, 94, 95 y concordantes de la Ley de Contrato de Trabajo No. 20744.
        </p>
        <input type="submit" value="Generar contrato">
    </form>

    

</body>
</html>