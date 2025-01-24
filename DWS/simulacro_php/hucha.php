<?php
    session_start();
    if(!isset($_SESSION['hucha'])){
        $_SESSION['hucha'] =0;
    }
    $hucha = $_SESSION['hucha']
?>

<!DOCTYPE html>
<head></head>
<body>
    <form action="huchaAction.php" method="post">
        <p>Hucha:<?php echo number_format($hucha, 2) . "€.";?></p>
        <p>
            <input type="submit" name="1" value="1€.">
            <input type="submit" name="0,50" value="0,50€.">
            <input type="submit" name="0,25" value="0,25€.">
            <input type="submit" name="0,10" value="0,10€.">
            <input type="submit" name="0,05" value="0,05€.">
        </p>
        <input type="submit" name="reset" value="Vaciar">
    </form>
</body>