<?php
if($_SERVER['REQUEST_METHOD'] =='POST'){
    $dimension = isset($_POST['tabla']) ? $_POST['tabla'] : null;
    if($dimension != null){
        session_start();
        $_SESSION['dimension'] = $dimension;
    }
    
}else{header('location: ej4a.php');}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<style>
    main{
        display: flex;
        flex-direction: column;
    }
</style>
</head>

<body>
    <main>
        <div>
            <h1>Tabla cuadrada con casillas de verificacion (Resultado1)</h1>
            <p>Marque las casillas que quiera</p>


        </div>
        <div>
            <form action="ej4c.php" method="post">
                <table>
                    <tbody>
                        <?php
                        $num = 1;
                        for ($i = 1; $i <= $dimension; $i ++){
                            echo "<tr>";
                            for($j = 1; $j <=$dimension; $j ++){
                                echo "<td><label for='" . $num . "'>" . $num ."</label><input type='checkbox' value ='" . $num . "' name = '" . $num ."' id='" . $num . "'></td>";
                                $num ++;
                                
                            }
                            echo "</tr>";
                        }
                        ?>
                        
                    </tbody>
                </table>
                <input type="submit" value="Enviar">
            </form>
        </div>
        <div></div>

    </main>

</body>

</html>