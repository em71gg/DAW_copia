<?php
    
    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');

    if(count($_REQUEST)>0){
        if (isset($_GET['idCategoria'])){
            
            $operacionOk = false; 
            $idCategoria = $_GET['idCategoria'];//esto no fucionaba porque el get estaba mal escrito $_Get
            
            $conexion = conectarPDO($host, $user, $password, $bbdd);
            $consulta = $conexion -> prepare ("DELETE FROM categoria WHERE id = ?");
            $consulta -> bindParam(1, $idCategoria);

            try{
                $consulta -> execute();
                $operacionOk = true;
            }catch(PDOException $e){
                $operacionOk =false;
                echo "<p>" . $e -> getMessage() . "</p>";
            }
            if($operacionOk){
                echo "<p>Registro borrado.</p>";
            }else{
                echo "<p>No existe ese registro.</p>";
            }
            header('refresh:3 ; url = listado.php');//aquí tenía 0 en vez de igual
            exit();  
        }
    }else{
        header('location: index.html');
    }
?>    