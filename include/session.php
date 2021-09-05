<?php

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION["userInfo"])){
    if(time() - $_SESSION["userInfo"]["expireTime"] > 1){
        session_unset();
        session_destroy();
        echo "<script>window.location.href='http://localhost/phoneBook/login.php';</script>";
    }
}


?>
