<?php
session_start();
    if(!$_SESSION) {
        header("Location: login.php");
    }
?>
Success!
<a href='logout.php'> Log out </a>