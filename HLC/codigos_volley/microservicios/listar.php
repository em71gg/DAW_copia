<?php

include 'conexion.php';


$consulta = "SELECT * FROM personal";
$resultado = $conexion->query($consulta);

while ($fila = $resultado->fetch_assoc()) {
   $producto[] = array_map('utf8_encode', $fila);
}

echo json_encode($producto);
$resultado->close();

?>
