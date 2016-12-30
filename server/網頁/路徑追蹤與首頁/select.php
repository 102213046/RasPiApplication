<?php
    session_start();
    $name = $_GET['sortn'];
    if($name == null){
        header('Location: admit.php');
    }
    else{
        $_SESSION['nfc'] = $name;
        header('Location: gpsnow.php');
    }
    fclose($file);
?>