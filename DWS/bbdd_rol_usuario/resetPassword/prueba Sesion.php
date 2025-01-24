<?php
    session_name('reset_password');
    session_start();
    $validatedEmail = isset($_SESSION['validatedEmail']) ? $_SESSION['validatedEmail'] : "1";
    echo "validatedEmail = $validatedEmail";
    $erroresMail = [];
    $erroresPass = [];
    
?>