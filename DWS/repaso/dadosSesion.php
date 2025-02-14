<?php
session_start();

// Si se presiona "Borrar", reiniciar la puntuación y eliminar la jugada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resetear'])) {
    $_SESSION['maxScore'] = 0;
    $_SESSION['juego'] = [];
    session_destroy();
}

// Obtener la puntuación máxima almacenada en la sesión
if (!isset($_SESSION['maxScore'])) {
    $_SESSION['maxScore'] = 0;
}

$maxScore = $_SESSION['maxScore'];
$juego = []; // Inicializar jugada
$repeticiones = []; // Inicializar repeticiones

// Si se presiona "Lanzar", generar una nueva jugada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['jugar'])) {
    $tirada = [1, 2, 3, 4, 5, 6];
    $n = rand(1,8); // Número de dados a lanzar
    $juego = [];

    for ($i = 0; $i < $n; $i++) {
        $rdo = array_rand($tirada);
        $juego[] = $tirada[$rdo];
    }

    // Contar repeticiones de cada número
    $juegoOrdenado = array_count_values($juego);
    arsort($juegoOrdenado);
    foreach ($juegoOrdenado as $valor => $veces) {
        $repeticiones[] = "$valor -> $veces veces";
    }

    // Calcular la puntuación total y actualizar la mejor puntuación
    $totalScore = array_sum($juego);
    $_SESSION['maxScore'] = max($totalScore, $maxScore);
}

// Mostrar los resultados si se ha generado una jugada
if (!empty($juego)) {
    echo "En la jugada se han lanzado " . count($juego) . " dados y las puntuaciones han sido: " . implode(", ", $juego) . " puntos.</br>";
    echo "El resultado total ha sido de: " . array_sum($juego) . " puntos.</br>";
    echo "En la jugada han salido: el " . implode(", el ", $repeticiones) . ".</br>";
}

// Mostrar la puntuación máxima hasta el momento
echo "La puntuación máxima hasta el momento es: " . $_SESSION['maxScore'] . ".";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <main>
        <div>
            <form action="" method="post">
                <input type="submit" name="jugar" value="Lanzar">
                <input type="submit" name="resetear" value="Borrar">
            </form>
        </div>
    </main>
</body>
</html>
