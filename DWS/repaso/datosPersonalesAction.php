
<!DOCTYPE html>
<html lang='es'>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
            require_once('../utiles/funciones.php');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $name = obtenerValorCampo('nombre');
                $surname = obtenerValorCampo('apellidos');
                $age = obtenerValorCampo('edad');
                $weight = obtenerValorCampo('peso');

                if(!empty($name)){
                    echo "<p>Su nombre es: ". $name . "</p>".PHP_EOL;
                }else{
                    echo "<p class='error'>Debe introducir un nombre". "</p>".PHP_EOL;
                }
                if(!empty($surname)){
                    echo "<p>Sus apellidos son: ". $surname . "</p>".PHP_EOL;
                }else{
                    echo "<p class='error'>Debe introducir los apellidos.". "</p>".PHP_EOL;
                }
                if(!empty($age)){
                    if(!validarEnteroPositivo($age) || 5 > $age || $age > 130){
                        echo "<p class='error'>Debe  introducir una edad que sea un número entero, positivo y entre 5 y 130 años.". "</p>".PHP_EOL;
                    }else{
                        echo "<p>Su edad es de $age años.". "</p>".PHP_EOL;
                    }
                }else{
                    echo "<p class='error'>Debe introducir su edad.". "</p>".PHP_EOL;
                }
                if(!empty($weight)){
                    if(!validarEnteroPositivo($weight) || 10 > $weight || $weight > 150){
                        echo "<p class='error'>Debe  introducir un peso que sea un número entero, positivo y entre 10 y 150 años.". "</p>".PHP_EOL;
                    }else{
                        echo"<p>Su peso es de $weight kilogramos.<p>".PHP_EOL;
                    }
                }else{
                    echo "<p class='error'>Debe introducir su peso." . "</p></br>".PHP_EOL;
                }

            }
            else{
                header('location: datosPersonales.php');
            }
        ?>
    </body>
</html>