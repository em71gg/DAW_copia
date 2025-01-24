<?php
    session_start();
    if(!isset($_SESSION['hucha'])){
        $_SESSION['hucha'] =0;
    }
    //$hucha = $_SESSION['hucha'];

    if($_SERVER['REQUEST_METHOD']== 'POST'){
        switch (true){
            case $_POST['1']:
                $_SESSION['hucha'] += 1;
                break;
            case $_POST['0,50']:
                $_SESSION['hucha'] += 0.5;
                break;
            case $_POST['0,25']:
                $_SESSION['hucha'] += 0.25;
                break;
            case $_POST['0,10']:
                $_SESSION['hucha'] += 0.1;
                break;
            case $_POST['0,05']:
                $_SESSION['hucha'] += 0.05;
                break;
            case $_POST['reset']:
                $_SESSION['hucha'] = 0;
                break;
        }
        header('location: hucha.php');
        exit();
    }else{
        header('location: hucha.php');
    }
    
?>