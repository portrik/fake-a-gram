<?php
    include('db_communication.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $conn = get_connection();
        $value = $_POST['value'];

        switch($_POST['type'])
        {
            case 'username':
                echo(check_username($conn, $value));
                break;
            case 'email':
                echo(check_email($conn, $value));
                break;
            case 'login':
                echo(login($conn, $value, $_POST['secondValue']));
                break;
        }
    }

    header('Location: /');

    function check_username($conn, $username)
    {
        if (get_user_id($conn, $username) == -1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
?>