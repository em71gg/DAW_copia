<?php
    session_start();
    $totalCasillas = $_SESSION['totalCasillas'];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $checked = count($_REQUEST);
        $string = implode(", ", $_REQUEST);
    }
?>

<!DOCTYPE html>

<html lang='es'>
    <head></head>
    <body>
    <h1>TABLA CUADRADA CON CASILLAS DE VERIFICACIÓN</h1>
    <h1>(RESULTADO 2)</h1>
    <p>Ha marcado <?php echo $checked . " "?> casillas de un total de <?php echo $totalCasillas . " casillas: " . $string?>
    </body>
</html>