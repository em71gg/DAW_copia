<?php

include 'conexion.php';
$codigo = $_GET['codigo'];//para poder pasar  la consulta desde android en la string del url por eso el get y no post
                            // porque el código se lo paso para la bu´squeda desde android

$consulta = "SELECT * FROM personal WHERE codigo = '$codigo'";
$resultado = $conexion->query($consulta);

while ($fila = $resultado->fetch_assoc()) {
   $producto[] = array_map('utf8_encode', $fila);
}

echo json_encode($producto);
$resultado->close();

?>
