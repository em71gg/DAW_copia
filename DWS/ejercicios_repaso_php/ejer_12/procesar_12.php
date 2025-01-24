<?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recibimos el nombre de la persona
        if(isset($_REQUEST['nombre'])){
            $nombre = trim(htmlspecialchars($_POST['nombre']));//saneamos
        }
        
        if (isset($_POST['deportes']) && is_array($_POST['deportes'])) {//verifica si se han seleccionado deportes
            $deportes = $_POST['deportes']; // Array con los deportes seleccionados
            $cantidadDeportes = count($deportes); // cuenta los deportes seleccionados
            echo "<h3>Hola, $nombre</h3>";
            echo "<p>Practicas $cantidadDeportes " . ($cantidadDeportes>1?"deportes":"deporte"). 
            ".</p>";//formatea a singular o plurar con el condicional abreviado
            echo "<ul>";
            foreach ($deportes as $deporte) {
                echo "<li>$deporte</li>"; // Muestra cada deporte
            }
            echo "</ul>";
        } else {
            echo "<p>No has seleccionado ningún deporte.</p>";
        }
    }
    ?>