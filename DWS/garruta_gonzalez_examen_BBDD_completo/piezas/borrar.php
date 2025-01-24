<?php
    require_once('../utiles/funciones.php');
    require_once('../utiles/variables.php');
    //$borrarOk = false;
    if($_SERVER['REQUEST_METHOD'] == 'GET'){//Compruebo que se llegue a través de un get.
        if(isset($_GET['idPieza'])){//compruebo que lo que llega a través del get es lo que espero.
            $idPieza = $_GET['idPieza'];

            try{//protejo la conexión y la consulta.
                $conexion = conectarPDO($host, $user, $password, $bbdd);
                $consulta = $conexion -> prepare("DELETE FROM pieza WHERE id=?");
                $consulta -> bindParam(1, $idPieza);
                $consulta -> execute();

                if($consulta -> rowCount()>0){//Si el borrado funciona, $borrarOk a true.
                    echo "Producto eliminado";//$borrarOk =true;
                }else{}
            }catch(PDOException $e){
                echo"No ha sido posible eliminar el producto";
                //echo"<p>" . $e -> getMessage() . "</p>";
             }finally{
                desconectarPDO($consulta, $conexion);
                header('refresh: 3 ; url=listado.php');
                exit();
             }


        }else{
            header('location: listado.php');//Si nos llega a través del get algo diferente a lo esperado, vuelve a listado.
            exit();
        }
    }else{
        header('location: index.html');//Si nos llega algo y no es através de un get devuelvo al index ya que no viene del listado.
        exit();
    }
?>