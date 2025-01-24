<?php//conexion.php
$hostname= 'localhost';
$database= 'registro_producto';
$username= 'root';
$password= '';
$conexion= new mysqli($hostname, $username, $password, $database);
if ($conexion->connect_errno) {
echo "lo sentimos, error al conectar";
}
?>


<?php
//insertar_producto.php
include 'conexion.php';
$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$precio=$_POST['precio'];
$fabricante=$_POST['fabricante'];
$consulta="insert into producto values('".$codigo."', '".$nombre."','".$precio."','".$fabricante."')";
mysqli_query($conexion, $consulta) or die(mysqli_error());
mysqli_close($conexion);
?>


<?php //buscar_producto.php
include 'conexion.php';
$codigo = $_GET['codigo'];
$consulta = "SELECT * FROM producto WHERE codigo = '$codigo'";
$resultado = $conexion->query($consulta);
while ($fila = $resultado->fetch_assoc()) {
$producto[] = array_map('utf8_encode', $fila);
}
echo json_encode($producto);
$resultado->close();
?>


<?php //editar_producto.php
include 'conexion.php';
$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$precio=$_POST['precio'];
$fabricante=$_POST['fabricante'];
$consulta="update producto set nombre = '".$nombre."', precio='".$precio."', fabricante='".$fabricante."' where
codigo = '".$codigo."'";
mysqli_query($conexion, $consulta) or die(mysqli_error());
mysqli_close($conexion);
?>

<?php //eliminar_producto.php
include 'conexion.php';
$codigo=$_POST['codigo'];
$consulta="delete from producto where codigo = '".$codigo."'";
mysqli_query($conexion, $consulta) or die(mysqli_error());
mysqli_close($conexion);
?>


<?php// posible Listar.php
include 'conexion.php';

$consulta = "SELECT * FROM producto";
$resultado = $conexion->query($consulta);

$productos = array();

while ($fila = $resultado->fetch_assoc()) {
    $productos[] = array_map('utf8_encode', $fila);
}

echo json_encode($productos);
$resultado->close();
$conexion->close();
?>