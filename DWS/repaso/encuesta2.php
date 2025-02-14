<?php
require_once('./utiles/funciones.php');
$preguntas= obtenerValorCampo('preguntas');
$respuestas = obtenerValorCampo('respuestas');
session_start();
    $_SESSION['preguntas'] = $preguntas;




?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        main{
            display: flex;
            flex-direction: column;
        }
    </style>

</head>

<body>
    <header>

    </header>

    <main>
        

        <div>
        <form action="encuesta3.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                        <?php
                            for($i=1 ; $i <=$respuestas; $i ++){
                                echo"<th>$i</th>";
                            }
                        ?>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        
                        for ($i = 1; $i <= $preguntas; $i ++){
                            echo "<tr>";
                            echo "<td>Pregunta $i</td>";
                            for($j = 1; $j <=$respuestas; $j ++){
                                
                                for($j = 1; $j<= $respuestas; $j++){
                                    echo "<td><input type='radio' value ='$j' name ='$i'></td>";
                                }
                               //echo "</tr>";
                                
                            }
                            echo "</tr>";
                        }
                        ?>
                        
                    </tbody>
                </table>
                <input type="submit" value="Enviar">
                <input type="reset" value="Borrar">
            </form>
        </div>
    </main>
</body>

</html>