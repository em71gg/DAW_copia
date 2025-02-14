

<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <h1>TABLA CUADRADA CON CASILLAS DE VERIFICACIÓN</h1>
        <h1>(RESULTADO 1)</h1>
        <?php
        session_start();
        if(!isset($_SESSION['totalCasillas'])){
            $_SESSION['totalCasillas']= "";
        }
            require_once('../utiles/funciones.php');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $rango = obtenerValorCampo('rango');
                $_SESSION['totalCasillas'] = $rango * $rango;
                if(!empty($rango)){
                    if(!validarEnteroPositivo($rango) || $rango >20 || $rango ==0){
                        echo "<p class='error'>Debe definir un rango positivo, mayor que 0 y menor o igual a 20.</p>".PHP_EOL;
                        header('refresh: 3; url= casillasVerificacion1.php');
                    }else{
                        $rangoMatriz = $rango;
                    }
                }else{
                    echo "<p class='error'>Debe definir un rango</p>".PHP_EOL;
                    header('refresh: 3; url= casillasVerificacion1.php');
                }
            }else{
                header('location: casillasVerificacion1.php');
            }
            
        ?>
        <form action='casillasVerificacion3.php' method='post'>
                <table>
                    <tb>
                        <?php
                            $name=1;
                        
                            for($i= 1; $i <= $rangoMatriz; $i++){
                                echo"<tr>";
                                for($j = 1; $j <= $rangoMatriz; $j++){
                                    echo "<td>
                                            <input type='checkbox' name='". $name ."' value='" . $name ."'><span>" . $name. "</span>
                                        </td>";
                                    $name +=1;
                                }
                                echo"</tr>".PHP_EOL;
                            }
                        ?>
                    </tb>
                </table>
            <input type='submit' value='Enviar'>
        </form>
    
    </body>
</html>