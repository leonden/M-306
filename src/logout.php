<?php
    session_start();
      if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
        // Session nicht OK,  Weiterleitung auf Anmeldung
        //  Script beenden
        header("Location: index.php");
        die();
    } else {
        
        $_SESSION = array();
        session_destroy();
        header("Location: index.php");
        exit();
    } ?>