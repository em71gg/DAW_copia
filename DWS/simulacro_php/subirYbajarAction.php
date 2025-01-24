<?php
    session_start();
    if(!isset($_SESSION['resultado'])){
        $_SESSION['resultado'] = 0;
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        switch(true){
            case $_POST['increase']:
                $_SESSION['resultado'] +=1;
                break;
            case $_POST['decrease']:
                $_SESSION['resultado'] -=1;
                break;
            case $_POST['reset']:
                $_SESSION['resultado'] = 0;
        }

        header('location: subirYbajarDisplay.php');
        exit();
    }else{
        header('location: subirYbajarDisplay.php');
    }
?>