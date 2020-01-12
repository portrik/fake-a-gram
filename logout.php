<?php
    ini_set('display_errors', false);
    session_start();

    if(isset($_SESSION["username"]))
    {
        unset($_SESSION["username"]);
    }

    header('Location: /~dvorap74/fake-a-gram/');
?>